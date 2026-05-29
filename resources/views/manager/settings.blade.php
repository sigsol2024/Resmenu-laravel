@extends('layouts.manager')
@section('title', 'Settings')
@push('head')
<link rel="stylesheet" href="{{ asset('legacy/css/pages/manager-settings.css') }}">
@endpush
@section('content')
<div class="page-header">
    <h1 class="page-title">Settings</h1>
    <p class="page-subtitle">Manage your account, restaurant details, and password</p>
</div>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@if($errors->any())<div class="alert alert-error">{{ $errors->first() }}</div>@endif

<div class="settings-card" style="margin-bottom:24px;">
    <h2 class="section-title">Account</h2>
    <form method="post" action="{{ route('manager.settings.update') }}">
        @csrf
        @method('PUT')
        <input type="hidden" name="action" value="update_profile">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" class="form-input" value="{{ old('username', $manager->username) }}" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-input" value="{{ old('email', $manager->email) }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Save account</button>
    </form>
</div>

<div class="settings-card" style="margin-bottom:24px;">
    <h2 class="section-title">Password</h2>
    <form method="post" action="{{ route('manager.settings.update') }}">
        @csrf
        @method('PUT')
        <input type="hidden" name="action" value="update_password">
        <div class="form-group">
            <label for="password">New password</label>
            <input type="password" name="password" id="password" class="form-input" autocomplete="new-password" required>
        </div>
        <div class="form-group">
            <label for="password_confirmation">Confirm password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-input" required>
        </div>
        <button type="submit" class="btn btn-primary">Update password</button>
    </form>
</div>

<div class="settings-card">
    <h2 class="section-title">Restaurant profile</h2>
    <p class="text-muted" style="font-size:0.875rem;margin-bottom:16px;">Synced with admin — changes appear on your public menu.</p>
    <form method="post" action="{{ route('manager.settings.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="action" value="update_restaurant">
        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" class="form-input" value="{{ old('name', $restaurant->name) }}" required>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-input" rows="3">{{ old('description', $restaurant->description) }}</textarea>
        </div>
        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" class="form-input" value="{{ old('phone', $restaurant->phone) }}">
        </div>
        <div class="form-group">
            <label>Public email</label>
            <input type="email" name="email" class="form-input" value="{{ old('email', $restaurant->email) }}">
        </div>
        <div class="form-group">
            <label>Address</label>
            <textarea name="address" class="form-input" rows="2">{{ old('address', $restaurant->address) }}</textarea>
        </div>
        <div class="form-group">
            <label>WhatsApp link</label>
            <input type="text" name="whatsapp_link" class="form-input" value="{{ old('whatsapp_link', $restaurant->whatsapp_link) }}">
        </div>
        <div class="form-group">
            <label>Instagram URL</label>
            <input type="url" name="instagram_url" class="form-input" value="{{ old('instagram_url', $restaurant->instagram_url) }}">
        </div>
        <div class="form-group">
            <label>Facebook URL</label>
            <input type="url" name="facebook_url" class="form-input" value="{{ old('facebook_url', $restaurant->facebook_url) }}">
        </div>
        <div class="form-group">
            <label>Twitter / X URL</label>
            <input type="url" name="twitter_url" class="form-input" value="{{ old('twitter_url', $restaurant->twitter_url) }}">
        </div>
        <div class="form-group">
            <label>Footer content</label>
            <textarea name="footer_content" class="form-input" rows="2">{{ old('footer_content', $restaurant->footer_content) }}</textarea>
        </div>
        <div class="form-group">
            <label>Logo</label>
            <input type="file" name="logo" accept="image/*">
        </div>
        <div class="form-group">
            <label>Hero image</label>
            <input type="file" name="hero_image" accept="image/*">
        </div>
        <button type="submit" class="btn btn-primary">Save restaurant</button>
    </form>
</div>
@endsection
