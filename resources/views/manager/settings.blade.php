@extends('layouts.manager')

@section('title', 'Settings')

@push('head')
<link rel="stylesheet" href="{{ resmenu_public_asset('css/pages/manager-settings.css') }}">
@if($inline = resmenu_inline_page_css('manager-settings.css'))
<style>{!! $inline !!}</style>
@endif
@endpush

@section('content')
@php
    $activeTab = $activeTab ?? 'account';
    $logoUrl = $logoUrl ?? null;
    $heroUrl = $heroUrl ?? null;
@endphp

<div class="settings-page">
<div class="page-header">
    <h1 class="page-title">Settings</h1>
    <p class="page-subtitle">Manage your account, restaurant details, and password</p>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if($errors->any())
    <div class="alert alert-error">{{ $errors->first() }}</div>
@endif

<div class="tabs-container">
    <div class="tabs-nav">
        <button type="button" class="tab-button {{ $activeTab === 'account' ? 'active' : '' }}" data-tab="account" onclick="switchTab('account')">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            Account
        </button>
        <button type="button" class="tab-button {{ $activeTab === 'restaurant' ? 'active' : '' }}" data-tab="restaurant" onclick="switchTab('restaurant')">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            Restaurant Details
        </button>
        <button type="button" class="tab-button {{ $activeTab === 'password' ? 'active' : '' }}" data-tab="password" onclick="switchTab('password')">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
            Password
        </button>
    </div>

    <div id="tab-account" class="tab-content {{ $activeTab === 'account' ? 'active' : '' }}">
        <div class="settings-card">
            <div class="section-header">
                <h2 class="section-title">Account Information</h2>
            </div>
            <div class="info-row">
                <div>
                    <label class="form-label">Username</label>
                    <div class="info-value">{{ $manager->username }}</div>
                </div>
                <div>
                    <label class="form-label">Email</label>
                    <div class="info-value">{{ $manager->email }}</div>
                </div>
                <div>
                    <label class="form-label">Restaurant</label>
                    <div class="info-value">{{ $restaurant->name }}</div>
                </div>
                <div>
                    <label class="form-label">Account Created</label>
                    <div class="info-value">{{ $manager->created_at ? $manager->created_at->format('F j, Y g:i A') : 'N/A' }}</div>
                </div>
            </div>
            <form method="post" action="{{ route('manager.settings.update') }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="tab" value="account">
                <input type="hidden" name="action" value="update_profile">
                <div class="form-group">
                    <label class="form-label" for="username">Username *</label>
                    <input type="text" id="username" name="username" class="form-input" required value="{{ old('username', $manager->username) }}">
                </div>
                <div class="form-group">
                    <label class="form-label" for="manager_email">Email *</label>
                    <input type="email" id="manager_email" name="email" class="form-input" required value="{{ old('email', $manager->email) }}">
                </div>
                <button type="submit" class="btn">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Save Account
                </button>
            </form>
        </div>
    </div>

    <div id="tab-restaurant" class="tab-content {{ $activeTab === 'restaurant' ? 'active' : '' }}">
        <div class="settings-card">
            <div class="section-header">
                <h2 class="section-title">Restaurant Details</h2>
            </div>
            <p style="color: var(--muted); font-size: 0.875rem; margin-bottom: 20px;">Edit your restaurant information. Synced with admin—changes here appear in admin and vice versa. All data is saved to the same database.</p>
            <form method="post" action="{{ route('manager.settings.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="tab" value="restaurant">
                <input type="hidden" name="action" value="update_restaurant">
                <div class="form-group">
                    <label class="form-label" for="rest_name">Restaurant Name *</label>
                    <input type="text" id="rest_name" name="name" class="form-input" required value="{{ old('name', $restaurant->name) }}">
                </div>
                <div class="form-group">
                    <label class="form-label" for="rest_description">Description</label>
                    <textarea id="rest_description" name="description" class="form-input form-textarea" rows="3">{{ old('description', $restaurant->description) }}</textarea>
                </div>
                <div class="form-group-row">
                    <div class="form-group">
                        <label class="form-label" for="rest_phone">Phone</label>
                        <input type="text" id="rest_phone" name="phone" class="form-input" value="{{ old('phone', $restaurant->phone) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="rest_email">Email</label>
                        <input type="email" id="rest_email" name="email" class="form-input" value="{{ old('email', $restaurant->email) }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label" for="rest_address">Address</label>
                    <textarea id="rest_address" name="address" class="form-input form-textarea" rows="2">{{ old('address', $restaurant->address) }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label" for="rest_logo">Logo</label>
                    <input type="file" id="rest_logo" name="logo" class="form-input" accept="image/*">
                    @if($logoUrl)
                        <div style="margin-top: 10px;">
                            <p style="margin-bottom: 5px; color: var(--muted);">Current logo:</p>
                            <img src="{{ $logoUrl }}" alt="Current logo" style="max-width: 160px; max-height: 160px; border-radius: 8px; border: 2px solid #e5e7eb;">
                        </div>
                    @endif
                    <small style="color: var(--muted); display: block; margin-top: 5px;">Recommended: square or horizontal logo (PNG/JPEG), max ~1MB.</small>
                </div>
                <div class="form-group">
                    <label class="form-label" for="rest_hero_image">Cover / Hero Image</label>
                    <input type="file" id="rest_hero_image" name="hero_image" class="form-input" accept="image/*">
                    @if($heroUrl)
                        <div style="margin-top: 10px;">
                            <p style="margin-bottom: 5px; color: var(--muted);">Current cover image:</p>
                            <img src="{{ $heroUrl }}" alt="Current cover image" style="max-width: 220px; max-height: 180px; border-radius: 8px; border: 2px solid #e5e7eb;">
                        </div>
                    @endif
                    <small style="color: var(--muted); display: block; margin-top: 5px;">Large hero/cover image used on your menu and reservation page. Recommended wide image.</small>
                </div>
                <div class="form-group">
                    <label class="form-label">Social Media Links</label>
                    <p style="color: var(--muted); font-size: 0.8rem; margin-bottom: 12px;">Only links with values will appear as icons in the menu footer.</p>
                    <div class="form-group-row">
                        <div class="form-group">
                            <label class="form-label" for="rest_whatsapp" style="font-size: 0.8rem;">WhatsApp</label>
                            <input type="url" id="rest_whatsapp" name="whatsapp_link" class="form-input" value="{{ old('whatsapp_link', $restaurant->whatsapp_link) }}" placeholder="https://wa.me/...">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="rest_instagram" style="font-size: 0.8rem;">Instagram</label>
                            <input type="url" id="rest_instagram" name="instagram_url" class="form-input" value="{{ old('instagram_url', $restaurant->instagram_url) }}" placeholder="https://instagram.com/...">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="rest_facebook" style="font-size: 0.8rem;">Facebook</label>
                            <input type="url" id="rest_facebook" name="facebook_url" class="form-input" value="{{ old('facebook_url', $restaurant->facebook_url) }}" placeholder="https://facebook.com/...">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="rest_twitter" style="font-size: 0.8rem;">Twitter</label>
                            <input type="url" id="rest_twitter" name="twitter_url" class="form-input" value="{{ old('twitter_url', $restaurant->twitter_url) }}" placeholder="https://twitter.com/...">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label" for="rest_footer">Footer Content</label>
                    <textarea id="rest_footer" name="footer_content" class="form-input form-textarea" rows="3">{{ old('footer_content', $restaurant->footer_content) }}</textarea>
                    <small style="color: var(--muted); display: block; margin-top: 5px;">Optional text displayed in the footer of your menu.</small>
                </div>
                <button type="submit" class="btn">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Save Restaurant Details
                </button>
            </form>
        </div>
    </div>

    <div id="tab-password" class="tab-content {{ $activeTab === 'password' ? 'active' : '' }}">
        <div class="settings-card">
            <div class="section-header">
                <h2 class="section-title">Change Password</h2>
            </div>
            <form method="post" action="{{ route('manager.settings.update') }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="tab" value="password">
                <input type="hidden" name="action" value="update_password">
                <div class="form-group">
                    <label class="form-label" for="current_password">Current Password *</label>
                    <input type="password" id="current_password" name="current_password" class="form-input" required autocomplete="current-password">
                </div>
                <div class="form-group">
                    <label class="form-label" for="new_password">New Password *</label>
                    <input type="password" id="new_password" name="new_password" class="form-input" required minlength="8" autocomplete="new-password">
                    <small style="color: var(--muted); display: block; margin-top: 5px;">Password must be at least {{ config('resmenu.password_min_length', 8) }} characters</small>
                </div>
                <div class="form-group">
                    <label class="form-label" for="confirm_password">Confirm New Password *</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-input" required minlength="8" autocomplete="new-password">
                </div>
                <button type="submit" class="btn">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Change Password
                </button>
            </form>
        </div>
    </div>
</div>
</div>

<script>
function switchTab(tab) {
    document.querySelectorAll('.tab-button').forEach(function(b) {
        b.classList.remove('active');
        if (b.dataset.tab === tab) b.classList.add('active');
    });
    document.querySelectorAll('.tab-content').forEach(function(c) {
        c.classList.remove('active');
        if (c.id === 'tab-' + tab) c.classList.add('active');
    });
    history.replaceState(null, '', '?tab=' + tab);
}
</script>
@endsection
