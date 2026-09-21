<?php

namespace App\Services\CRM\Providers\Mailchimp;

use App\Services\CRM\CRMProviderInterface;
use App\Services\CRM\DTOs\ContactPayload;

/**
 * Future provider stub — interface-compatible only.
 */
class MailchimpProvider implements CRMProviderInterface
{
    public function key(): string
    {
        return 'mailchimp';
    }

    public function testConnection(): array
    {
        return ['success' => false, 'message' => 'Mailchimp provider is not implemented yet.'];
    }

    public function syncContact(ContactPayload $payload): array
    {
        return ['success' => false, 'skipped' => true, 'message' => 'Mailchimp provider is not implemented yet.'];
    }

    public function publicWebsiteConfig(): array
    {
        return [
            'enabled' => false,
            'portal_id' => null,
            'tracking' => false,
            'live_chat' => false,
        ];
    }
}
