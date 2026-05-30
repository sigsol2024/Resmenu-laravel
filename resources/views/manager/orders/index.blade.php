@extends('layouts.manager')
@section('title', 'Orders')
@push('head')
<link rel="stylesheet" href="{{ asset('legacy/css/pages/manager-orders.css') }}">
@endpush
@section('content')
@php
    $statusLabels = ['pending' => 'Pending', 'confirmed' => 'Confirmed', 'on_hold' => 'On hold', 'cancelled' => 'Cancelled', 'completed' => 'Completed'];
    $statusColors = ['pending' => '#f59e0b', 'confirmed' => '#3b82f6', 'on_hold' => '#6b7280', 'cancelled' => '#ef4444', 'completed' => '#10b981'];
    $badgeStyles = [
        'pending' => 'background:#fef3c7;color:#92400e',
        'confirmed' => 'background:#dbeafe;color:#1e40af',
        'on_hold' => 'background:#f3f4f6;color:#4b5563',
        'cancelled' => 'background:#fee2e2;color:#991b1b',
        'completed' => 'background:#d1fae5;color:#065f46',
    ];
    $statusChartData = [];
    $statusMax = max(array_values($stats)) ?: 1;
    foreach ($statuses as $s) {
        $statusChartData[] = [
            'label' => $statusLabels[$s],
            'value' => $stats[$s],
            'color' => $statusColors[$s],
            'pct' => ($stats[$s] / $statusMax) * 100,
        ];
    }
@endphp

<div class="resmenu-manager-content-wrap {{ !empty($showUpgradeOverlay) ? 'resmenu-manager-blurred' : '' }}">
<div class="page-header">
    <h1 class="page-title">Orders</h1>
    <p class="page-subtitle">View revenue and manage orders for {{ $restaurant->name }}</p>
</div>

