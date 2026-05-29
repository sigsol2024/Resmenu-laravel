@extends('layouts.admin')
@section('title', 'Settings')
@section('content')
@include('partials.admin.page-header', ['title' => 'Site settings', 'subtitle' => 'Branding and contact information'])

<div class="card" style="max-width:720px;margin-bottom:24px;">
  <h2>Site branding</h2>
  <form method="post" action="{{ route('admin.settings.index') }}" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="action" value="update_site">
    <div class="filter-group"><label>Site name</label><input name="site_name" value="{{ old('site_name', $settings['site_name'] ?? 'Resmenu') }}" style="width:100%"></div>
    @if($siteLogoUrl)<p style="margin:8px 0"><img src="{{ $siteLogoUrl }}" alt="Logo" style="max-height:48px"></p>@endif
    <div class="filter-group" style="margin-top:12px"><label>Site logo</label><input type="file" name="site_logo" accept="image/*"></div>
    @if($faviconUrl)<p style="margin:8px 0"><img src="{{ $faviconUrl }}" alt="Favicon" style="max-height:32px"></p>@endif
    <div class="filter-group" style="margin-top:12px"><label>Favicon</label><input type="file" name="favicon" accept="image/*"></div>
    <button type="submit" class="btn-filter" style="margin-top:16px">Save branding</button>
  </form>
</div>

<div class="card" style="max-width:720px;margin-bottom:24px;">
  <h2>Contact settings</h2>
  <form method="post" action="{{ route('admin.settings.index') }}">
    @csrf
    <div class="form-grid">
      <div class="filter-group"><label>Sales email</label><input name="contact_sales_email" value="{{ old('contact_sales_email', $settings['contact_sales_email'] ?? '') }}" style="width:100%"></div>
      <div class="filter-group"><label>Sales phone</label><input name="contact_sales_phone" value="{{ old('contact_sales_phone', $settings['contact_sales_phone'] ?? '') }}" style="width:100%"></div>
      <div class="filter-group"><label>Support email</label><input name="contact_support_email" value="{{ old('contact_support_email', $settings['contact_support_email'] ?? '') }}" style="width:100%"></div>
      <div class="filter-group"><label>Support phone</label><input name="contact_support_phone" value="{{ old('contact_support_phone', $settings['contact_support_phone'] ?? '') }}" style="width:100%"></div>
      <div class="filter-group"><label>Partners email</label><input name="contact_partners_email" value="{{ old('contact_partners_email', $settings['contact_partners_email'] ?? '') }}" style="width:100%"></div>
      <div class="filter-group"><label>Form recipient</label><input name="contact_form_recipient" value="{{ old('contact_form_recipient', $settings['contact_form_recipient'] ?? '') }}" style="width:100%"></div>
      <div class="filter-group"><label>HQ title</label><input name="contact_hq_title" value="{{ old('contact_hq_title', $settings['contact_hq_title'] ?? '') }}" style="width:100%"></div>
      <div class="filter-group"><label>HQ address</label><textarea name="contact_hq_address" rows="2" style="width:100%">{{ old('contact_hq_address', $settings['contact_hq_address'] ?? '') }}</textarea></div>
      <div class="filter-group"><label>Map embed</label><textarea name="contact_map_embed" rows="3" style="width:100%">{{ old('contact_map_embed', $settings['contact_map_embed'] ?? '') }}</textarea></div>
      <div class="filter-group"><label>Facebook</label><input name="contact_social_facebook" value="{{ old('contact_social_facebook', $settings['contact_social_facebook'] ?? '') }}" style="width:100%"></div>
      <div class="filter-group"><label>Twitter</label><input name="contact_social_twitter" value="{{ old('contact_social_twitter', $settings['contact_social_twitter'] ?? '') }}" style="width:100%"></div>
      <div class="filter-group"><label>Instagram</label><input name="contact_social_instagram" value="{{ old('contact_social_instagram', $settings['contact_social_instagram'] ?? '') }}" style="width:100%"></div>
    </div>
    <button type="submit" class="btn-filter" style="margin-top:16px">Save contact</button>
  </form>
</div>

<div class="card" style="max-width:720px;margin-bottom:24px;">
  <h2>Admin accounts</h2>
  <table style="width:100%;margin-bottom:16px">
    <thead><tr><th>Username</th><th>Email</th><th></th></tr></thead>
    <tbody>
    @foreach($admins as $admin)
      <tr>
        <td>{{ $admin->username }}</td>
        <td>{{ $admin->email }}</td>
        <td>
          @if($admins->count() > 1 && auth('admin')->id() !== $admin->id)
          <form method="post" action="{{ route('admin.settings.index') }}" style="display:inline" onsubmit="return confirm('Remove this admin?')">
            @csrf
            <input type="hidden" name="action" value="delete_admin">
            <input type="hidden" name="admin_id" value="{{ $admin->id }}">
            <button type="submit" class="btn-filter" style="background:#fee2e2;color:#b91c1c">Delete</button>
          </form>
          @endif
        </td>
      </tr>
    @endforeach
    </tbody>
  </table>
  <h3 style="font-size:1rem;margin:16px 0 8px">Add admin</h3>
  <form method="post" action="{{ route('admin.settings.index') }}">
    @csrf
    <input type="hidden" name="action" value="create_admin">
    <div class="filter-group"><label>Username</label><input name="username" required style="width:100%"></div>
    <div class="filter-group" style="margin-top:8px"><label>Email</label><input type="email" name="email" required style="width:100%"></div>
    <div class="filter-group" style="margin-top:8px"><label>Password</label><input type="password" name="password" required minlength="8" style="width:100%"></div>
    <button type="submit" class="btn-filter" style="margin-top:12px">Create admin</button>
  </form>
</div>

<div class="card" style="max-width:720px;">
  <h2>Test email</h2>
  <form method="post" action="{{ route('admin.settings.index') }}">
    @csrf
    <input type="hidden" name="action" value="test_email">
    <div class="filter-group"><label>Email</label><input type="email" name="test_email" required style="width:100%"></div>
    <button type="submit" class="btn-filter" style="margin-top:12px">Send test</button>
  </form>
</div>
@endsection
@push('head')
<link rel="stylesheet" href="{{ asset('assets/css/admin-payments.css') }}">
<style>.form-grid{display:grid;gap:12px}</style>
@endpush
