<?php

namespace App\Services\CRM;

use App\Services\CRM\DTOs\ContactPayload;

interface CRMProviderInterface
{
    public function key(): string;

    public function testConnection(): array;

    public function syncContact(ContactPayload $payload): array;

    /** @return array{enabled: bool, portal_id: ?string, tracking: bool, live_chat: bool} */
    public function publicWebsiteConfig(): array;
}