<section class="orders-overview" style="margin-bottom:24px;">
    <div class="stats orders-stats">
        @foreach($statuses as $s)
            @php
                $curr = $stats[$s];
                $prev = $statsLastMonth[$s] ?? 0;
                $diff = $prev > 0 ? round((($curr - $prev) / $prev) * 100) : ($curr > 0 ? 100 : 0);
                $isUp = $curr >= $prev;
                $showTrend = $prev > 0 || $curr > 0;
            @endphp
            <div class="stat-card" style="background:#fff;padding:16px;border-radius:8px;box-shadow:0 1px 3px rgba(0,0,0,0.1);border-left:3px solid {{ $statusColors[$s] ?? '#e5e7eb' }};">
                <div class="stat-label" style="font-size:0.7rem;color:{{ $statusColors[$s] ?? '#6b7280' }};text-transform:uppercase;margin-bottom:4px;font-weight:600;">{{ $statusLabels[$s] }}</div>
                <div class="stat-value" style="font-size:1.5rem;font-weight:700;color:#111827;">{{ $curr }}</div>
                @if($showTrend && $diff != 0)
                    <div class="stat-trend" style="font-size:0.7rem;margin-top:4px;display:flex;align-items:center;gap:4px;">
                        <span style="color:{{ $isUp ? '#059669' : '#dc2626' }};">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width:12px;height:12px;display:inline;vertical-align:middle;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $isUp ? 'M5 10l7-7m0 0l7 7m-7-7v18' : 'M19 14l-7 7m0 0l-7-7m7 7V3' }}" />
                            </svg>
                            {{ abs($diff) }}%
                        </span>
                        <span style="color:#9ca3af;font-size:0.65rem;">vs last month</span>
                    </div>
                @elseif($showTrend)
                    <div class="stat-trend" style="font-size:0.65rem;color:#9ca3af;margin-top:4px;">vs last month</div>
                @endif
            </div>
        @endforeach
        @php
            $revDiff = ($revenueLastMonth ?? 0) > 0 ? round((($revenue - $revenueLastMonth) / $revenueLastMonth) * 100) : ($revenue > 0 ? 100 : 0);
            $revUp = $revenue >= ($revenueLastMonth ?? 0);
        @endphp
        <div class="stat-card" style="background:#fff;padding:16px;border-radius:8px;box-shadow:0 1px 3px rgba(0,0,0,0.1);border-left:3px solid #111827;">
            <div class="stat-label" style="font-size:0.7rem;color:#6b7280;text-transform:uppercase;margin-bottom:4px;font-weight:600;">Total Amount</div>
            <div class="stat-value" style="font-size:1rem;font-weight:700;color:#111827;">{{ $currencySymbol }}{{ number_format($revenue, 2) }}</div>
            @if((($revenueLastMonth ?? 0) > 0 || $revenue > 0) && $revDiff != 0)
                <div class="stat-trend" style="font-size:0.7rem;margin-top:4px;display:flex;align-items:center;gap:4px;">
                    <span style="color:{{ $revUp ? '#059669' : '#dc2626' }};">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width:12px;height:12px;display:inline;vertical-align:middle;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $revUp ? 'M5 10l7-7m0 0l7 7m-7-7v18' : 'M19 14l-7 7m0 0l-7-7m7 7V3' }}" />
                        </svg>
                        {{ abs($revDiff) }}%
                    </span>
                    <span style="color:#9ca3af;font-size:0.65rem;">vs last month</span>
                </div>
            @else
                <div class="stat-trend" style="font-size:0.65rem;color:#9ca3af;margin-top:4px;">vs last month</div>
            @endif
        </div>
    </div>

    <div class="settings-card" style="padding:24px;margin-bottom:24px;">
        <h3 class="section-title" style="font-size:1rem;font-weight:600;margin-bottom:16px;color:#111827;">Orders by Status</h3>
        <div class="simple-bar-chart orders-bar-chart">
            @foreach($statusChartData as $item)
                <div class="item" style="--clr: {{ $item['color'] }}; --val: {{ round($item['pct'], 1) }}">
                    <div class="label">{{ $item['label'] }}</div>
                    <div class="value">{{ $item['value'] }}</div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="settings-card revenue-chart-card" style="padding:24px;margin-bottom:24px;">
        <div style="display:flex;flex-wrap:wrap;justify-content:space-between;align-items:center;gap:16px;margin-bottom:16px;">
            <div>
                <h3 class="chart-title" style="font-size:1rem;font-weight:600;margin:0;color:#111827;">Revenue Growth Over Time</h3>
                <p id="revenue-trend" style="font-size:0.75rem;margin:4px 0 0;color:#6b7280;display:flex;align-items:center;gap:4px;"></p>
            </div>
            <div class="revenue-chart-filters" style="display:flex;flex-wrap:wrap;gap:6px;">
                <button type="button" class="revenue-range-btn" data-range="today">Today</button>
                <button type="button" class="revenue-range-btn" data-range="3days">3 Days</button>
                <button type="button" class="revenue-range-btn" data-range="7days">7 Days</button>
                <button type="button" class="revenue-range-btn" data-range="1month">1 Month</button>
                <button type="button" class="revenue-range-btn btn-active" data-range="all">All Time</button>
            </div>
        </div>
        <div id="revenue-chart-wrapper" style="position:relative;height:280px;min-width:0;">
            <div id="revenue-chart-empty" style="display:none;color:#6b7280;padding:60px 24px;text-align:center;font-size:0.875rem;">No revenue data for this period.</div>
            <div id="revenue-line-chart" style="display:none;height:100%;width:100%;position:relative;">
                <svg id="revenue-svg" style="width:100%;height:100%;" preserveAspectRatio="none" viewBox="0 0 800 280"></svg>
                <div id="revenue-tooltip" class="revenue-tooltip" style="display:none;position:absolute;background:#111827;color:#fff;padding:8px 12px;border-radius:8px;font-size:0.75rem;font-weight:500;pointer-events:none;z-index:10;box-shadow:0 4px 12px rgba(0,0,0,0.15);white-space:nowrap;min-width:100px;line-height:1.4;"></div>
            </div>
        </div>
    </div>
