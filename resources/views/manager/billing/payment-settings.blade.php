@extends('layouts.manager')

@section('title', 'Payment Settings')

@push('head')
<link rel="stylesheet" href="{{ resmenu_public_asset('css/pages/manager-payment-settings.css') }}">
@if($inline = resmenu_inline_page_css('manager-payment-settings.css'))
<style>{!! $inline !!}</style>
@endif
@endpush

@section('content')
@php
    $ps = $paystackSettings ?? [];
    $fw = $flutterwaveSettings ?? [];
    $bt = $bankTransferSettings ?? [];
@endphp

<div class="resmenu-manager-content-wrap {{ !empty($showUpgradeOverlay) ? 'resmenu-manager-blurred' : '' }} payment-settings-page">

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if($errors->any())
    <div class="alert alert-error">{{ $errors->first() }}</div>
@endif

<div class="page-header">
    <h1 class="page-title">Payment Settings</h1>
    <p class="page-subtitle">Configure payment options for customer checkout on your menu</p>
</div>

<div class="info-box">
    <div class="info-box-title">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        Important
    </div>
    <div class="info-box-content">
        These settings control payment options for <strong>customer food orders</strong> at checkout. This is separate from subscription billing (managed in <a href="{{ route('manager.billing.index') }}">Billing</a>). Enable only the methods you want customers to use.
    </div>
</div>

