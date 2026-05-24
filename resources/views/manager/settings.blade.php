@extends('layouts.manager')
@section('title', 'Settings')
@section('content')
<h1 style="margin-bottom:20px;">Account settings</h1>
<div class="card">
<form method="post" action="{{ route('manager.settings.update') }}">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" value="{{ old('username', $manager->username) }}" required>
    </div>
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" value="{{ old('email', $manager->email) }}" required>
    </div>
    <div class="form-group">
        <label for="password">New password (leave blank to keep)</label>
        <input type="password" name="password" id="password" autocomplete="new-password">
    </div>
    <p style="font-size:0.875rem;color:#6b7280;">Restaurant: <strong>{{ $restaurant->name }}</strong> ({{ $restaurant->slug }})</p>
    <button type="submit" class="btn btn-primary" style="margin-top:16px;">Save</button>
</form>
</div>
@endsection