</section>

<section class="orders-list">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
        <h2 class="section-title" style="font-size:1.125rem;font-weight:600;color:#111827;">Recent Orders</h2>
        @if($totalCount > 0)
            <a href="{{ route('manager.orders.list') }}" class="btn btn-primary" style="padding:8px 16px;font-size:0.875rem;">View All Orders</a>
        @endif
    </div>

    @if($recent->isEmpty())
        <p style="color:#6b7280;padding:24px;text-align:center;">No orders yet.</p>
    @else
        <div class="table-wrapper">
            <table class="orders-table" style="width:100%;border-collapse:collapse;">
                <thead>
                    <tr style="border-bottom:1px solid #e5e7eb;">
                        <th style="text-align:left;padding:12px 16px;font-size:0.75rem;color:#6b7280;font-weight:600;">Order #</th>
                        <th style="text-align:left;padding:12px 16px;font-size:0.75rem;color:#6b7280;font-weight:600;">Customer</th>
                        <th style="text-align:left;padding:12px 16px;font-size:0.75rem;color:#6b7280;font-weight:600;">Date</th>
                        <th style="text-align:left;padding:12px 16px;font-size:0.75rem;color:#6b7280;font-weight:600;">Total</th>
                        <th style="text-align:left;padding:12px 16px;font-size:0.75rem;color:#6b7280;font-weight:600;">Status</th>
                        <th style="text-align:left;padding:12px 16px;font-size:0.75rem;color:#6b7280;font-weight:600;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recent as $order)
                        @php $ostatus = $order->status ?? 'pending'; @endphp
                        <tr style="border-bottom:1px solid #f3f4f6;">
                            <td style="padding:12px 16px;font-size:0.875rem;">#{{ $order->displayNumber() }}</td>
                            <td style="padding:12px 16px;font-size:0.875rem;">{{ $order->customer_name }}</td>
                            <td style="padding:12px 16px;font-size:0.875rem;">{{ $order->created_at?->format('M j, Y H:i') }}</td>
                            <td style="padding:12px 16px;font-size:0.875rem;font-weight:600;">{{ $price::format($order->total) }}</td>
                            <td style="padding:12px 16px;">
                                <span class="badge status-badge" style="{{ $badgeStyles[$ostatus] ?? 'background:#f3f4f6;color:#374151' }};padding:4px 10px;border-radius:6px;font-size:0.75rem;font-weight:600;">{{ $statusLabels[$ostatus] ?? ucfirst(str_replace('_', ' ', $ostatus)) }}</span>
                            </td>
                            <td class="actions-cell" style="padding:12px 16px;">
                                <button type="button" class="actions-btn orders-actions-btn" title="Actions">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                    </svg>
                                </button>
                                <div class="actions-dropdown">
                                    <button type="button" class="view-order-btn actions-dropdown-item" data-order-id="{{ $order->id }}">View</button>
                                    <div class="actions-dropdown-divider"></div>
                                    <div class="actions-dropdown-title">Change Status</div>
                                    @foreach($statuses as $s)
                                        @if($ostatus !== $s)
                                            <form method="post" action="{{ route('manager.orders.status', $order) }}" style="display:contents;">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="{{ $s }}">
                                                <button type="submit" class="actions-dropdown-item">Set to {{ $statusLabels[$s] }}</button>
                                            </form>
                                        @endif
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</section>

<div id="order-modal" class="order-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;padding:24px;">
    <div class="order-modal-content" style="background:#fff;border-radius:12px;max-width:560px;width:100%;max-height:90vh;overflow-y:auto;padding:24px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
            <h3 id="order-modal-title" style="font-size:1.25rem;font-weight:600;color:#111827;">Order Details</h3>
            <button type="button" id="order-modal-close" style="background:0;border:0;cursor:pointer;padding:4px;color:#6b7280;">&times;</button>
        </div>
        <div id="order-modal-body"></div>
    </div>
