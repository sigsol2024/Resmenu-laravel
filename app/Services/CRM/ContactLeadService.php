<?php

namespace App\Services\CRM;

use App\Services\CRM\DTOs\ContactPayload;
use App\Support\PhoneNormalizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;

class ContactLeadService
{
    public function __construct(
        private ConsentService $consent,
        private CRMService $crm,
    ) {}

    /**
     * Unified entry for Phase-1 lead sources.
     *
     * @param  array<string, mixed>  $data
     * @return array{lead_id: ?int, sync: array<string, mixed>, deduped: bool}
     */
    public function capture(array $data, ?Request $request = null): array
    {
        $source = (string) ($data['source'] ?? '');
        if (! in_array($source, ConsentService::SOURCES, true)) {
            throw new \InvalidArgumentException('Unsupported CRM lead source: '.$source);
        }

        $email = strtolower(trim((string) ($data['email'] ?? '')));
        if ($email === '' || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Valid email is required.');
        }

        $phoneRaw = $data['phone'] ?? null;
        $phone = null;
        if ($phoneRaw !== null && trim((string) $phoneRaw) !== '') {
            $phone = PhoneNormalizer::normalize((string) $phoneRaw);
            if ($phone === null) {
                throw new \InvalidArgumentException('Invalid phone number.');
            }
        }

        // Server-owned consent text version — ignore any client-supplied version.
        $version = (string) config('resmenu.marketing_consent_text_version', 'v1');
        $consent = (bool) ($data['marketing_consent'] ?? false);

        $minutes = (int) config('resmenu.crm_lead_dedup_minutes', 15);
        $leadId = null;
        $deduped = false;

        if (Schema::hasTable('crm_leads')) {
            $existing = DB::table('crm_leads')
                ->where('email', $email)
                ->where('source', $source)
                ->where('created_at', '>=', now()->subMinutes($minutes))
                ->orderByDesc('id')
                ->first();

            if ($existing) {
                $leadId = (int) $existing->id;
                $deduped = true;
                DB::table('crm_leads')->where('id', $leadId)->update([
                    'name' => $data['name'] ?? $existing->name,
                    'phone' => $phone ?? $existing->phone,
                    'message' => $data['message'] ?? $existing->message,
                    'marketing_consent' => $consent ? 1 : 0,
                    'marketing_consent_at' => $consent ? now() : $existing->marketing_consent_at,
                    'marketing_consent_source' => $consent ? $source : $existing->marketing_consent_source,
                    'marketing_consent_text_version' => $consent ? $version : $existing->marketing_consent_text_version,
                    'updated_at' => now(),
                ]);
            } else {
                $leadId = (int) DB::table('crm_leads')->insertGetId([
                    'email' => $email,
                    'name' => $data['name'] ?? null,
                    'phone' => $phone,
                    'message' => $data['message'] ?? null,
                    'source' => $source,
                    'marketing_consent' => $consent ? 1 : 0,
                    'marketing_consent_at' => $consent ? now() : null,
                    'marketing_consent_source' => $consent ? $source : null,
                    'marketing_consent_text_version' => $consent ? $version : null,
                    'manager_id' => $data['manager_id'] ?? null,
                    'sync_status' => 'pending',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $consentEventId = $this->consent->record([
            'email' => $email,
            'marketing_consent' => $consent,
            'source' => $source,
            'manager_id' => $data['manager_id'] ?? null,
            'crm_lead_id' => $leadId,
        ], $request);

        // Fresh consent event with checked box → may subscribe. Deduped re-submit with check also creates new event (explicit re-consent).
        $shouldSubscribe = $consent && $consentEventId !== null;

        $payload = ContactPayload::fromArray([
            'email' => $email,
            'name' => $data['name'] ?? null,
            'phone' => $phone,
            'message' => $data['message'] ?? null,
            'source' => $source,
            'marketing_consent' => $consent,
            'marketing_consent_at' => $consent ? now()->toIso8601String() : null,
            'marketing_consent_text_version' => $version,
            'manager_id' => $data['manager_id'] ?? null,
            'crm_lead_id' => $leadId,
            'consent_event_id' => $consentEventId,
            'should_subscribe' => $shouldSubscribe,
        ]);

        $sync = ['success' => false, 'skipped' => true, 'message' => 'CRM disabled'];
        try {
            $sync = $this->crm->syncContact($payload);
        } catch (\Throwable $e) {
            Log::warning('ContactLeadService CRM sync failed: '.$e->getMessage());
            $sync = ['success' => false, 'message' => $e->getMessage()];
        }

        if ($leadId && Schema::hasTable('crm_leads')) {
            DB::table('crm_leads')->where('id', $leadId)->update([
                'sync_status' => ($sync['success'] ?? false) ? 'synced' : (($sync['skipped'] ?? false) ? 'skipped' : 'failed'),
                'sync_error' => ($sync['success'] ?? false) ? null : ($sync['message'] ?? 'Unknown error'),
                'crm_external_id' => $sync['external_id'] ?? null,
                'synced_at' => ($sync['success'] ?? false) ? now() : null,
                'updated_at' => now(),
            ]);
        }

        return ['lead_id' => $leadId, 'sync' => $sync, 'deduped' => $deduped];
    }

    /**
     * Retry failed contact sync only — never creates a new consent event or re-subscribes.
     */
    public function retryLead(int $leadId): array
    {
        $lead = DB::table('crm_leads')->where('id', $leadId)->first();
        if (! $lead) {
            return ['success' => false, 'message' => 'Lead not found'];
        }

        $payload = ContactPayload::fromArray([
            'email' => $lead->email,
            'name' => $lead->name,
            'phone' => $lead->phone,
            'message' => $lead->message,
            'source' => $lead->source,
            'marketing_consent' => (bool) $lead->marketing_consent,
            'marketing_consent_at' => $lead->marketing_consent_at,
            'marketing_consent_text_version' => $lead->marketing_consent_text_version,
            'manager_id' => $lead->manager_id,
            'crm_lead_id' => $lead->id,
            'should_subscribe' => false,
        ]);

        $sync = $this->crm->syncContact($payload);

        DB::table('crm_leads')->where('id', $leadId)->update([
            'sync_status' => ($sync['success'] ?? false) ? 'synced' : (($sync['skipped'] ?? false) ? 'skipped' : 'failed'),
            'sync_error' => ($sync['success'] ?? false) ? null : ($sync['message'] ?? 'Unknown error'),
            'crm_external_id' => $sync['external_id'] ?? null,
            'synced_at' => ($sync['success'] ?? false) ? now() : null,
            'updated_at' => now(),
        ]);

        return $sync;
    }

    public function assertPublicRateLimits(string $email, string $ip): bool
    {
        $emailKey = 'crm_lead_email:'.strtolower($email);
        $ipKey = 'crm_lead_ip:'.$ip;
        $emailLimit = (int) config('resmenu.crm_lead_limit_per_email', 10);
        $ipLimit = (int) config('resmenu.crm_lead_limit_per_ip', 30);
        $window = (int) config('resmenu.crm_lead_limit_window_seconds', 3600);

        if (RateLimiter::tooManyAttempts($emailKey, $emailLimit) || RateLimiter::tooManyAttempts($ipKey, $ipLimit)) {
            return false;
        }

        RateLimiter::hit($emailKey, $window);
        RateLimiter::hit($ipKey, $window);

        return true;
    }
}
