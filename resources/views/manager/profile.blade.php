@extends('layouts.manager')

@section('title', 'Profile')

@push('head')
<link rel="stylesheet" href="{{ asset('legacy/css/pages/manager-settings.css') }}">
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">Profile</h1>
    <p class="page-subtitle">Update your account username, email, and password</p>
</div>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
<div class="settings-card">
<form method="post" action="{{ route('manager.profile.update') }}">
    @csrf @method('PUT')
    <div class="form-group">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" class="form-input" value="{{ old('username', $manager->username) }}" required>
    </div>
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" class="form-input" value="{{ old('email', $manager->email) }}" required>
    </div>
    <div class="form-group">
        <label for="password">New password (optional)</label>
        <input type="password" name="password" id="password" class="form-input" autocomplete="new-password">
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
</form>
</div>
@endsection
