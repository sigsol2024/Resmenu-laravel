@extends('layouts.manager')
@section('title', 'Billing')
@section('content')
<h1 style="margin:0 0 8px;">Billing</h1>
<p style="color:#6b7280;margin:0 0 24px;">Subscription for {{ $restaurant->name }}</p>

@if(!$access['valid'])
    <div class="alert alert-error">{{ $access['message'] }}</div>
@endif

<div class="card" style="margin-bottom:20px;">
    <h2 style="margin:0 0 12px;font-size:1rem;">Current subscription</h2>
    @if($subscription)
        <p><strong>Status:</strong> {{ $subscription->status }}</p>
        @if($subscription->trial_ends_at)
            <p><strong>Trial ends:</strong> {{ $subscription->trial_ends_at }}</p>
        @endif
        @if($subscription->current_period_end)
            <p><strong>Period ends:</strong> {{ $subscription->current_period_end }}</p>
        @endif
        @if($subscription->plan)
            <p><strong>Plan:</strong> {{ $subscription->plan->name ?? $subscription->plan_id }}</p>
        @endif
    @else
        <p>No subscription record found.</p>
    @endif
</div>

<div class="card">
    <h2 style="margin:0 0 12px;font-size:1rem;">Available plans</h2>
    <ul style="margin:0;padding-left:18px;">
        @foreach($plans as $plan)
            <li style="margin-bottom:8px;">
                {{ $plan['name'] }} —
                ₦{{ number_format((float)($plan['monthly_price'] ?? 0)) }}/mo
                <a href="{{ route('manager.billing.checkout', ['plan' => $plan['slug'] ?? '', 'cycle' => 'monthly']) }}" style="margin-left:8px;">Subscribe</a>
            </li>
        @endforeach
    </ul>
    <p style="margin-top:16px;font-size:0.875rem;color:#6b7280;">Full billing UI (plan changes, payment history) uses legacy checkout until fully ported.</p>
</div>
@endsection
