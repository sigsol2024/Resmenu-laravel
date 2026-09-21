@extends('layouts.admin')

@section('title', 'Profile')

@section('content')
<div class="page-header">
    <h1 class="page-title">Profile</h1>
    <p class="page-subtitle">Update your email and password</p>
</div>

<div class="card" style="margin-bottom:16px;">
    <div class="card-header">
        <h2 class="card-title">Account Information</h2>
    </div>
    <div class="card-body">
        <div class="form-group">
            <label class="form-label">Username</label>
            <div class="info-display">{{ $admin->username }}</div>
            <small style="color:#6b7280;display:block;margin-top:4px;font-size:0.75rem;">Usernames cannot be changed after creation.</small>
        </div>
        <div class="form-group">
            <label class="form-label">Role</label>
            <div class="info-display">{{ $admin->isSuperAdmin() ? 'Super Admin' : 'Administrator' }}</div>
        </div>
        <div class="form-group">
            <label class="form-label">Account Created</label>
            <div class="info-display">{{ $admin->created_at ? $admin->created_at->format('F j, Y g:i A') : 'N/A' }}</div>
        </div>
    </div>
</div>

<div class="card" style="margin-bottom:16px;">
    <div class="card-header">
        <h2 class="card-title">Update Email</h2>
    </div>
    <div class="card-body">
        <form method="post" action="{{ route('admin.profile.update') }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="action" value="update_profile">
            <div class="form-group">
                <label class="form-label" for="email">Email *</label>
                <input type="email" id="email" name="email" class="form-input" required value="{{ old('email', $admin->email) }}">
            </div>
            <button type="submit" class="btn btn-primary">Update Email</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Change Password</h2>
    </div>
    <div class="card-body">
        <form method="post" action="{{ route('admin.profile.update') }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="action" value="update_password">
            <div class="form-group">
                <label class="form-label" for="current_password">Current Password *</label>
                <input type="password" id="current_password" name="current_password" class="form-input" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="new_password">New Password *</label>
                <input type="password" id="new_password" name="new_password" class="form-input" required minlength="8">
                <small style="color: #6b7280; display: block; margin-top: 5px; font-size: 0.75rem;">At least 8 characters with a letter and a number</small>
            </div>
            <div class="form-group">
                <label class="form-label" for="new_password_confirmation">Confirm New Password *</label>
                <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="form-input" required minlength="8">
            </div>
            <button type="submit" class="btn btn-primary">Change Password</button>
        </form>
    </div>
</div>
@endsection

@push('head')
<link rel="stylesheet" href="{{ asset('legacy/css/pages/admin-settings.css') }}">
@endpush
