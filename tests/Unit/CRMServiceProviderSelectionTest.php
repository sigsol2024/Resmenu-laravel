<?php

namespace Tests\Unit;

use App\Services\CRM\CRMService;
use App\Services\CRM\Providers\Mailchimp\MailchimpProvider;
use Tests\TestCase;

class CRMServiceProviderSelectionTest extends TestCase
{
    public function test_mailchimp_provider_is_stub(): void
    {
        $provider = new MailchimpProvider();
        $this->assertSame('mailchimp', $provider->key());
        $result = $provider->syncContact(\App\Services\CRM\DTOs\ContactPayload::fromArray([
            'email' => 'a@example.com',
            'source' => 'newsletter',
        ]));
        $this->assertTrue($result['skipped'] ?? false);
    }

    public function test_crm_service_public_config_safe_when_disabled(): void
    {
        $cfg = app(CRMService::class)->publicWebsiteConfig();
        $this->assertArrayHasKey('enabled', $cfg);
        $this->assertArrayHasKey('portal_id', $cfg);
        $this->assertArrayHasKey('tracking', $cfg);
        $this->assertArrayHasKey('live_chat', $cfg);
    }
}