<div class="tabs-container">
    <div class="tabs-nav">
        <button type="button" class="tab-button active" onclick="switchTab('paystack', this)">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
            Paystack
        </button>
        <button type="button" class="tab-button" onclick="switchTab('flutterwave', this)">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
            Flutterwave
        </button>
        <button type="button" class="tab-button" onclick="switchTab('bank_transfer', this)">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Bank Transfer
        </button>
    </div>

    <div id="tab-paystack" class="tab-content active">
        <form method="POST" action="{{ route('manager.billing.payment-settings.save') }}">
            @csrf
            <input type="hidden" name="gateway" value="paystack">
            <div class="settings-card">
                <div class="section-header">
                    <div class="section-title">Paystack Configuration</div>
                    <span class="status-badge {{ !empty($ps['is_active']) ? 'active' : 'inactive' }}">
                        <span class="status-dot"></span>{{ !empty($ps['is_active']) ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                <div class="toggle-row">
                    <div class="toggle-info"><div class="toggle-label">Enable Paystack</div><div class="toggle-description">Allow customers to pay with Paystack</div></div>
                    <label class="toggle-switch"><input type="checkbox" name="is_active" value="1" @checked(!empty($ps['is_active']))><span class="toggle-slider"></span></label>
                </div>
                <div class="toggle-row">
                    <div class="toggle-info"><div class="toggle-label">Test Mode</div><div class="toggle-description">Use test credentials (no real payments)</div></div>
                    <label class="toggle-switch"><input type="checkbox" name="test_mode" value="1" id="paystack_test_mode" @checked(!empty($ps['test_mode'])) onchange="toggleTestCredentials('paystack', this.checked)"><span class="toggle-slider"></span></label>
                </div>
            </div>
            <div class="settings-card" id="paystack_test_credentials" style="display:{{ !empty($ps['test_mode']) ? 'block' : 'none' }};">
                <div class="section-subtitle"><span>Test Credentials</span><span class="badge badge-test">TEST</span></div>
                <div class="form-group"><label>Public Key</label><input type="text" name="public_key_test" value="{{ $ps['public_key_test'] ?? '' }}" placeholder="pk_test_xxxxxxxxxxxxx"></div>
                <div class="form-group"><label>Secret Key</label><input type="password" name="secret_key_test" placeholder="{{ !empty($ps['secret_key_test']) ? '••••••••••••••••' : 'sk_test_xxxxxxxxxxxxx' }}"><div class="form-hint">Leave empty to keep existing key</div></div>
                <div class="form-group"><label>Webhook Secret</label><input type="text" name="webhook_secret_test" value="{{ $ps['webhook_secret_test'] ?? '' }}" placeholder="Optional"></div>
            </div>
            <div class="settings-card">
                <div class="section-subtitle"><span>Live Credentials</span><span class="badge badge-live">LIVE</span></div>
                <div class="form-group"><label>Public Key</label><input type="text" name="public_key_live" value="{{ $ps['public_key_live'] ?? '' }}" placeholder="pk_live_xxxxxxxxxxxxx"></div>
                <div class="form-group"><label>Secret Key</label><input type="password" name="secret_key_live" placeholder="{{ !empty($ps['secret_key_live']) ? '••••••••••••••••' : 'sk_live_xxxxxxxxxxxxx' }}"><div class="form-hint">Leave empty to keep existing key</div></div>
                <div class="form-group"><label>Webhook Secret</label><input type="text" name="webhook_secret_live" value="{{ $ps['webhook_secret_live'] ?? '' }}" placeholder="Optional"></div>
            </div>
            <div class="settings-card">
                <div class="section-subtitle">Order Webhook URL</div>
                <div class="form-hint">Copy this URL and add it to your Paystack dashboard (Settings → API Keys & Webhooks):</div>
                <div class="webhook-url-wrap">
                    <input type="text" class="webhook-url" id="paystack-webhook-url" readonly value="{{ $paystackWebhookUrl }}">
                    <button type="button" class="webhook-copy-btn" onclick="copyWebhookUrl('paystack-webhook-url', this)">Copy URL</button>
                </div>
            </div>
            <div class="save-section"><button type="submit" class="btn-save">Save Paystack Settings</button></div>
        </form>
    </div>

    <div id="tab-flutterwave" class="tab-content">
        <form method="POST" action="{{ route('manager.billing.payment-settings.save') }}">
            @csrf
            <input type="hidden" name="gateway" value="flutterwave">
            <div class="settings-card">
                <div class="section-header">
                    <div class="section-title">Flutterwave Configuration</div>
                    <span class="status-badge {{ !empty($fw['is_active']) ? 'active' : 'inactive' }}">
                        <span class="status-dot"></span>{{ !empty($fw['is_active']) ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                <div class="toggle-row">
                    <div class="toggle-info"><div class="toggle-label">Enable Flutterwave</div><div class="toggle-description">Allow customers to pay with Flutterwave</div></div>
                    <label class="toggle-switch"><input type="checkbox" name="is_active" value="1" @checked(!empty($fw['is_active']))><span class="toggle-slider"></span></label>
                </div>
                <div class="toggle-row">
                    <div class="toggle-info"><div class="toggle-label">Test Mode</div><div class="toggle-description">Use test credentials (no real payments)</div></div>
                    <label class="toggle-switch"><input type="checkbox" name="test_mode" value="1" id="flutterwave_test_mode" @checked(!empty($fw['test_mode'])) onchange="toggleTestCredentials('flutterwave', this.checked)"><span class="toggle-slider"></span></label>
                </div>
            </div>
            <div class="settings-card" id="flutterwave_test_credentials" style="display:{{ !empty($fw['test_mode']) ? 'block' : 'none' }};">
                <div class="section-subtitle"><span>Test Credentials</span><span class="badge badge-test">TEST</span></div>
                <div class="form-group"><label>Public Key</label><input type="text" name="public_key_test" value="{{ $fw['public_key_test'] ?? '' }}" placeholder="FLWPUBK_TEST-xxxxxxxxxxxxx"></div>
                <div class="form-group"><label>Secret Key</label><input type="password" name="secret_key_test" placeholder="{{ !empty($fw['secret_key_test']) ? '••••••••••••••••' : 'FLWSECK_TEST-xxxxxxxxxxxxx' }}"><div class="form-hint">Leave empty to keep existing key</div></div>
                <div class="form-group"><label>Webhook Secret Hash</label><input type="text" name="webhook_secret_test" value="{{ $fw['webhook_secret_test'] ?? '' }}" placeholder="Optional"></div>
            </div>
            <div class="settings-card">
                <div class="section-subtitle"><span>Live Credentials</span><span class="badge badge-live">LIVE</span></div>
                <div class="form-group"><label>Public Key</label><input type="text" name="public_key_live" value="{{ $fw['public_key_live'] ?? '' }}" placeholder="FLWPUBK-xxxxxxxxxxxxx"></div>
                <div class="form-group"><label>Secret Key</label><input type="password" name="secret_key_live" placeholder="{{ !empty($fw['secret_key_live']) ? '••••••••••••••••' : 'FLWSECK-xxxxxxxxxxxxx' }}"><div class="form-hint">Leave empty to keep existing key</div></div>
                <div class="form-group"><label>Webhook Secret Hash</label><input type="text" name="webhook_secret_live" value="{{ $fw['webhook_secret_live'] ?? '' }}" placeholder="Optional"></div>
            </div>
            <div class="settings-card">
                <div class="section-subtitle">Order Webhook URL</div>
                <div class="form-hint">Copy this URL and add it to your Flutterwave dashboard (Settings → Webhooks):</div>
                <div class="webhook-url-wrap">
                    <input type="text" class="webhook-url" id="flutterwave-webhook-url" readonly value="{{ $flutterwaveWebhookUrl }}">
                    <button type="button" class="webhook-copy-btn" onclick="copyWebhookUrl('flutterwave-webhook-url', this)">Copy URL</button>
                </div>
            </div>
            <div class="save-section"><button type="submit" class="btn-save">Save Flutterwave Settings</button></div>
        </form>
    </div>

    <div id="tab-bank_transfer" class="tab-content">
        <form method="POST" action="{{ route('manager.billing.payment-settings.save') }}">
            @csrf
            <input type="hidden" name="gateway" value="bank_transfer">
            <div class="settings-card">
                <div class="section-header">
                    <div class="section-title">Bank Transfer</div>
                    <span class="status-badge {{ !empty($bt['is_active']) ? 'active' : 'inactive' }}">
                        <span class="status-dot"></span>{{ !empty($bt['is_active']) ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                <div class="toggle-row">
                    <div class="toggle-info"><div class="toggle-label">Enable Bank Transfer</div><div class="toggle-description">Show bank details at checkout for customers to transfer funds</div></div>
                    <label class="toggle-switch"><input type="checkbox" name="is_active" value="1" @checked(!empty($bt['is_active']))><span class="toggle-slider"></span></label>
                </div>
                <div class="form-group"><label>Bank Name</label><input type="text" name="bank_name" value="{{ $bt['bank_name'] ?? '' }}" placeholder="e.g. GTBank"></div>
                <div class="form-group"><label>Account Number</label><input type="text" name="account_number" value="{{ $bt['account_number'] ?? '' }}" placeholder="1234567890"></div>
                <div class="form-group"><label>Account Name</label><input type="text" name="account_name" value="{{ $bt['account_name'] ?? '' }}" placeholder="Restaurant Name"></div>
            </div>
            <div class="save-section"><button type="submit" class="btn-save">Save Bank Transfer Settings</button></div>
        </form>
    </div>
</div>

</div>

@include('partials.manager.upgrade-overlay', [
    'showUpgradeOverlay' => $showUpgradeOverlay ?? false,
    'upgradePlans' => $upgradePlans ?? [],
    'overlayTitle' => 'Payments',
    'overlayMessage' => 'Upgrade your plan to enable online payments for orders and reservations.',
])

<script>
function copyWebhookUrl(inputId, btn) {
    var input = document.getElementById(inputId);
    if (input) {
        input.select();
        input.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(input.value).then(function() {
            btn.textContent = 'Copied!';
            btn.classList.add('copied');
            setTimeout(function() { btn.textContent = 'Copy URL'; btn.classList.remove('copied'); }, 2000);
        });
    }
}
function switchTab(tabName, btn) {
    document.querySelectorAll('.tab-content').forEach(function(t) { t.classList.remove('active'); });
    document.querySelectorAll('.tab-button').forEach(function(b) { b.classList.remove('active'); });
    document.getElementById('tab-' + tabName).classList.add('active');
    if (btn) btn.classList.add('active');
}
function toggleTestCredentials(gateway, isChecked) {
    var el = document.getElementById(gateway + '_test_credentials');
    if (el) el.style.display = isChecked ? 'block' : 'none';
}
document.addEventListener('DOMContentLoaded', function() {
    ['paystack','flutterwave'].forEach(function(g) {
        var cb = document.getElementById(g + '_test_mode');
        if (cb) toggleTestCredentials(g, cb.checked);
    });
});
</script>
@endsection
