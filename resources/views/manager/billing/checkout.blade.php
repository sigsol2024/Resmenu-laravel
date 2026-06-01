@extends('layouts.manager')

@section('title', 'Subscribe')

@push('head')
<link rel="stylesheet" href="{{ asset('legacy/css/pages/manager-checkout.css') }}">
<style>
.plan-checkout-warning { display:none; margin:20px 0; padding:16px; background:#fffbeb; border:1px solid #fcd34d; border-radius:12px; color:#92400e; }
.plan-checkout-warning.is-visible { display:block; }
.plan-checkout-warning ul { margin:8px 0 0 18px; }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">Checkout</h1>
    <p class="page-subtitle">Select a plan and complete your subscription payment</p>
</div>

@if(session('error'))
    <div class="alert alert-error" style="margin-bottom:16px;">{{ session('error') }}</div>
@endif
@if($errors->any())
    <div class="alert alert-error" style="margin-bottom:16px;">
        <ul style="margin:0;padding-left:18px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="post" action="{{ route('manager.billing.process-payment') }}" class="settings-card" id="checkoutForm">
    @csrf
    @foreach($plans as $plan)
        <label class="flex items-center gap-3" style="display:flex;align-items:center;gap:12px;margin-bottom:12px;cursor:pointer;">
            <input type="radio" name="plan_id" value="{{ $plan['id'] }}" required
                data-plan-name="{{ $plan['name'] }}"
                data-max-categories="{{ (int)($plan['max_categories'] ?? -1) }}"
                data-max-menu-items="{{ (int)($plan['max_menu_items'] ?? -1) }}"
                @checked($selectedPlanId === (int)$plan['id'])>
            <span class="font-semibold">{{ $plan['name'] }}</span>
            <span style="color:#6b7280;">₦{{ number_format($plan['monthly_price'] ?? $plan['price_monthly'] ?? 0, 0) }}/mo</span>
        </label>
    @endforeach

    <div id="planCheckoutWarning" class="plan-checkout-warning" role="alert" aria-live="polite">
        <strong>⚠️ Plan limits</strong>
        <p style="margin:8px 0 4px;">You currently have <strong>{{ (int)($usage['categories']['used'] ?? 0) }}</strong> categories and <strong>{{ (int)($usage['menu_items']['used'] ?? 0) }}</strong> menu items.</p>
        <p id="planCheckoutWarningBody" style="margin:0;"></p>
        <p style="margin:12px 0 0;font-size:0.9rem;">Your data will not be deleted and will automatically reappear if you upgrade.</p>
        <p style="margin:12px 0 0;"><a href="{{ route('manager.billing.index') }}">Choose another plan</a></p>
    </div>

    <div style="margin:20px 0;">
        <p class="font-medium" style="font-weight:600;margin-bottom:8px;">Billing cycle</p>
        <label style="display:block;margin-bottom:4px;">
            <input type="radio" name="billing_cycle" value="monthly" @checked($selectedCycle === 'monthly')> Monthly
        </label>
        <label style="display:block;">
            <input type="radio" name="billing_cycle" value="annual" @checked($selectedCycle === 'annual')> Annual
        </label>
    </div>
    <div style="margin-bottom:20px;">
        <p class="font-medium" style="font-weight:600;margin-bottom:8px;">Payment gateway</p>
        @forelse($paymentGateways ?? [] as $index => $paymentGateway)
            <label style="display:block;margin-bottom:4px;">
                <input type="radio" name="gateway" value="{{ $paymentGateway['code'] }}" @checked($index === 0) required>
                {{ $paymentGateway['label'] }}
            </label>
        @empty
            <p style="color:#b91c1c;margin:0;">No payment gateway is currently available. Please contact support.</p>
        @endforelse
    </div>
    <button type="submit" class="btn btn-primary" @disabled(empty($paymentGateways))>Continue to payment</button>
</form>

<script>
(function() {
    var comparisons = @json($planComparisons);
    var warningEl = document.getElementById('planCheckoutWarning');
    var bodyEl = document.getElementById('planCheckoutWarningBody');

    function updateWarning() {
        var selected = document.querySelector('input[name="plan_id"]:checked');
        if (!selected || !warningEl || !bodyEl) return;

        var planId = selected.value;
        var data = comparisons[planId];
        if (!data || !data.summary) {
            warningEl.classList.remove('is-visible');
            return;
        }

        var summary = data.summary;
        var catHidden = summary.categories && summary.categories.hidden_count ? summary.categories.hidden_count : 0;
        var itemHidden = summary.menu_items && summary.menu_items.hidden_count ? summary.menu_items.hidden_count : 0;
        var planName = data.plan_name || selected.getAttribute('data-plan-name') || 'this plan';

        if (catHidden > 0 || itemHidden > 0) {
            var catLimit = summary.categories.limit;
            var itemLimit = summary.menu_items.limit;
            var parts = [];
            parts.push('<strong>' + planName + '</strong> includes ' +
                (catLimit === 'unlimited' ? 'unlimited categories' : catLimit + ' categories') + ' and ' +
                (itemLimit === 'unlimited' ? 'unlimited menu items' : itemLimit + ' menu items') + '.');
            parts.push('After payment:');
            var list = [];
            if (catHidden > 0) list.push('<li><strong>' + catHidden + '</strong> categories will be hidden on your public menu</li>');
            if (itemHidden > 0) list.push('<li><strong>' + itemHidden + '</strong> menu items will be hidden on your public menu</li>');
            if (list.length) parts.push('<ul>' + list.join('') + '</ul>');
            parts.push('Lowest display order is kept visible first.');
            bodyEl.innerHTML = parts.join(' ');
            warningEl.classList.add('is-visible');
        } else {
            warningEl.classList.remove('is-visible');
        }
    }

    document.querySelectorAll('input[name="plan_id"]').forEach(function(radio) {
        radio.addEventListener('change', updateWarning);
    });
    updateWarning();
})();
</script>
@endsection