</div>
</div>

@if(!empty($showUpgradeOverlay))
    <div style="position:relative;margin-top:-40vh;z-index:20;">
        <div style="display:flex;align-items:center;justify-content:center;padding:48px 24px;text-align:center;">
            <div class="settings-card" style="max-width:420px;padding:32px;">
                <h2 style="margin:0 0 12px;font-size:1.25rem;">Upgrade required</h2>
                <p style="color:#4b5563;margin:0 0 16px;">{{ $upgradeMessage }}</p>
                <a href="{{ route('manager.billing.index') }}" class="btn btn-primary">View plans & upgrade</a>
            </div>
        </div>
    </div>
@endif
@endsection

@push('scripts')
<script>
(function() {
    const symbol = @json($currencySymbol);
    const statusColors = @json($statusColors);
    const analyticsUrl = @json(url('/api/orders/analytics'));

    function loadRevenueChart(range) {
        const wrapper = document.getElementById('revenue-chart-wrapper');
        const chartEl = document.getElementById('revenue-line-chart');
        const emptyEl = document.getElementById('revenue-chart-empty');
        const svgEl = document.getElementById('revenue-svg');
        const tooltipEl = document.getElementById('revenue-tooltip');
        const trendEl = document.getElementById('revenue-trend');
        fetch(analyticsUrl + '?range=' + encodeURIComponent(range || 'all'), { credentials: 'same-origin' })
            .then(r => r.json())
            .then(function(data) {
                if (!data.success || !data.revenue_by_date) return;
                const rows = data.revenue_by_date || [];
                if (rows.length === 0) {
                    chartEl.style.display = 'none';
                    emptyEl.style.display = 'block';
                    trendEl.innerHTML = '';
                    return;
                }
                emptyEl.style.display = 'none';
                chartEl.style.display = 'block';
                const revenues = rows.map(function(r) { return parseFloat(r.revenue) || 0; });
                const maxRev = Math.max.apply(null, revenues) || 1;
                const minRev = Math.min.apply(null, revenues);
                const rangeRev = maxRev - minRev || 1;
                const chartW = 800; const chartH = 280;
                const padL = 48; const padR = 24; const padT = 24; const padB = 40;
                const plotW = chartW - padL - padR;
                const plotH = chartH - padT - padB;
                const firstVal = revenues[0] || 0;
                const lastVal = revenues[revenues.length - 1] || 0;
                const isGrowth = lastVal >= firstVal;
                const lineColor = isGrowth ? '#059669' : '#DC2626';
                const gradientId = 'revGrad-' + (isGrowth ? 'up' : 'down');
                const pts = rows.map(function(r, i) {
                    const val = parseFloat(r.revenue) || 0;
                    const y = padT + plotH - ((val - minRev) / rangeRev) * plotH;
                    const x = padL + (rows.length > 1 ? (i / (rows.length - 1)) * plotW : plotW / 2);
                    return { x: x, y: y, val: val, date: r.date };
                });
                const pathD = pts.map(function(p, i) { return (i === 0 ? 'M' : 'L') + p.x + ' ' + p.y; }).join(' ');
                const areaD = pathD + ' L' + (padL + plotW) + ' ' + (padT + plotH) + ' L' + padL + ' ' + (padT + plotH) + ' Z';
                const yTicks = 5;
                let html = '<defs><linearGradient id="' + gradientId + '" x1="0%" y1="0%" x2="0%" y2="100%">' +
                    '<stop offset="0%" style="stop-color:' + lineColor + ';stop-opacity:0.25"/>' +
                    '<stop offset="100%" style="stop-color:' + lineColor + ';stop-opacity:0"/></linearGradient></defs>';
                for (var g = 0; g <= yTicks; g++) {
                    var gy = padT + (g / yTicks) * plotH;
                    var gval = (maxRev - (g / yTicks) * (maxRev - minRev)).toFixed(0);
                    html += '<line x1="' + padL + '" y1="' + gy + '" x2="' + (padL + plotW) + '" y2="' + gy + '" stroke="#e5e7eb" stroke-width="1"/>';
                    html += '<text x="' + (padL - 8) + '" y="' + (gy + 4) + '" text-anchor="end" font-size="10" fill="#6b7280">' + symbol + gval + '</text>';
                }
                html += '<path d="' + areaD + '" fill="url(#' + gradientId + ')"/>';
                html += '<path d="' + pathD + '" fill="none" stroke="' + lineColor + '" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>';
                for (var i = 0; i < pts.length; i++) {
                    var p = pts[i];
                    var d = p.date ? new Date(p.date + 'T12:00:00').toLocaleDateString(undefined, { month: 'short', day: 'numeric', year: pts.length > 14 ? '2-digit' : undefined }) : p.date;
                    html += '<circle class="revenue-point" cx="' + p.x + '" cy="' + p.y + '" r="6" fill="' + lineColor + '" stroke="#fff" stroke-width="2" opacity="0.9" data-date="' + (d || '') + '" data-rev="' + symbol + p.val.toFixed(2) + '"/>';
                }
                var step = Math.max(1, Math.floor(pts.length / 6));
                for (var xi = 0; xi < pts.length; xi += step) {
                    var px = pts[xi];
                    var dx = px.date ? new Date(px.date + 'T12:00:00').toLocaleDateString(undefined, { month: 'short', day: 'numeric' }) : '';
                    html += '<text x="' + px.x + '" y="' + (chartH - 8) + '" text-anchor="middle" font-size="10" fill="#6b7280">' + dx + '</text>';
                }
                svgEl.innerHTML = html;
                var trendText = '';
                if (rows.length >= 2 && firstVal > 0) {
                    var pct = ((lastVal - firstVal) / firstVal * 100).toFixed(1);
                    trendText = (parseFloat(pct) >= 0 ? '+' : '') + pct + '% vs first day';
                } else if (rows.length >= 1) {
                    trendText = symbol + lastVal.toFixed(2) + ' total';
                }
                trendEl.innerHTML = isGrowth ? '<span style="color:#059669">&#9650;</span> ' + trendText : '<span style="color:#DC2626">&#9660;</span> ' + trendText;
                function showTooltip(el, e) {
                    var rect = wrapper.getBoundingClientRect();
                    tooltipEl.innerHTML = '<strong>' + (el.getAttribute('data-date') || '') + '</strong><br/>Revenue: ' + (el.getAttribute('data-rev') || '');
                    tooltipEl.style.display = 'block';
                    tooltipEl.style.left = Math.min(e.clientX - rect.left + 12, rect.width - 140) + 'px';
                    tooltipEl.style.top = Math.max(e.clientY - rect.top - 50, 8) + 'px';
                }
                function hideTooltip() { tooltipEl.style.display = 'none'; }
                wrapper.querySelectorAll('.revenue-point').forEach(function(el) {
                    el.addEventListener('mouseenter', function(e) { showTooltip(el, e); });
                    el.addEventListener('mouseleave', hideTooltip);
                    el.addEventListener('mousemove', function(e) { showTooltip(el, e); });
                });
            })
            .catch(function() {
                document.getElementById('revenue-line-chart').style.display = 'none';
                emptyEl.style.display = 'block';
                trendEl.innerHTML = '';
            });
    }

    document.querySelectorAll('.revenue-range-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.revenue-range-btn').forEach(function(b) { b.classList.remove('btn-active'); });
            this.classList.add('btn-active');
            loadRevenueChart(this.getAttribute('data-range'));
        });
    });
    loadRevenueChart('all');

    const modal = document.getElementById('order-modal');
    const modalBody = document.getElementById('order-modal-body');
    const modalTitle = document.getElementById('order-modal-title');
    const modalClose = document.getElementById('order-modal-close');

    function closeModal() { modal.style.display = 'none'; }
    if (modalClose) modalClose.onclick = closeModal;
    if (modal) modal.onclick = function(e) { if (e.target === modal) closeModal(); };

    function esc(s) {
        if (s == null || s === '') return '';
        return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#39;');
    }

    document.querySelectorAll('.view-order-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const orderId = this.getAttribute('data-order-id');
            fetch('/api/orders/' + encodeURIComponent(orderId), { credentials: 'same-origin' })
                .then(r => r.json())
                .then(data => {
                    if (!data.success) return;
                    const o = data.order;
                    const items = o.items || [];
                    const st = o.status || 'pending';
                    const statusClr = statusColors[st] || '#6b7280';
                    const statusLabel = (st.charAt(0).toUpperCase() + st.slice(1)).replace('_', ' ');
                    const itemsHtml = items.map(function(i) {
                        const qty = parseInt(i.quantity, 10) || 1;
                        const price = parseFloat(i.price || 0);
                        return '<tr><td>' + esc(i.name) + '</td><td style="text-align:center;">' + qty + '</td><td style="text-align:right;">' + symbol + price.toFixed(2) + '</td><td style="text-align:right;">' + symbol + (price * qty).toFixed(2) + '</td></tr>';
                    }).join('');
                    const phoneVal = (o.customer_phone || '').replace(/\s/g, '');
                    const phoneDisplay = esc(o.customer_phone) || '-';
                    const phoneLink = phoneVal ? '<a href="tel:' + esc(phoneVal) + '" class="detail-link">' + phoneDisplay + '</a>' : phoneDisplay;
                    const emailDisplay = (o.customer_email && o.customer_email.trim()) ? '<a href="mailto:' + esc(o.customer_email) + '" class="detail-link">' + esc(o.customer_email) + '</a>' : '-';
                    modalTitle.textContent = 'Order #' + (o.order_display_number || orderId);
                    modalBody.innerHTML = '<div class="detail-modal">' +
                        '<div class="detail-modal-section"><h4 class="detail-modal-heading">Customer Information</h4>' +
                        '<div class="detail-modal-grid">' +
                        '<div class="detail-modal-item"><span class="detail-label">Name</span><span class="detail-value">' + (esc(o.customer_name) || '-') + '</span></div>' +
                        '<div class="detail-modal-item"><span class="detail-label">Phone</span><span class="detail-value">' + phoneLink + '</span></div>' +
                        '<div class="detail-modal-item"><span class="detail-label">Email</span><span class="detail-value">' + emailDisplay + '</span></div>' +
                        '<div class="detail-modal-item"><span class="detail-label">Address</span><span class="detail-value">' + (esc(o.delivery_address) || '-') + '</span></div>' +
                        '<div class="detail-modal-item"><span class="detail-label">Status</span><span class="detail-badge" style="background:' + statusClr + '22;color:' + statusClr + '">' + esc(statusLabel) + '</span></div>' +
                        '</div></div>' +
                        '<div class="detail-modal-section"><h4 class="detail-modal-heading">Order Items</h4>' +
                        '<table class="detail-items-table" style="width:100%;border-collapse:collapse;"><thead><tr style="border-bottom:1px solid #e5e7eb;"><th style="text-align:left;padding:8px 12px;font-size:0.7rem;color:#6b7280;font-weight:600;">Item</th><th style="text-align:center;padding:8px 12px;font-size:0.7rem;color:#6b7280;">Qty</th><th style="text-align:right;padding:8px 12px;font-size:0.7rem;color:#6b7280;">Price</th><th style="text-align:right;padding:8px 12px;font-size:0.7rem;color:#6b7280;">Total</th></tr></thead><tbody>' + itemsHtml + '</tbody></table></div>' +
                        '<div class="detail-modal-section detail-modal-footer"><div class="detail-modal-item"><span class="detail-label">Total</span><span class="detail-value detail-total">' + symbol + parseFloat(o.total || 0).toFixed(2) + '</span></div></div></div>';
                    modal.style.display = 'flex';
                })
                .catch(function() { alert('Failed to load order details.'); });
        });
    });
})();
</script>
@endpush
