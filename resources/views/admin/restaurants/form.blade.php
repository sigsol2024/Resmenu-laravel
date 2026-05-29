@extends('layouts.admin')
@section('title', $restaurant->exists ? 'Edit Restaurant' : 'Create Restaurant')
@push('head')
<link rel="stylesheet" href="{{ asset('legacy/css/pages/admin-restaurants.css') }}">
@endpush
@section('content')
@php
    $uploadUrl = rtrim(config('resmenu.upload_url'), '/');
@endphp

<div class="page-header">
    <h1 class="page-title">{{ $restaurant->exists ? 'Edit Restaurant' : 'Create New Restaurant' }}</h1>
    <p class="page-subtitle">{{ $restaurant->exists ? 'Update restaurant details and manager account' : 'Add a new restaurant to the platform' }}</p>
</div>

<div class="form-page-card">
    <form method="post" action="{{ $restaurant->exists ? route('admin.restaurants.update', $restaurant) : route('admin.restaurants.store') }}" enctype="multipart/form-data">
        @csrf
        @if($restaurant->exists)
            @method('PUT')
        @endif

        <div class="form-group">
            <label class="form-label" for="name">Restaurant Name *</label>
            <input type="text" id="name" name="name" class="form-input" required value="{{ old('name', $restaurant->name) }}">
        </div>

        <div class="form-group">
            <label class="form-label" for="slug">Slug * (URL-friendly name)</label>
            <input type="text" id="slug" name="slug" class="form-input" required value="{{ old('slug', $restaurant->slug) }}">
        </div>

        <div class="form-group">
            <label class="form-label" for="description">Description</label>
            <textarea id="description" name="description" class="form-textarea" rows="3">{{ old('description', $restaurant->description) }}</textarea>
        </div>

        <div class="form-group-row">
            <div class="form-group">
                <label class="form-label" for="phone">Phone</label>
                <input type="tel" id="phone" name="phone" class="form-input" value="{{ old('phone', $restaurant->phone) }}">
            </div>
            <div class="form-group">
                <label class="form-label" for="template_id">Template ID</label>
                <input type="number" id="template_id" name="template_id" class="form-input" min="1" value="{{ old('template_id', $restaurant->template_id ?? 4) }}">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="address">Address</label>
            <textarea id="address" name="address" class="form-textarea" rows="2">{{ old('address', $restaurant->address) }}</textarea>
        </div>

        <div class="form-group">
            <label class="form-label" for="logo">Logo</label>
            <input type="file" id="logo" name="logo" class="form-input" accept="image/*">
            @if($restaurant->logo)
                <div style="margin-top: 10px;">
                    <p style="margin-bottom: 5px; color: var(--muted);">Current logo:</p>
                    <img src="{{ $uploadUrl . '/logos/' . $restaurant->logo }}" alt="Current logo" style="max-width: 200px; max-height: 200px; border-radius: 8px; border: 2px solid #e5e7eb;">
                </div>
            @endif
        </div>

        <div class="form-group">
            <label class="form-label" for="hero_image">Hero Image</label>
            <input type="file" id="hero_image" name="hero_image" class="form-input" accept="image/*">
            @if($restaurant->hero_image)
                <div style="margin-top: 10px;">
                    <p style="margin-bottom: 5px; color: var(--muted);">Current hero image:</p>
                    <img src="{{ $uploadUrl . '/heroes/' . $restaurant->hero_image }}" alt="Current hero image" style="max-width: 300px; max-height: 200px; border-radius: 8px; border: 2px solid #e5e7eb;">
                </div>
            @endif
        </div>

        <div class="form-group">
            <div style="display: flex; align-items: center; gap: 10px;">
                <input type="checkbox" id="is_active" name="is_active" value="1" style="width: 20px; height: 20px;" @checked(old('is_active', $restaurant->is_active ?? true))>
                <label class="form-label" for="is_active" style="margin: 0;">Active</label>
            </div>
        </div>

        @if(!$restaurant->exists)
            <div class="form-group">
                <label class="form-label" for="plan_id">Subscription Plan</label>
                <select id="plan_id" name="plan_id" class="form-select">
                    <option value="">—</option>
                    @foreach($plans as $p)
                        <option value="{{ $p->id }}" @selected(old('plan_id') == $p->id)>{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>
        @endif

        <hr style="margin: 30px 0; border: none; border-top: 2px solid #e5e7eb;">
        <h3 style="margin-bottom: 20px; font-weight: 600;">Manager Account</h3>

        <div class="form-group-row">
            <div class="form-group">
                <label class="form-label" for="manager_username">Manager Username *</label>
                <input type="text" id="manager_username" name="manager_username" class="form-input" required value="{{ old('manager_username', $manager->username ?? '') }}">
            </div>
            <div class="form-group">
                <label class="form-label" for="manager_email">Manager Email *</label>
                <input type="email" id="manager_email" name="manager_email" class="form-input" required value="{{ old('manager_email', $manager->email ?? $restaurant->email) }}">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="manager_password">Manager Password{{ $restaurant->exists ? ' (leave blank to keep current)' : ' *' }}</label>
            <input type="password" id="manager_password" name="manager_password" class="form-input" {{ $restaurant->exists ? '' : 'required minlength=8' }} autocomplete="new-password">
        </div>

        <div class="form-page-actions">
            <button type="submit" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                {{ $restaurant->exists ? 'Update Restaurant' : 'Create Restaurant' }}
            </button>
            @if($restaurant->exists)
                <a href="{{ route('admin.restaurants.hub', $restaurant) }}" class="btn btn-secondary">Manage Restaurant</a>
            @endif
            <a href="{{ route('admin.restaurants.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
