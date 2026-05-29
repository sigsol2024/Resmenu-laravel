<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Services\MailService;
use App\Services\SiteSettingsService;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
  public function index(Request $request, SiteSettingsService $siteSettings, MailService $mail, UploadService $uploads)
  {
    if ($request->isMethod('post')) {
      $action = $request->input('action', 'update_site');

      if ($action === 'test_email') {
        $request->validate(['test_email' => 'required|email']);
        $mail->send($request->input('test_email'), '', 'Resmenu test', '<p>Test email OK</p>');

        return back()->with('success', 'Test email sent.');
      }

      if ($action === 'create_admin') {
        $data = $request->validate([
          'username' => 'required|string|max:100|unique:admins,username',
          'email' => 'required|email|max:255|unique:admins,email',
          'password' => 'required|string|min:8',
        ]);
        Admin::create([
          'username' => $data['username'],
          'email' => $data['email'],
          'password_hash' => Hash::make($data['password']),
        ]);

        return back()->with('success', 'Admin account created.');
      }

      if ($action === 'delete_admin') {
        $adminId = (int) $request->input('admin_id');
        if ($adminId === (int) $request->user('admin')?->id) {
          return back()->withErrors(['admin' => 'You cannot delete your own account.']);
        }
        if (Admin::count() <= 1) {
          return back()->withErrors(['admin' => 'At least one admin must remain.']);
        }
        Admin::where('id', $adminId)->delete();

        return back()->with('success', 'Admin removed.');
      }

      if ($action === 'update_site') {
        $data = $request->validate([
          'site_name' => 'nullable|string|max:255',
        ]);
        $current = (array) $siteSettings->row();
        if ($request->hasFile('site_logo')) {
          $data['site_logo'] = $uploads->storeSiteAsset($request->file('site_logo'), $current['site_logo'] ?? null);
        }
        if ($request->hasFile('favicon')) {
          $data['favicon'] = $uploads->storeSiteAsset($request->file('favicon'), $current['favicon'] ?? null);
        }
        $siteSettings->update($data);

        return back()->with('success', 'Site branding saved.');
      }

      $data = $request->validate([
        'site_name' => 'nullable|string|max:255',
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

      $siteSettings->update($data);

      return back()->with('success', 'Contact settings saved.');
    }

    return view('admin.settings.index', [
      'settings' => $siteSettings->all(),
      'siteLogoUrl' => $siteSettings->siteLogoUrl(),
      'faviconUrl' => $siteSettings->faviconUrl(),
      'admins' => Admin::orderBy('id')->get(),
    ]);
  }
}
