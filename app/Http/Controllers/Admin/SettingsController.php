<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Services\MailService;
use App\Services\SiteSettingsService;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SettingsController extends Controller
{
  public function index(Request $request, SiteSettingsService $siteSettings, MailService $mail, UploadService $uploads)
  {
    if ($request->isMethod('post')) {
      return $this->handlePost($request, $siteSettings, $mail, $uploads);
    }

    $admins = Admin::orderBy('id')->get();
    $currentAdmin = $request->user('admin');

    return view('admin.settings.index', [
      'settings' => $siteSettings->all(),
      'siteLogoUrl' => $siteSettings->siteLogoUrl(),
      'faviconUrl' => $siteSettings->faviconUrl(),
      'admins' => $admins,
      'currentAdmin' => $currentAdmin,
      'primaryAdminId' => $admins->first()?->id,
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

    if ($action === 'add_admin') {
      $data = $request->validate([
        'new_admin_username' => 'required|string|max:100|unique:admins,username',
        'new_admin_email' => 'required|email|max:255|unique:admins,email',
        'new_admin_password' => ['required', 'string', Password::min(8)->letters()->numbers()],
      ]);
      Admin::create([
        'username' => $data['new_admin_username'],
        'email' => $data['new_admin_email'],
        'password_hash' => Hash::make($data['new_admin_password']),
      ]);

      return back()->with('success', 'Administrator account created.');
    }

    if ($action === 'update_admin') {
      $data = $request->validate([
        'target_admin_id' => 'required|integer|exists:admins,id',
        'target_username' => 'required|string|max:100',
        'target_email' => 'required|email|max:255',
        'target_new_password' => ['nullable', 'string', Password::min(8)->letters()->numbers()],
      ]);

      $target = Admin::findOrFail((int) $data['target_admin_id']);
      if (Admin::where('username', $data['target_username'])->where('id', '!=', $target->id)->exists()) {
        return back()->withErrors(['admin' => 'Username is already in use.']);
      }
      if (Admin::where('email', $data['target_email'])->where('id', '!=', $target->id)->exists()) {
        return back()->withErrors(['admin' => 'Email is already in use.']);
      }

      $target->username = $data['target_username'];
      $target->email = $data['target_email'];
      if (! empty($data['target_new_password'])) {
        $target->password_hash = Hash::make($data['target_new_password']);
      }
      $target->save();

      return back()->with('success', 'Administrator updated.');
    }

    if ($action === 'delete_admin') {
      $targetId = (int) $request->input('target_admin_id');
      $currentId = (int) $request->user('admin')?->id;
      $primaryId = (int) Admin::orderBy('id')->value('id');

      if ($targetId === $currentId) {
        return back()->withErrors(['admin' => 'You cannot delete your own account.']);
      }
      if ($targetId === $primaryId) {
        return back()->withErrors(['admin' => 'The primary administrator account cannot be deleted.']);
      }
      if (Admin::count() <= 1) {
        return back()->withErrors(['admin' => 'At least one administrator must remain.']);
      }

      Admin::where('id', $targetId)->delete();

      return back()->with('success', 'Administrator deleted.');
    }

    if ($action === 'update_profile') {
      $admin = $request->user('admin');
      $data = $request->validate([
        'username' => 'required|string|max:100',
        'email' => 'required|email|max:255',
      ]);

      if (Admin::where('username', $data['username'])->where('id', '!=', $admin->id)->exists()) {
        return back()->withErrors(['profile' => 'Username is already taken.']);
      }
      if (Admin::where('email', $data['email'])->where('id', '!=', $admin->id)->exists()) {
        return back()->withErrors(['profile' => 'Email is already taken.']);
      }

      $admin->update($data);

      return back()->with('success', 'Profile updated.');
    }

    if ($action === 'update_password') {
      $admin = $request->user('admin');
      $request->validate([
        'current_password' => 'required|string',
        'new_password' => ['required', 'string', 'confirmed', Password::min(8)->letters()->numbers()],
      ]);

      if (! Hash::check($request->input('current_password'), $admin->password_hash)) {
        return back()->withErrors(['password' => 'Current password is incorrect.']);
      }

      $admin->update(['password_hash' => Hash::make($request->input('new_password'))]);

      return back()->with('success', 'Password updated.');
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
