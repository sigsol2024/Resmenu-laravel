@extends('layouts.manager')

@section('title', 'Subscribe')

@push('head')
<link rel="stylesheet" href="{{ asset('legacy/css/pages/manager-checkout.css') }}">
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">Checkout</h1>
    <p class="page-subtitle">Select a plan and complete your subscription payment</p>
</div>
<form method="post" action="{{ route('manager.billing.process-payment') }}" class="settings-card">
    @csrf
    @foreach($plans as $plan)
        <label class="flex items-center gap-3" style="display:flex;align-items:center;gap:12px;margin-bottom:12px;cursor:pointer;">
            <input type="radio" name="plan_id" value="{{ $plan['id'] }}" required>
            <span class="font-semibold">{{ $plan['name'] }}</span>
            <span style="color:#6b7280;">₦{{ number_format($plan['monthly_price'] ?? $plan['price_monthly'] ?? 0, 0) }}/mo</span>
        </label>
    @endforeach
    <div style="margin:20px 0;">
        <p class="font-medium" style="font-weight:600;margin-bottom:8px;">Billing cycle</p>
        <label style="display:block;margin-bottom:4px;"><input type="radio" name="billing_cycle" value="monthly" checked> Monthly</label>
        <label style="display:block;"><input type="radio" name="billing_cycle" value="annual"> Annual</label>
    </div>
    <div style="margin-bottom:20px;">
        <p class="font-medium" style="font-weight:600;margin-bottom:8px;">Payment gateway</p>
        <label style="display:block;margin-bottom:4px;"><input type="radio" name="gateway" value="paystack" checked> Paystack</label>
        <label style="display:block;"><input type="radio" name="gateway" value="flutterwave"> Flutterwave</label>
    </div>
    <button type="submit" class="btn btn-primary">Continue to payment</button>
</form>
@endsection
