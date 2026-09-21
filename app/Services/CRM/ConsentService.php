<?php

namespace App\Services\CRM;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Local consent evidence only — never calls HubSpot/Mailchimp.
 */
class ConsentService
{
    public const SOURCES = ['registration', 'homepage_popup', 'newsletter', 'contact_form'];

    public const PUBLIC_SOURCES = ['homepage_popup', 'newsletter', 'contact_form'];

    /**
     * @param  array<string, mixed>  $data
     * @return int|null consent event id
     */
    public function record(array $data, ?Request $request = null): ?int
    {
        $email = strtolower(trim((string) ($data['email'] ?? '')));
        if ($email === '' || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return null;
        }

        $consent = (bool) ($data['marketing_consent'] ?? false);
        $source = (string) ($data['source'] ?? 'registration');
        if (! in_array($source, self::SOURCES, true)) {
            $source = 'registration';
        }
        // Always server-owned — never trust browser text version.
        $version = (string) config('resmenu.marketing_consent_text_version', 'v1');

        $id = (int) DB::table('crm_consent_events')->insertGetId([
            'email' => $email,
            'marketing_consent' => $consent ? 1 : 0,
            'source' => $source,
            'text_version' => $version,
            'manager_id' => $data['manager_id'] ?? null,
            'crm_lead_id' => $data['crm_lead_id'] ?? null,
            'ip_address' => $request?->ip(),
            'user_agent' => substr((string) ($request?->userAgent() ?? ''), 0, 500),
            'created_at' => now(),
        ]);

        if (! empty($data['manager_id'])) {
            DB::table('managers')->where('id', (int) $data['manager_id'])->update([
                'marketing_consent' => $consent ? 1 : 0,
                'marketing_consent_at' => $consent ? now() : null,
                'marketing_consent_source' => $consent ? $source : null,
                'marketing_consent_text_version' => $consent ? $version : null,
                'updated_at' => now(),
            ]);
        }

        return $id > 0 ? $id : null;
    }
}
