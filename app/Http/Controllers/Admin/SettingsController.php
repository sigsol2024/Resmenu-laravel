<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\MailService;
use App\Services\SiteSettingsService;
use App\Services\UploadService;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index(Request $request, SiteSettingsService $siteSettings, MailService $mail, UploadService $uploads)
    {
        if ($request->isMethod('post')) {
            return $this->handlePost($request, $siteSettings, $mail, $uploads);
        }

        return view('admin.settings.index', [
            'settings' => $siteSettings->all(),
            'siteLogoUrl' => $siteSettings->siteLogoUrl(),
            'faviconUrl' => $siteSettings->faviconUrl(),
        ]);
    }

    private function handlePost(Request $request, SiteSettingsService $siteSettings, MailService $mail, UploadService $uploads)
    {
        $action = $request->input('action', 'update_site');

        if ($action === 'test_email') {
            $request->validate(['test_email' => 'required|email']);
            $siteName = $siteSettings->get('site_name', 'Resmenu');
            $body = '<h2 style="margin:0 0 16px;font-size:22px;font-weight:700;color:#111827;">Email Configuration Test</h2>'
                .'<p style="margin:0 0 12px;">Hello,</p>'
                .'<p style="margin:0 0 16px;">This is a test email from <strong>'.e($siteName).'</strong>.</p>'
                .'<p style="margin:0 0 16px;">If you received this, your mail configuration is working correctly.</p>'
                .'<p style="margin:0;padding:16px;background:#f9fafb;border-radius:8px;font-size:13px;color:#6b7280;">Sent at: '.now()->format('F j, Y g:i A').'</p>';
            $mail->send($request->input('test_email'), '', 'Test Email - '.$siteName, $body);

            return back()->with('success', 'Test email sent to '.$request->input('test_email'));
        }

        if ($action === 'update_site') {
            $data = $request->validate(['site_name' => 'nullable|string|max:255']);
            $current = (array) $siteSettings->row();
            if ($request->hasFile('site_logo')) {
                $data['site_logo'] = $uploads->storeSiteAsset($request->file('site_logo'), $current['site_logo'] ?? null);
            }
            if ($request->hasFile('favicon')) {
                $data['favicon'] = $uploads->storeSiteAsset($request->file('favicon'), $current['favicon'] ?? null);
            }
            $siteSettings->update(array_merge($current, $data));

            return back()->with('success', 'Site settings updated.');
        }

        if ($action === 'update_contact') {
            $data = $request->validate([
                'contact_sales_email' => 'nullable|email|max:255',
                'contact_sales_phone' => 'nullable|string|max:50',
                'contact_support_email' => 'nullable|email|max:255',
                'contact_support_phone' => 'nullable|string|max:50',
                'contact_partners_email' => 'nullable|email|max:255',
                'contact_form_recipient' => 'nullable|email|max:255',
                'contact_hq_title' => 'nullable|string|max:255',
                'contact_hq_address' => 'nullable|string',
                'contact_map_embed' => 'nullable|string',
                'contact_social_facebook' => 'nullable|string|max:255',
                'contact_social_twitter' => 'nullable|string|max:255',
                'contact_social_instagram' => 'nullable|string|max:255',
            ]);

            $siteSettings->update(array_merge((array) $siteSettings->row(), $data));

            return back()->with('success', 'Contact settings updated.');
        }

        return back();
    }
}
