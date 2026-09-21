<?php

namespace App\Services\CRM;

use App\Services\CRM\DTOs\ContactPayload;
use App\Services\CRM\Providers\HubSpot\HubSpotProvider;
use App\Services\CRM\Providers\Mailchimp\MailchimpProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class CRMService
{
    public function settings(): ?object
    {
        if (! Schema::hasTable('crm_settings')) {
            return null;
        }

        return DB::table('crm_settings')->where('id', 1)->first();
    }

    public function isEnabled(): bool
    {
        $settings = $this->settings();

        return $settings && (bool) $settings->enabled;
    }

    public function activeProvider(): ?CRMProviderInterface
    {
        $settings = $this->settings();
        if (! $settings || ! $settings->enabled) {
            return null;
        }

        return match ($settings->provider) {
            'hubspot' => app(HubSpotProvider::class),
            'mailchimp' => app(MailchimpProvider::class),
            default => null,
        };
    }

    public function syncContact(ContactPayload $payload): array
    {
        $provider = $this->activeProvider();
        if (! $provider) {
            return ['success' => false, 'skipped' => true, 'message' => 'CRM disabled'];
        }

        $settings = $this->settings();
        if ($settings && ! $settings->sync_contacts) {
            return ['success' => false, 'skipped' => true, 'message' => 'Contact sync disabled'];
        }

        try {
            $result = $provider->syncContact($payload);
            $this->logSync($provider->key(), 'sync_contact', $payload->email, $payload->crmLeadId, $result['success'] ?? false, $result['message'] ?? null, $payload->toArray());

            return $result;
        } catch (\Throwable $e) {
            Log::warning('CRM syncContact failed: '.$e->getMessage());
            $this->logSync($provider->key(), 'sync_contact', $payload->email, $payload->crmLeadId, false, $e->getMessage(), $payload->toArray());

            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function testConnection(): array
    {
        $provider = $this->activeProvider();
        if (! $provider) {
            return ['success' => false, 'message' => 'CRM is disabled or provider missing.'];
        }

        return $provider->testConnection();
    }

    /** @return array{enabled: bool, portal_id: ?string, tracking: bool, live_chat: bool, provider: ?string} */
    public function publicWebsiteConfig(): array
    {
        $settings = $this->settings();
        if (! $settings || ! $settings->enabled) {
            return [
                'enabled' => false,
                'portal_id' => null,
                'tracking' => false,
                'live_chat' => false,
                'provider' => null,
            ];
        }

        $provider = $this->activeProvider();
        $cfg = $provider ? $provider->publicWebsiteConfig() : [
            'enabled' => false,
            'portal_id' => null,
            'tracking' => false,
            'live_chat' => false,
        ];

        return array_merge($cfg, ['provider' => $settings->provider]);
    }

    public function decryptedHubSpotToken(): string
    {
        $settings = $this->settings();
        if (! $settings || empty($settings->hubspot_private_app_token_encrypted)) {
            return '';
        }

        return \App\Support\CrmEncryption::decrypt($settings->hubspot_private_app_token_encrypted);
    }

    /** @param array<string, mixed>|null $payload */
    private function logSync(string $provider, string $action, ?string $email, ?int $leadId, bool $ok, ?string $message, ?array $payload = null): void
    {
        if (! Schema::hasTable('crm_sync_logs')) {
            return;
        }

        DB::table('crm_sync_logs')->insert([
            'provider' => $provider,
            'action' => $action,
            'email' => $email,
            'crm_lead_id' => $leadId,
            'status' => $ok ? 'success' : 'failed',
            'message' => $message,
            'payload' => $payload ? json_encode($payload) : null,
            'created_at' => now(),
        ]);
    }
}
