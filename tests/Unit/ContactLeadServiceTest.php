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

class ContactLeadServiceTest extends TestCase
{
    public function test_capture_records_local_consent_and_calls_crm_service(): void
    {
        try {
            if (! Schema::hasTable('crm_leads') || ! Schema::hasTable('crm_consent_events')) {
                $this->markTestSkipped('CRM tables not available.');
            }
        } catch (\Throwable) {
            $this->markTestSkipped('CRM tables not available.');
        }

        $email = 'lead-'.uniqid().'@example.com';

        $crm = Mockery::mock(CRMService::class);
        $crm->shouldReceive('syncContact')
            ->once()
            ->with(Mockery::type(ContactPayload::class))
            ->andReturn(['success' => true, 'external_id' => 'hs-1', 'message' => 'ok']);

        $service = new ContactLeadService(app(ConsentService::class), $crm);
        $result = $service->capture([
            'email' => $email,
            'name' => 'Lead User',
            'phone' => '+2348000000000',
            'source' => 'newsletter',
            'marketing_consent' => true,
            'marketing_consent_text_version' => 'v1',
        ]);

        $this->assertNotNull($result['lead_id']);
        $this->assertTrue($result['sync']['success']);

        $this->assertTrue(
            DB::table('crm_consent_events')->where('email', $email)->where('marketing_consent', 1)->exists()
        );

        DB::table('crm_leads')->where('email', $email)->delete();
        DB::table('crm_consent_events')->where('email', $email)->delete();
    }

    public function test_rejects_non_phase1_sources(): void
    {
        $crm = Mockery::mock(CRMService::class);
        $crm->shouldNotReceive('syncContact');
        $service = new ContactLeadService(app(ConsentService::class), $crm);

        $this->expectException(\InvalidArgumentException::class);
        $service->capture([
            'email' => 'x@example.com',
            'source' => 'orders',
            'marketing_consent' => false,
        ]);
    }
}
