@extends('layouts.manager')

@section('title', 'Billing & Subscription')

@push('head')
<link rel="stylesheet" href="{{ resmenu_public_asset('css/pages/manager-billing.css') }}">
@if($inline = resmenu_inline_page_css('manager-billing.css'))
<style>{!! $inline !!}</style>
@endif
@endpush

@section('content')
<div class="billing-page">
<div class="page-header">
    <h1 class="page-title">Billing & Subscription</h1>
    <p class="page-subtitle">Manage your subscription plan and view payment history</p>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('info'))
    <div class="alert alert-success">{{ session('info') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-error">{{ session('error') }}</div>
@endif

@if($subscription)

    @if(($subscription['status'] ?? '') === 'trial')
        <div class="trial-banner">
            <div class="trial-info">
                <div class="trial-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="trial-text">
                    <h3>{{ $trialDaysLeft }} Days Left in Your Trial</h3>
                    <p>Your trial ends on {{ \Illuminate\Support\Carbon::parse($subscription['trial_ends_at'])->format('F j, Y') }}. Subscribe now to keep all features.</p>
                </div>
            </div>
            <div class="trial-action">
                <a href="{{ route('manager.billing.checkout') }}" class="btn-upgrade">Subscribe Now</a>
            </div>
        </div>
    @endif

    <div class="plan-card-current">
        <div class="plan-card-header">
            <div>
                <div class="plan-name-large">{{ $subscription['plan_name'] }} Plan</div>
                <div class="plan-billing-cycle">{{ ucfirst($subscription['billing_cycle'] ?? 'monthly') }} billing</div>
            </div>
            <span class="status-badge-large">{{ $statusInfo['label'] }}</span>
        </div>

        @php
            $currentPrice = ($subscription['billing_cycle'] ?? 'monthly') === 'annual'
                ? ($subscription['annual_price'] ?? 0)
                : ($subscription['monthly_price'] ?? 0);
        @endphp
        <div class="plan-price-display">
            {{ $formatPrice($currentPrice) }}
            <span class="plan-price-period">/ {{ ($subscription['billing_cycle'] ?? 'monthly') === 'annual' ? 'year' : 'month' }}</span>
        </div>

        <div class="plan-details">
            @if(($subscription['status'] ?? '') === 'trial' && !empty($subscription['trial_ends_at']))
                <div class="plan-detail-item">
                    <span class="plan-detail-label">Trial Ends</span>
                    <span class="plan-detail-value">{{ \Illuminate\Support\Carbon::parse($subscription['trial_ends_at'])->format('M j, Y') }}</span>
                </div>
            @elseif(!empty($subscription['current_period_end']))
                <div class="plan-detail-item">
                    <span class="plan-detail-label">Next Billing</span>
                    <span class="plan-detail-value">{{ \Illuminate\Support\Carbon::parse($subscription['current_period_end'])->format('M j, Y') }}</span>
                </div>
            @endif
            <div class="plan-detail-item">
                <span class="plan-detail-label">Categories</span>
                <span class="plan-detail-value">{{ (int)($subscription['max_categories'] ?? 0) === -1 ? 'Unlimited' : $subscription['max_categories'] }}</span>
            </div>
            <div class="plan-detail-item">
                <span class="plan-detail-label">Menu Items</span>
                <span class="plan-detail-value">{{ (int)($subscription['max_menu_items'] ?? 0) === -1 ? 'Unlimited' : $subscription['max_menu_items'] }}</span>
            </div>
        </div>

        <div class="plan-actions">
            @if(($subscription['plan_slug'] ?? '') !== 'enterprise')
                <a href="{{ route('manager.billing.checkout', ['upgrade' => 1]) }}" class="btn-upgrade">Upgrade Plan</a>
            @endif
        </div>
    </div>

    @if($scheduledChange)
        <div class="alert alert-success" style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;">
            <div>
                <strong>Scheduled change:</strong>
                Switch to {{ $scheduledChange['to_plan_name'] }}
                ({{ ucfirst($scheduledChange['to_billing_cycle']) }})
                on {{ \Illuminate\Support\Carbon::parse($scheduledChange['effective_at'])->format('M j, Y') }}.
            </div>
            <form method="post" action="{{ route('manager.billing.index') }}" style="margin:0;">
                @csrf
                <input type="hidden" name="action" value="cancel_scheduled_change">
                <button type="submit" class="btn btn-secondary btn-small">Cancel</button>
            </form>
        </div>
    @endif

    <div class="billing-grid">
        <div class="usage-card">
            <h3 class="card-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                Current Usage
            </h3>
            @include('partials.manager.usage-bar', ['label' => 'Categories', 'usage' => $usage['categories']])
            @include('partials.manager.usage-bar', ['label' => 'Menu Items', 'usage' => $usage['menu_items']])
            @include('partials.manager.usage-bar', ['label' => 'QR Styles', 'usage' => $usage['qr_styles']])
            @include('partials.manager.usage-bar', ['label' => 'Templates', 'usage' => $usage['templates']])
        </div>

        <div class="payment-card">
            <div class="payment-header">
                <h3 class="card-title" style="margin: 0;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Payment History
                </h3>
                <a href="{{ route('manager.billing.transactions') }}" style="font-size: 0.875rem; color: var(--primary); text-decoration: none; font-weight: 500;">View All</a>
            </div>
            <div class="payment-list">
                @forelse($paymentHistory as $payment)
                    <div class="payment-item">
                        <div class="payment-info">
                            <span class="payment-plan">{{ $payment['plan_name'] ?? 'Subscription' }}</span>
                            <span class="payment-date">{{ \Illuminate\Support\Carbon::parse($payment['created_at'])->format('M j, Y') }}</span>
                        </div>
                        <div class="payment-amount">
                            <div class="payment-value">{{ $formatPrice($payment['amount'], $payment['currency'] ?? 'NGN') }}</div>
                            <span class="payment-status {{ $payment['status'] }}">{{ ucfirst($payment['status']) }}</span>
                        </div>
                    </div>
                @empty
                    <div class="empty-payments"><p>No payment history yet</p></div>
                @endforelse
            </div>
        </div>
    </div>

@else
    <div class="no-subscription">
        <div class="no-subscription-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
            </svg>
        </div>
        <h2>No Active Subscription</h2>
        <p>Choose a plan to unlock all features and start managing your digital menu.</p>
        <a href="{{ route('manager.billing.checkout') }}" class="btn-upgrade" style="display: inline-block; background: var(--primary); color: #fff;">View Plans & Subscribe</a>
    </div>
@endif

<div class="plans-section">
    <h2 class="section-title">Available Plans</h2>
    <div class="plans-grid">
        @foreach($plans as $plan)
            @php
                $isCurrent = $subscription && (int)($subscription['plan_id'] ?? 0) === (int)$plan['id'];
                $isPopular = ($plan['slug'] ?? '') === 'professional';
                $monthlyDecision = $subscription
                    ? app(\App\Services\SubscriptionService::class)->getSubscriptionChangeDecision($subscription, $plan, 'monthly')
                    : ['mode' => 'immediate', 'type' => 'new'];
                $annualDecision = $subscription
                    ? app(\App\Services\SubscriptionService::class)->getSubscriptionChangeDecision($subscription, $plan, 'annual')
                    : ['mode' => 'immediate', 'type' => 'new'];
                $currentCycle = $subscription && $isCurrent ? ($subscription['billing_cycle'] ?? 'monthly') : 'monthly';
                $defaultCycle = $currentCycle === 'annual' ? 'annual' : 'monthly';
                $decisionForDefault = $defaultCycle === 'annual' ? $annualDecision : $monthlyDecision;
            @endphp
            <div class="plan-option {{ $isCurrent ? 'current' : '' }} {{ $isPopular ? 'popular' : '' }}">
                @if($isPopular)
                    <span class="popular-badge">Most Popular</span>
                @endif
                <div class="plan-option-name">{{ $plan['name'] }}</div>
                <div class="plan-option-price">{{ $formatPrice($plan['monthly_price']) }}</div>
                <div class="plan-option-period">per month ({{ $formatPrice($plan['annual_price']) }}/year)</div>
                @if((int)($plan['yearly_discount_percent'] ?? 20) > 0)
                    <div class="plan-option-save" style="font-size:0.85em;color:#16a34a;margin-top:2px;margin-bottom:8px;">Save {{ (int)$plan['yearly_discount_percent'] }}% off</div>
                @endif
                <ul class="plan-features">
                    <li>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        {{ (int)($plan['max_categories'] ?? 0) === -1 ? 'Unlimited' : $plan['max_categories'] }} Categories
                    </li>
                    <li>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        {{ (int)($plan['max_menu_items'] ?? 0) === -1 ? 'Unlimited' : $plan['max_menu_items'] }} Menu Items
                    </li>
                    <li>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        {{ (int)($plan['max_qr_styles'] ?? 0) === -1 ? 'Unlimited' : $plan['max_qr_styles'] }} QR Styles
                    </li>
                    <li>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        {{ (int)($plan['max_templates'] ?? 0) === -1 ? 'All' : $plan['max_templates'] }} Templates
                    </li>
                </ul>
                <form method="post" action="{{ route('manager.billing.index') }}" class="plan-select-form" style="margin:0;" data-monthly-mode="{{ $monthlyDecision['mode'] }}" data-annual-mode="{{ $annualDecision['mode'] }}">
                    @csrf
                    <input type="hidden" name="action" value="schedule_change">
                    <input type="hidden" name="target_plan_id" value="{{ (int)$plan['id'] }}">
                    <label class="billing-cycle-label">Billing</label>
                    <select name="target_cycle" class="plan-cycle-select" required>
                        <option value="monthly" @selected($defaultCycle === 'monthly')>Monthly</option>
                        <option value="annual" @selected($defaultCycle === 'annual')>Yearly</option>
                    </select>
                    <div class="plan-action-wrap" style="margin-top:8px;">
                        @if($decisionForDefault['mode'] === 'none')
                            <span class="btn-select-plan current plan-action-current" style="display:inline-block;width:100%;text-align:center;">Current plan</span>
                            <button type="submit" class="btn-select-plan primary plan-action-btn" style="width:100%;border:none;cursor:pointer;display:none;">Subscribe</button>
                        @else
                            <span class="btn-select-plan current plan-action-current" style="display:none;width:100%;text-align:center;">Current plan</span>
                            <button type="submit" class="btn-select-plan primary plan-action-btn" style="width:100%;border:none;cursor:pointer;">{{ $decisionForDefault['mode'] === 'scheduled' ? 'Schedule for period end' : 'Subscribe' }}</button>
                        @endif
                    </div>
                </form>
                @php
                    if ($isCurrent) {
                        $changeHint = 'Current plan. Upgrades are immediate; downgrades or billing-cycle changes are scheduled.';
                    } elseif ($monthlyDecision['mode'] === 'scheduled' || $annualDecision['mode'] === 'scheduled') {
                        $changeHint = 'Downgrades and billing-cycle changes are scheduled for your period end.';
                    } else {
                        $changeHint = 'Upgrade changes are applied immediately after successful payment.';
                    }
                @endphp
                <p style="font-size:0.78rem;color:var(--muted);margin-top:8px;">{{ $changeHint }}</p>
            </div>
        @endforeach
    </div>
</div>
</div>

<script>
(function() {
    function updatePlanAction(form) {
        var sel = form.querySelector('.plan-cycle-select');
        var wrap = form.querySelector('.plan-action-wrap');
        if (!sel || !wrap) return;
        var mode = sel.value === 'annual' ? form.getAttribute('data-annual-mode') : form.getAttribute('data-monthly-mode');
        var currentSpan = wrap.querySelector('.plan-action-current');
        var btn = wrap.querySelector('.plan-action-btn');
        if (mode === 'none') {
            if (currentSpan) currentSpan.style.display = 'inline-block';
            if (btn) { btn.style.display = 'none'; btn.disabled = true; }
        } else {
            if (currentSpan) currentSpan.style.display = 'none';
            if (btn) {
                btn.style.display = 'block';
                btn.disabled = false;
                btn.textContent = mode === 'scheduled' ? 'Schedule for period end' : 'Subscribe';
            }
        }
    }
    document.querySelectorAll('.plan-select-form').forEach(function(form) {
        var sel = form.querySelector('.plan-cycle-select');
        if (sel) sel.addEventListener('change', function() { updatePlanAction(form); });
        updatePlanAction(form);
    });
})();
</script>
@endsection
