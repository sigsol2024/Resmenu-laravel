@extends('layouts.manager')

@section('title', 'Payment settings')

@push('head')
<link rel="stylesheet" href="{{ asset('legacy/css/pages/manager-payment-settings.css') }}">
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">Customer payment settings</h1>
    <p class="page-subtitle">Paystack, Flutterwave, and bank transfer for orders and reservation deposits</p>
</div>

@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@if($errors->any())<div class="alert alert-error">{{ $errors->first() }}</div>@endif

@if(!empty($showUpgradeOverlay))
<div class="card" style="margin-bottom:24px;border:2px solid #f59e0b;background:#fffbeb;">
    <p>Upgrade to Professional or Enterprise to configure customer checkout payments.</p>
    <a href="{{ route('manager.billing.index') }}" class="btn btn-primary">View plans</a>
</div>
@else
@php
    $ps = $settings['paystack'] ?? null;
    $fw = $settings['flutterwave'] ?? null;
    $bt = $settings['bank_transfer'] ?? null;
@endphp

<div class="settings-card" style="margin-bottom:24px;">
    <h2 class="section-title">Paystack</h2>
    <form method="post" action="{{ route('manager.billing.payment-settings.save') }}">
        @csrf
        <input type="hidden" name="gateway" value="paystack">
        <label style="display:block;margin-bottom:12px;"><input type="checkbox" name="is_active" value="1" @checked($ps->is_active ?? false)> Active</label>
        <label style="display:block;margin-bottom:12px;"><input type="checkbox" name="test_mode" value="1" @checked($ps->test_mode ?? true)> Test mode</label>
        <div class="form-group"><label>Public key (test)</label><input class="form-input" name="public_key_test" value="{{ $ps->public_key_test ?? '' }}"></div>
        <div class="form-group"><label>Secret key (test)</label><input class="form-input" type="password" name="secret_key_test" placeholder="Leave blank to keep"></div>
        <div class="form-group"><label>Webhook secret (test)</label><input class="form-input" name="webhook_secret_test" value="{{ $ps->webhook_secret_test ?? '' }}"></div>
        <div class="form-group"><label>Public key (live)</label><input class="form-input" name="public_key_live" value="{{ $ps->public_key_live ?? '' }}"></div>
        <div class="form-group"><label>Secret key (live)</label><input class="form-input" type="password" name="secret_key_live" placeholder="Leave blank to keep"></div>
        <div class="form-group"><label>Webhook secret (live)</label><input class="form-input" name="webhook_secret_live" value="{{ $ps->webhook_secret_live ?? '' }}"></div>
        <p class="text-muted" style="font-size:0.8rem;">Webhook URL: {{ $webhookBase }}/paystack</p>
        <button type="submit" class="btn btn-primary">Save Paystack</button>
    </form>
</div>

<div class="settings-card" style="margin-bottom:24px;">
    <h2 class="section-title">Flutterwave</h2>
    <form method="post" action="{{ route('manager.billing.payment-settings.save') }}">
        @csrf
        <input type="hidden" name="gateway" value="flutterwave">
        <label style="display:block;margin-bottom:12px;"><input type="checkbox" name="is_active" value="1" @checked($fw->is_active ?? false)> Active</label>
        <label style="display:block;margin-bottom:12px;"><input type="checkbox" name="test_mode" value="1" @checked($fw->test_mode ?? true)> Test mode</label>
        <div class="form-group"><label>Public key (test)</label><input class="form-input" name="public_key_test" value="{{ $fw->public_key_test ?? '' }}"></div>
        <div class="form-group"><label>Secret key (test)</label><input class="form-input" type="password" name="secret_key_test" placeholder="Leave blank to keep"></div>
        <div class="form-group"><label>Public key (live)</label><input class="form-input" name="public_key_live" value="{{ $fw->public_key_live ?? '' }}"></div>
        <div class="form-group"><label>Secret key (live)</label><input class="form-input" type="password" name="secret_key_live" placeholder="Leave blank to keep"></div>
        <p class="text-muted" style="font-size:0.8rem;">Webhook URL: {{ $webhookBase }}/flutterwave</p>
        <button type="submit" class="btn btn-primary">Save Flutterwave</button>
    </form>
</div>

<div class="settings-card">
    <h2 class="section-title">Bank transfer</h2>
    <form method="post" action="{{ route('manager.billing.payment-settings.save') }}">
        @csrf
        <input type="hidden" name="gateway" value="bank_transfer">
        <label style="display:block;margin-bottom:12px;"><input type="checkbox" name="is_active" value="1" @checked($bt->is_active ?? false)> Active</label>
        <div class="form-group"><label>Bank name</label><input class="form-input" name="bank_name" value="{{ $bt->bank_name ?? '' }}"></div>
        <div class="form-group"><label>Account number</label><input class="form-input" name="account_number" value="{{ $bt->account_number ?? '' }}"></div>
        <div class="form-group"><label>Account name</label><input class="form-input" name="account_name" value="{{ $bt->account_name ?? '' }}"></div>
        <button type="submit" class="btn btn-primary">Save bank transfer</button>
    </form>
</div>
@endif
@endsection
