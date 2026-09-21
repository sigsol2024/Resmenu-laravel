<?php

namespace Tests\Unit;

use App\Services\CRM\ConsentService;
use App\Services\CRM\ContactLeadService;
use App\Services\CRM\CRMService;
use App\Services\CRM\DTOs\ContactPayload;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Mockery;
use Tests\TestCase;

class ContactLeadConsentRetryTest extends TestCase
{
    private function crmTablesAvailable(): bool
    {
        try {
            return Schema::hasTable('crm_leads') && Schema::hasTable('crm_consent_events');
        } catch (\Throwable) {
            return false;
        }
    }

    public function test_retry_never_sets_should_subscribe(): void
    {
        if (! $this->crmTablesAvailable()) {
            $this->markTestSkipped('CRM tables not available.');
        }

        $email = 'retry-'.uniqid().'@example.com';
        $leadId = (int) DB::table('crm_leads')->insertGetId([
            'email' => $email,
            'name' => 'Retry',
            'phone' => '+2348000000001',
            'source' => 'newsletter',
            'marketing_consent' => 1,
            'marketing_consent_at' => now(),
            'marketing_consent_source' => 'newsletter',
            'marketing_consent_text_version' => 'v1',
            'sync_status' => 'failed',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $crm = Mockery::mock(CRMService::class);
        $crm->shouldReceive('syncContact')
            ->once()
            ->with(Mockery::on(function (ContactPayload $payload) {
                return $payload->shouldSubscribe === false;
            }))
            ->andReturn(['success' => true, 'external_id' => 'hs-retry', 'message' => 'ok']);

        $service = new ContactLeadService(app(ConsentService::class), $crm);
        $result = $service->retryLead($leadId);

        $this->assertTrue($result['success'] ?? false);

        DB::table('crm_leads')->where('id', $leadId)->delete();
    }

    public function test_fresh_consent_sets_should_subscribe(): void
    {
        if (! $this->crmTablesAvailable()) {
            $this->markTestSkipped('CRM tables not available.');
        }

        $email = 'fresh-'.uniqid().'@example.com';

        $crm = Mockery::mock(CRMService::class);
        $crm->shouldReceive('syncContact')
            ->once()
            ->with(Mockery::on(function (ContactPayload $payload) {
                return $payload->shouldSubscribe === true;
            }))
            ->andReturn(['success' => true, 'external_id' => 'hs-fresh', 'message' => 'ok']);

        $service = new ContactLeadService(app(ConsentService::class), $crm);
        $result = $service->capture([
            'email' => $email,
            'source' => 'contact_form',
            'marketing_consent' => true,
            'marketing_consent_text_version' => 'client-ignored-v9',
        ]);

        $this->assertNotNull($result['lead_id']);
        $version = DB::table('crm_consent_events')->where('email', $email)->value('text_version');
        $this->assertSame(config('resmenu.marketing_consent_text_version', 'v1'), $version);

        DB::table('crm_leads')->where('email', $email)->delete();
        DB::table('crm_consent_events')->where('email', $email)->delete();
    }

    public function test_dedup_reuses_lead_within_window(): void
    {
        if (! $this->crmTablesAvailable()) {
            $this->markTestSkipped('CRM tables not available.');
        }

        $email = 'dedup-'.uniqid().'@example.com';
        $crm = Mockery::mock(CRMService::class);
        $crm->shouldReceive('syncContact')->twice()->andReturn(['success' => true, 'external_id' => 'x', 'message' => 'ok']);

        $service = new ContactLeadService(app(ConsentService::class), $crm);
        $first = $service->capture(['email' => $email, 'source' => 'newsletter', 'marketing_consent' => false]);
        $second = $service->capture(['email' => $email, 'source' => 'newsletter', 'marketing_consent' => false]);

        $this->assertSame($first['lead_id'], $second['lead_id']);
        $this->assertTrue($second['deduped']);

        DB::table('crm_leads')->where('email', $email)->delete();
        DB::table('crm_consent_events')->where('email', $email)->delete();
    }
}
