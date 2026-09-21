<?php

namespace App\Services\CRM\Providers\HubSpot;

use App\Services\CRM\CRMProviderInterface;
use App\Services\CRM\DTOs\ContactPayload;
use App\Support\CrmEncryption;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class HubSpotProvider implements CRMProviderInterface
{
    private const API_BASE = 'https://api.hubapi.com';

    public function key(): string
    {
        return 'hubspot';
    }

    public function testConnection(): array
    {
        $token = $this->token();
        if ($token === '') {
            return ['success' => false, 'message' => 'Private App token is not configured.'];
        }

        try {
            $response = Http::withToken($token)
                ->timeout(15)
                ->get(self::API_BASE.'/crm/v3/objects/contacts', ['limit' => 1]);

            if ($response->successful()) {
                return ['success' => true, 'message' => 'Connected to HubSpot successfully.'];
            }

            return ['success' => false, 'message' => 'HubSpot API error: HTTP '.$response->status()];
        } catch (\Throwable $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function syncContact(ContactPayload $payload): array
    {
        $token = $this->token();
        if ($token === '') {
            return ['success' => false, 'skipped' => true, 'message' => 'HubSpot token missing'];
        }

        $settings = $this->settings();
        $properties = $this->buildProperties($payload, forUpdate: false);

        $search = Http::withToken($token)->timeout(20)->post(self::API_BASE.'/crm/v3/objects/contacts/search', [
            'filterGroups' => [[
                'filters' => [[
                    'propertyName' => 'email',
                    'operator' => 'EQ',
                    'value' => $payload->email,
                ]],
            ]],
            'limit' => 1,
        ]);

        $externalId = null;
        if ($search->successful() && ! empty($search->json('results.0.id'))) {
            $externalId = (string) $search->json('results.0.id');
            $updateProps = $this->buildProperties($payload, forUpdate: true);
            if ($updateProps !== []) {
                $update = Http::withToken($token)->timeout(20)->patch(
                    self::API_BASE.'/crm/v3/objects/contacts/'.$externalId,
                    ['properties' => $updateProps]
                );
                if (! $update->successful()) {
                    return ['success' => false, 'message' => 'Failed to update HubSpot contact: HTTP '.$update->status()];
                }
            }
        } else {
            $create = Http::withToken($token)->timeout(20)->post(self::API_BASE.'/crm/v3/objects/contacts', [
                'properties' => $properties,
            ]);
            if (! $create->successful()) {
                return ['success' => false, 'message' => 'Failed to create HubSpot contact: HTTP '.$create->status()];
            }
            $externalId = (string) $create->json('id');
        }

        // Subscribe only on fresh consent events (shouldSubscribe). Ordinary retries never set this.
        if ($payload->shouldSubscribe && $settings && (bool) $settings->sync_marketing_consent) {
            $subResult = $this->subscribeMarketing($token, $payload->email, (string) ($settings->hubspot_subscription_type_id ?? ''));
            if (! ($subResult['success'] ?? false)) {
                Log::info('HubSpot contact synced but consent sync failed', [
                    'consent_event_id' => $payload->consentEventId,
                    'error' => $subResult['message'] ?? '',
                ]);
            }
        }

        return [
            'success' => true,
            'external_id' => $externalId,
            'message' => 'HubSpot contact synced',
        ];
    }

    public function publicWebsiteConfig(): array
    {
        $settings = $this->settings();

        return [
            'enabled' => (bool) ($settings?->enabled),
            'portal_id' => $settings?->hubspot_portal_id,
            'tracking' => (bool) ($settings?->website_tracking),
            'live_chat' => (bool) ($settings?->live_chat && $settings?->website_tracking),
        ];
    }

    /** @return array<string, string> */
    private function buildProperties(ContactPayload $payload, bool $forUpdate): array
    {
        $properties = ['email' => $payload->email];

        $name = $payload->name !== null ? trim(mb_substr($payload->name, 0, 100)) : '';
        if ($name !== '') {
            $parts = preg_split('/\s+/', $name, 2) ?: [];
            $properties['firstname'] = $parts[0] ?? $name;
            if (! empty($parts[1])) {
                $properties['lastname'] = mb_substr($parts[1], 0, 100);
            }
        } elseif ($forUpdate) {
            // merge-safe: do not wipe existing name with blanks
        }

        $phone = $payload->phone !== null ? trim(mb_substr($payload->phone, 0, 40)) : '';
        if ($phone !== '') {
            $properties['phone'] = $phone;
        }

        if ($forUpdate) {
            unset($properties['email']); // email is identity; avoid unnecessary overwrite
        }

        return $properties;
    }

    private function settings(): ?object
    {
        if (! Schema::hasTable('crm_settings')) {
            return null;
        }

        return DB::table('crm_settings')->where('id', 1)->first();
    }

    private function token(): string
    {
        $settings = $this->settings();
        if (! $settings || empty($settings->hubspot_private_app_token_encrypted)) {
            return '';
        }

        return CrmEncryption::decrypt($settings->hubspot_private_app_token_encrypted);
    }

    private function subscribeMarketing(string $token, string $email, string $subscriptionId): array
    {
        if ($subscriptionId === '') {
            return ['success' => false, 'message' => 'Subscription type ID not configured'];
        }

        try {
            $response = Http::withToken($token)->timeout(20)->post(
                self::API_BASE.'/communication-preferences/v4/statuses/'.rawurlencode($email),
                [
                    'channel' => 'EMAIL',
                    'statusState' => 'SUBSCRIBED',
                    'subscriptionId' => (int) $subscriptionId,
                    'legalBasis' => 'CONSENT_WITH_NOTICE',
                    'legalBasisExplanation' => 'Opted in via Resmenu form',
                ]
            );

            if ($response->successful()) {
                return ['success' => true];
            }

            $legacy = Http::withToken($token)->timeout(20)->post(
                self::API_BASE.'/communication-preferences/v3/subscribe',
                [
                    'emailAddress' => $email,
                    'subscriptionId' => (int) $subscriptionId,
                    'legalBasis' => 'CONSENT_WITH_NOTICE',
                    'legalBasisExplanation' => 'Opted in via Resmenu form',
                ]
            );

            if ($legacy->successful()) {
                return ['success' => true];
            }

            return ['success' => false, 'message' => 'Consent API HTTP '.$response->status()];
        } catch (\Throwable $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
