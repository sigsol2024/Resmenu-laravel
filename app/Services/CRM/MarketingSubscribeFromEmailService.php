<?php

namespace App\Services\CRM;

use App\Models\Manager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;

/**
 * Confirm-then-subscribe path from the welcome-email CTA.
 * Uses ContactLeadService → CRMService → HubSpotProvider (no credentials client-side).
 */
class MarketingSubscribeFromEmailService
{
    public function __construct(
        private ContactLeadService $leads,
    ) {}

    /**
     * @return array{status: string, message: string}
     *   status: already|success|failure
     */
    public function confirm(Manager $manager, ?Request $request = null): array
    {
        if ($manager->exists) {
            $manager->refresh();
        }

        if ((bool) $manager->marketing_consent) {
            return [
                'status' => 'already',
                'message' => "You're already subscribed to Resmenu updates.",
            ];
        }

        $email = strtolower((string) $manager->email);
        $emailKey = 'marketing_subscribe_email:'.$email;
        $ipKey = 'marketing_subscribe_ip:'.(string) ($request?->ip() ?? 'cli');
        $emailLimit = (int) config('resmenu.marketing_subscribe_limit_per_email', 10);
        $ipLimit = (int) config('resmenu.marketing_subscribe_limit_per_ip', 20);
        $window = (int) config('resmenu.marketing_subscribe_window_seconds', 3600);

        if (RateLimiter::tooManyAttempts($emailKey, $emailLimit) || RateLimiter::tooManyAttempts($ipKey, $ipLimit)) {
            return [
                'status' => 'failure',
                'message' => 'Too many subscription attempts. Please try again later.',
            ];
        }

        RateLimiter::hit($emailKey, $window);
        RateLimiter::hit($ipKey, $window);

        try {
            $result = $this->leads->capture([
                'email' => $manager->email,
                'name' => $manager->username,
                'phone' => $manager->phone,
                'source' => 'welcome_email_subscribe',
                'marketing_consent' => true,
                'manager_id' => $manager->id,
            ], $request);
        } catch (\Throwable $e) {
            Log::warning('Welcome-email marketing subscribe failed: '.$e->getMessage(), [
                'manager_id' => $manager->id,
            ]);

            return [
                'status' => 'failure',
                'message' => 'We could not complete your subscription right now. Please try again later.',
            ];
        }

        $sync = $result['sync'] ?? [];
        $contactOk = (bool) ($sync['success'] ?? false);
        $subscribed = ($sync['marketing_subscribed'] ?? null) === true;
        $leadId = isset($result['lead_id']) ? (int) $result['lead_id'] : null;

        if (! $contactOk || ! $subscribed) {
            $this->revertLocalConsent($manager, $leadId > 0 ? $leadId : null);

            $hint = 'We could not subscribe you in our email system right now. Please try again later.';
            if ($contactOk && ($sync['marketing_subscribed'] ?? null) === null) {
                $hint = 'Marketing subscription sync is not enabled for CRM. Please try again later or contact support.';
            }

            Log::warning('Welcome-email marketing subscribe HubSpot incomplete', [
                'manager_id' => $manager->id,
                'lead_id' => $leadId,
                'sync_success' => $contactOk,
                'marketing_subscribed' => $sync['marketing_subscribed'] ?? null,
                'message' => $sync['message'] ?? null,
            ]);

            return [
                'status' => 'failure',
                'message' => $hint,
            ];
        }

        return [
            'status' => 'success',
            'message' => "You're subscribed to Resmenu updates. Thanks!",
        ];
    }

    private function revertLocalConsent(Manager $manager, ?int $leadId = null): void
    {
        try {
            if ($manager->exists) {
                DB::table('managers')->where('id', $manager->id)->update([
                    'marketing_consent' => 0,
                    'marketing_consent_at' => null,
                    'marketing_consent_source' => null,
                    'marketing_consent_text_version' => null,
                    'updated_at' => now(),
                ]);
            }

            if ($leadId && Schema::hasTable('crm_leads')) {
                DB::table('crm_leads')->where('id', $leadId)->update([
                    'marketing_consent' => 0,
                    'marketing_consent_at' => null,
                    'marketing_consent_source' => null,
                    'marketing_consent_text_version' => null,
                    'sync_status' => 'failed',
                    'sync_error' => 'Marketing subscribe incomplete after welcome-email confirm',
                    'updated_at' => now(),
                ]);
            }
        } catch (\Throwable $e) {
            Log::warning('Welcome-email marketing subscribe consent revert failed: '.$e->getMessage(), [
                'manager_id' => $manager->id,
                'lead_id' => $leadId,
            ]);
        }
    }
}
