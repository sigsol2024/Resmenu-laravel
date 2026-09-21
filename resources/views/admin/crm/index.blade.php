@extends('layouts.admin')

@section('title', 'CRM Integrations')

@section('content')
<div class="page-header">
    <h1 class="page-title">CRM Integrations</h1>
    <p class="page-subtitle">Provider-based CRM (HubSpot first). Super Admin only.</p>
</div>

@include('partials.admin.flash-messages')

<div class="info-box" style="margin-bottom:1.5rem;">
    <div class="info-box-title">HubSpot account setup (ops)</div>
    <div class="info-box-content">
        <ul>
            <li>Create a Private App with contacts + communication preferences scopes; paste the token below (encrypted at rest).</li>
            <li>Copy Portal ID for website tracking.</li>
            <li>Create a marketing subscription type; paste its numeric ID.</li>
            <li>Create a Live Chat chatflow targeted to <code>resmenu.net</code> and turn it ON — the widget will not appear without this.</li>
            <li>Marketing campaigns are sent from HubSpot UI, not Resmenu.</li>
            <li>Full checklist: <code>docs/hubspot-crm-ops-checklist.md</code> in the Laravel repo.</li>
        </ul>
    </div>
</div>

<form method="post" action="{{ route('admin.crm.update') }}" class="form-card" style="max-width:720px;">
    @csrf
    <div class="form-group">
        <label><input type="checkbox" name="enabled" value="1" @checked(old('enabled', $settings->enabled ?? false))> Enable CRM</label>
    </div>
    <div class="form-group">
        <label for="provider">Provider</label>
        <select id="provider" name="provider" class="form-control">
            <option value="hubspot" @selected(old('provider', $settings->provider ?? 'hubspot') === 'hubspot')>HubSpot</option>
            <option value="mailchimp" @selected(old('provider', $settings->provider ?? '') === 'mailchimp')>Mailchimp (stub)</option>
        </select>
    </div>
    <div class="form-group">
        <label><input type="checkbox" name="sync_contacts" value="1" @checked(old('sync_contacts', $settings->sync_contacts ?? true))> Sync contacts</label>
    </div>
    <div class="form-group">
        <label><input type="checkbox" name="sync_marketing_consent" value="1" @checked(old('sync_marketing_consent', $settings->sync_marketing_consent ?? true))> Sync marketing consent</label>
    </div>
    <div class="form-group">
        <label><input type="checkbox" name="website_tracking" value="1" @checked(old('website_tracking', $settings->website_tracking ?? false))> Website tracking (resmenu.net)</label>
    </div>
    <div class="form-group">
        <label><input type="checkbox" name="live_chat" value="1" @checked(old('live_chat', $settings->live_chat ?? false))> Live chat (requires tracking + HubSpot chatflow ON)</label>
    </div>

    <h3 style="margin:1.5rem 0 0.75rem;">HubSpot</h3>
    <div class="form-group">
        <label for="hubspot_portal_id">Portal ID (Hub ID)</label>
        <input class="form-control" type="text" id="hubspot_portal_id" name="hubspot_portal_id" value="{{ old('hubspot_portal_id', $settings->hubspot_portal_id ?? '') }}">
    </div>
    <div class="form-group">
        <label for="hubspot_private_app_token">Private App token {{ $hasToken ? '(leave blank to keep current)' : '' }}</label>
        <input class="form-control" type="password" id="hubspot_private_app_token" name="hubspot_private_app_token" autocomplete="new-password" placeholder="{{ $hasToken ? '••••••••' : '' }}">
    </div>
    <div class="form-group">
        <label for="hubspot_subscription_type_id">Marketing subscription type ID</label>
        <input class="form-control" type="text" id="hubspot_subscription_type_id" name="hubspot_subscription_type_id" value="{{ old('hubspot_subscription_type_id', $settings->hubspot_subscription_type_id ?? '') }}">
    </div>

    <div style="display:flex;gap:0.75rem;flex-wrap:wrap;margin-top:1.25rem;">
        <button type="submit" class="btn btn-primary">Save settings</button>
    </div>
</form>

<form method="post" action="{{ route('admin.crm.test') }}" style="margin-top:1rem;">
    @csrf
    <button type="submit" class="btn btn-secondary">Test connection</button>
</form>

<p style="margin-top:1.5rem;"><a href="{{ route('admin.crm.leads') }}">View leads &amp; sync monitor →</a></p>
@endsection
