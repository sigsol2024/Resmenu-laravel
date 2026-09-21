@extends('layouts.admin')

@section('title', 'CRM Integrations')

@push('head')
<link rel="stylesheet" href="{{ asset('legacy/css/pages/admin-crm.css') }}">
@endpush

@section('content')
@php
    $enabled = (bool) old('enabled', $settings->enabled ?? false);
@endphp

<div class="page-header">
    <h1 class="page-title">CRM Integrations</h1>
    <p class="page-subtitle">Connect HubSpot for contacts, consent, tracking, and live chat. Super Admin only.</p>
</div>

@include('partials.admin.flash-messages')

<div class="info-box">
    <div class="info-box-title">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        HubSpot setup checklist
    </div>
    <div class="info-box-content">
        <ul>
            <li>Create a Private App (contacts + communication preferences); paste the token below.</li>
            <li>Copy Portal ID for website tracking on resmenu.net.</li>
            <li>Create a marketing subscription type and paste its numeric ID.</li>
            <li>Turn on a Live Chat chatflow targeted to resmenu.net (required for the widget).</li>
            <li>Send newsletters from HubSpot — Resmenu only syncs contacts and consent.</li>
        </ul>
    </div>
</div>

<form method="post" action="{{ route('admin.crm.update') }}">
    @csrf

    <div class="settings-card">
        <div class="section-header">
            <div class="section-title">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                CRM connection
            </div>
            <span class="status-badge {{ $enabled ? 'active' : 'inactive' }}">
                <span class="status-dot"></span>
                {{ $enabled ? 'Enabled' : 'Disabled' }}
            </span>
        </div>

        <div class="toggle-row">
            <div class="toggle-info">
                <div class="toggle-label">Enable CRM</div>
                <div class="toggle-description">Sync registration and website leads to the selected provider</div>
            </div>
            <label class="toggle-switch">
                <input type="checkbox" name="enabled" value="1" @checked(old('enabled', $settings->enabled ?? false))>
                <span class="toggle-slider"></span>
            </label>
        </div>

        <div class="form-group" style="margin-top:16px;">
            <label for="provider">Provider</label>
            <select id="provider" name="provider">
                <option value="hubspot" @selected(old('provider', $settings->provider ?? 'hubspot') === 'hubspot')>HubSpot</option>
                <option value="mailchimp" @selected(old('provider', $settings->provider ?? '') === 'mailchimp')>Mailchimp (coming soon)</option>
            </select>
        </div>

        <div class="toggle-row">
            <div class="toggle-info">
                <div class="toggle-label">Sync contacts</div>
                <div class="toggle-description">Create or update CRM contacts from leads</div>
            </div>
            <label class="toggle-switch">
                <input type="checkbox" name="sync_contacts" value="1" @checked(old('sync_contacts', $settings->sync_contacts ?? true))>
                <span class="toggle-slider"></span>
            </label>
        </div>

        <div class="toggle-row">
            <div class="toggle-info">
                <div class="toggle-label">Sync marketing consent</div>
                <div class="toggle-description">Subscribe only when the user newly opts in</div>
            </div>
            <label class="toggle-switch">
                <input type="checkbox" name="sync_marketing_consent" value="1" @checked(old('sync_marketing_consent', $settings->sync_marketing_consent ?? true))>
                <span class="toggle-slider"></span>
            </label>
        </div>

        <div class="toggle-row">
            <div class="toggle-info">
                <div class="toggle-label">Website tracking</div>
                <div class="toggle-description">Load HubSpot tracking on resmenu.net</div>
            </div>
            <label class="toggle-switch">
                <input type="checkbox" name="website_tracking" value="1" @checked(old('website_tracking', $settings->website_tracking ?? false))>
                <span class="toggle-slider"></span>
            </label>
        </div>

        <div class="toggle-row">
            <div class="toggle-info">
                <div class="toggle-label">Live chat</div>
                <div class="toggle-description">Requires tracking + an ON chatflow in HubSpot</div>
            </div>
            <label class="toggle-switch">
                <input type="checkbox" name="live_chat" value="1" @checked(old('live_chat', $settings->live_chat ?? false))>
                <span class="toggle-slider"></span>
            </label>
        </div>
    </div>

    <div class="settings-card">
        <div class="section-subtitle"><span>HubSpot credentials</span></div>

        <div class="form-group">
            <label for="hubspot_portal_id">Portal ID (Hub ID)</label>
            <input type="text" id="hubspot_portal_id" name="hubspot_portal_id" value="{{ old('hubspot_portal_id', $settings->hubspot_portal_id ?? '') }}" placeholder="e.g. 12345678" autocomplete="off">
        </div>

        <div class="form-group">
            <label for="hubspot_private_app_token">Private App token {{ !empty($hasToken) ? '(leave blank to keep current)' : '' }}</label>
            <input type="password" id="hubspot_private_app_token" name="hubspot_private_app_token" autocomplete="new-password" placeholder="{{ !empty($hasToken) ? '••••••••••••••••' : 'pat-na1-…' }}">
            <div class="form-hint">Encrypted at rest. Never share this token.</div>
        </div>

        <div class="form-group">
            <label for="hubspot_subscription_type_id">Marketing subscription type ID</label>
            <input type="text" id="hubspot_subscription_type_id" name="hubspot_subscription_type_id" value="{{ old('hubspot_subscription_type_id', $settings->hubspot_subscription_type_id ?? '') }}" placeholder="Numeric ID from HubSpot" autocomplete="off">
        </div>
    </div>

    <div class="save-section">
        <button type="submit" class="btn-save">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            Save CRM settings
        </button>
    </div>
</form>

<form method="post" action="{{ route('admin.crm.test') }}" class="save-section">
    @csrf
    <button type="submit" class="btn-secondary-action">Test connection</button>
</form>

<div class="crm-link-row">
    <a href="{{ route('admin.crm.leads') }}">View leads &amp; sync monitor →</a>
</div>
@endsection
