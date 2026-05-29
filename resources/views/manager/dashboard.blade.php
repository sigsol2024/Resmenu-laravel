@extends('layouts.manager')

@section('title', 'Dashboard')

@push('head')
<link rel="stylesheet" href="{{ asset('legacy/css/pages/manager-dashboard.css') }}">
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">{{ $restaurant->name }}</h1>
    <p class="page-subtitle">Overview of your restaurant menu and orders</p>
</div>

@if(is_array($subscription ?? null))
    @if(($subscription['status'] ?? '') === 'trial' && $trialDaysRemaining > 0)
        <div class="subscription-banner trial-banner">
            <div class="banner-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="banner-content">
                <strong>{{ $trialDaysRemaining }} days left in your free trial</strong>
                <span>Your trial ends on {{ \Illuminate\Support\Carbon::parse($subscription['trial_ends_at'])->format('F j, Y') }}. Subscribe to keep all features.</span>
            </div>
            <a href="{{ route('manager.billing.index') }}" class="banner-btn">Subscribe Now</a>
        </div>
    @elseif(($subscription['status'] ?? '') === 'trial' && $trialDaysRemaining <= 0)
        <div class="subscription-banner expired-banner">
            <div class="banner-content">
                <strong>Your free trial has ended</strong>
                <span>Subscribe now to continue using all features.</span>
            </div>
            <a href="{{ route('manager.billing.index') }}" class="banner-btn">Subscribe Now</a>
        </div>
    @elseif(($subscription['status'] ?? '') === 'expired' || (!($access['valid'] ?? true) && ($access['lockout_reason'] ?? '') === 'subscription_expired'))
        <div class="subscription-banner expired-banner">
            <div class="banner-content">
                <strong>Your subscription has expired</strong>
                <span>Renew now to continue managing your menu.</span>
            </div>
            <a href="{{ route('manager.billing.index') }}" class="banner-btn">Renew Now</a>
        </div>
    @endif
@endif

@if(!($access['valid'] ?? true))
    <div class="alert alert-error" style="margin-bottom:20px;">{{ $access['message'] ?? 'Subscription required.' }}</div>
@endif

<section class="stats">
    <div class="stat-card"><div class="stat-label">Menu Items</div><div class="stat-value">{{ $stats['menu_items'] }}</div></div>
    <div class="stat-card"><div class="stat-label">Categories</div><div class="stat-value">{{ $stats['categories'] }}</div></div>
    <div class="stat-card"><div class="stat-label">Available Items</div><div class="stat-value">{{ $stats['available_items'] }}</div></div>
    <div class="stat-card"><div class="stat-label">Unavailable Items</div><div class="stat-value">{{ $stats['unavailable_items'] }}</div></div>
    <div class="stat-card"><div class="stat-label">Total Orders</div><div class="stat-value">{{ $stats['total_orders'] }}</div></div>
    <div class="stat-card"><div class="stat-label">Orders Revenue</div><div class="stat-value">₦{{ number_format($stats['total_orders_amount'], 0) }}</div></div>
</section>

@if(is_array($subscription ?? null))
<section class="chart-card">
    <h2 class="chart-title">Plan usage</h2>
    <div style="display:grid;gap:16px;max-width:560px;">
        @foreach([
            ['label' => 'Categories', 'usage' => $usageCategories ?? []],
            ['label' => 'Menu items', 'usage' => $usageMenuItems ?? []],
            ['label' => 'QR styles', 'usage' => $usageQrStyles ?? []],
        ] as $bar)
            @php
                $u = $bar['usage'];
                $unlimited = $u['unlimited'] ?? false;
                $used = (int) ($u['used'] ?? 0);
                $limit = $unlimited ? 100 : max(1, (int) ($u['limit'] ?? 1));
                $pct = $unlimited ? 15 : min(100, round(($used / $limit) * 100));
            @endphp
            <div>
                <div style="display:flex;justify-content:space-between;font-size:.875rem;margin-bottom:6px;">
                    <span>{{ $bar['label'] }}</span>
                    <span>{{ $used }} / {{ $unlimited ? '∞' : $limit }}</span>
                </div>
                <div style="height:8px;background:#e5e7eb;border-radius:4px;overflow:hidden;">
                    <div style="width:{{ $pct }}%;height:100%;background:#111827;border-radius:4px;"></div>
                </div>
            </div>
        @endforeach
    </div>
</section>
@endif

