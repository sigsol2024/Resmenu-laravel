<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CRM\CRMService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CrmSettingsController extends Controller
{
    public function index(CRMService $crm)
    {
        $settings = $crm->settings();

        return view('admin.crm.index', [
            'settings' => $settings,
            'hasToken' => $settings && ! empty($settings->hubspot_private_app_token_encrypted),
        ]);
    }

    public function update(Request $request)
    {
        if (! Schema::hasTable('crm_settings')) {
            return back()->with('error', 'CRM settings table is missing. Run migrations.');
        }

        $data = $request->validate([
            'enabled' => 'nullable|boolean',
            'provider' => 'required|in:hubspot,mailchimp',
            'sync_contacts' => 'nullable|boolean',
            'sync_marketing_consent' => 'nullable|boolean',
            'website_tracking' => 'nullable|boolean',
            'live_chat' => 'nullable|boolean',
            'hubspot_portal_id' => 'nullable|string|max:64',
            'hubspot_private_app_token' => 'nullable|string|max:500',
            'hubspot_subscription_type_id' => 'nullable|string|max:64',
        ]);

        $liveChat = $request->boolean('live_chat');
        $tracking = $request->boolean('website_tracking');
        if ($liveChat && ! $tracking) {
            $tracking = true; // chat requires tracking embed per HubSpot docs
        }

        $payload = [
            'enabled' => $request->boolean('enabled') ? 1 : 0,
            'provider' => $data['provider'],
            'sync_contacts' => $request->boolean('sync_contacts') ? 1 : 0,
            'sync_marketing_consent' => $request->boolean('sync_marketing_consent') ? 1 : 0,
            'website_tracking' => $tracking ? 1 : 0,
            'live_chat' => $liveChat ? 1 : 0,
            'hubspot_portal_id' => $data['hubspot_portal_id'] ?? null,
            'hubspot_subscription_type_id' => $data['hubspot_subscription_type_id'] ?? null,
            'updated_at' => now(),
        ];

        if (! empty($data['hubspot_private_app_token'])) {
            $payload['hubspot_private_app_token_encrypted'] = \App\Support\CrmEncryption::encrypt($data['hubspot_private_app_token']);
        }

        if (DB::table('crm_settings')->where('id', 1)->exists()) {
            DB::table('crm_settings')->where('id', 1)->update($payload);
        } else {
            $payload['id'] = 1;
            $payload['created_at'] = now();
            DB::table('crm_settings')->insert($payload);
        }

        return back()->with('success', 'CRM settings saved.');
    }

    public function test(CRMService $crm)
    {
        $result = $crm->testConnection();

        return back()->with(
            ($result['success'] ?? false) ? 'success' : 'error',
            $result['message'] ?? 'Unknown result'
        );
    }
}