@php
    $uniqueBrowsers = count($qrAnalytics['scans_by_browser'] ?? []);
    $uniqueDevices = count($qrAnalytics['scans_by_device'] ?? []);
    $analyticsChartData = [
        ['label' => 'Total Scans', 'value' => $qrAnalytics['total_scans'] ?? 0, 'color' => '#5EB344'],
        ['label' => 'Browsers', 'value' => $uniqueBrowsers, 'color' => '#FCB72A'],
        ['label' => 'Device Types', 'value' => $uniqueDevices, 'color' => '#F8821A'],
        ['label' => 'Total Orders', 'value' => $stats['total_orders'], 'color' => '#4f46e5'],
        ['label' => 'Orders Revenue (₦)', 'value' => (int) $stats['total_orders_amount'], 'color' => '#10b981'],
    ];
    $analyticsMax = max(1, max(array_column($analyticsChartData, 'value')));
    foreach ($analyticsChartData as &$chartItem) {
        $chartItem['percentage'] = ($chartItem['value'] / $analyticsMax) * 100;
    }
    unset($chartItem);
@endphp

<section class="chart-card">
    <h2 class="chart-title">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
        Analytics Overview
    </h2>
    <div class="simple-bar-chart gradient-bars">
        @foreach($analyticsChartData as $item)
            <div class="item" style="--clr: {{ $item['color'] }}; --val: {{ round($item['percentage'], 1) }}">
                <div class="label">{{ $item['label'] }}</div>
                <div class="value">{{ number_format($item['value']) }}</div>
            </div>
        @endforeach
    </div>
</section>

<section class="quick-actions">
    <h2 class="section-title">Quick Actions</h2>
    <div class="actions-grid">
        @if($showOrdersQuickAction)
            <a href="{{ route('manager.orders.index') }}" class="action-card">
                <div class="action-header">
                    <div class="action-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:22px;height:22px"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                    </div>
                    <div class="action-title">View Orders</div>
                </div>
                <p class="action-desc">Manage and track customer orders, update status, and view order details</p>
                <div class="action-arrow">Get started →</div>
            </a>
        @endif
        <a href="{{ route('manager.menu-items.index') }}" class="action-card">
            <div class="action-header"><div class="action-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:22px;height:22px"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-3h6m-3-3h6"/></svg></div><div class="action-title">Manage Menu Items</div></div>
            <p class="action-desc">Create and update menu items with descriptions, prices, and images</p>
            <div class="action-arrow">Get started →</div>
        </a>
        <a href="{{ route('manager.categories.index') }}" class="action-card">
            <div class="action-header"><div class="action-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:22px;height:22px"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/></svg></div><div class="action-title">Manage Categories</div></div>
            <p class="action-desc">Add, edit, or delete menu categories to organize your menu items</p>
            <div class="action-arrow">Get started →</div>
        </a>
        <a href="{{ route('manager.customization') }}" class="action-card">
            <div class="action-header"><div class="action-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:22px;height:22px"><path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.876-5.814a1.151 1.151 0 00-1.597-1.597L14.146 6.32a15.996 15.996 0 00-4.649 4.763m3.42 3.42a6.776 6.776 0 00-3.42-3.42"/></svg></div><div class="action-title">Customize Menu</div></div>
            <p class="action-desc">Change colors, fonts, and styling to match your restaurant brand</p>
            <div class="action-arrow">Get started →</div>
        </a>
        <a href="{{ route('manager.qr.code') }}" class="action-card">
            <div class="action-header"><div class="action-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:22px;height:22px"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.5a.75.75 0 01.75-.75h4.5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0V6.31l-3.72 3.72a.75.75 0 01-1.06-1.06l3.72-3.72H4.5a.75.75 0 01-.75-.75zm9.75 0a.75.75 0 01.75-.75h4.5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0V6.31l-3.72 3.72a.75.75 0 11-1.06-1.06l3.72-3.72H14.25a.75.75 0 01-.75-.75zM3.75 15a.75.75 0 01.75.75H5.69l3.72-3.72a.75.75 0 111.06 1.06l-3.72 3.72v1.19a.75.75 0 01-1.5 0v-4.5zm9.75 0a.75.75 0 01.75.75h1.19l-3.72-3.72a.75.75 0 111.06-1.06l3.72 3.72V10.5a.75.75 0 011.5 0v4.5a.75.75 0 01-.75.75z"/></svg></div><div class="action-title">View QR Codes</div></div>
            <p class="action-desc">Generate, customize, and download QR codes for your restaurant menu</p>
            <div class="action-arrow">Get started →</div>
        </a>
        <a href="{{ route('manager.settings.edit') }}" class="action-card">
            <div class="action-header"><div class="action-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:22px;height:22px"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065zM15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div><div class="action-title">Settings</div></div>
            <p class="action-desc">Update your account, restaurant details, and password</p>
            <div class="action-arrow">Get started →</div>
        </a>
    </div>
</section>

<p style="margin-top:16px;">
    <a class="btn btn-primary" href="{{ url('/restaurant/'.$restaurant->slug) }}" target="_blank" rel="noopener">View public menu</a>
</p>
@endsection
