@extends('layouts.manager')
@section('title', 'Orders')
@section('content')
@if(!empty($showUpgradeOverlay))
<div class="card" style="margin-bottom:24px;border:2px solid #f59e0b;background:#fffbeb;">
    <h2 style="margin:0 0 8px;">Upgrade required</h2>
    <p style="margin:0 0 12px;color:#4b5563;">{{ $upgradeMessage }}</p>
    <a href="{{ route('manager.billing') }}" class="btn btn-primary">View plans</a>
</div>
<div style="filter:blur(3px);opacity:0.45;pointer-events:none;">
@endif
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
    <h1 style="margin:0;">Orders</h1>
    <a href="{{ route('manager.orders.list') }}" class="btn btn-primary">View all orders</a>
</div>
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(120px,1fr));gap:12px;margin-bottom:24px;">
    @foreach($stats as $status => $count)
        <div class="card"><strong>{{ $count }}</strong><br><span style="color:#6b7280;font-size:0.8rem;">{{ \App\Services\OrderService::statusLabel($status) }}</span></div>
    @endforeach
</div>
<div class="card" style="margin-bottom:24px;">
    <strong>Revenue (active orders):</strong> {{ $price::format($revenue) }}
    <span style="color:#6b7280;margin-left:8px;">({{ $totalCount }} total)</span>
</div>
<div class="card">
    <h2 style="margin:0 0 16px;font-size:1rem;">Recent orders</h2>
    <table>
        <thead><tr><th>Order</th><th>Customer</th><th>Total</th><th>Status</th><th>Date</th></tr></thead>
        <tbody>
        @forelse($recent as $order)
            <tr>
                <td>{{ $order->displayNumber() }}</td>
                <td>{{ $order->customer_name }}</td>
                <td>{{ $price::format($order->total) }}</td>
                <td>{{ \App\Services\OrderService::statusLabel($order->status) }}</td>
                <td>{{ $order->created_at?->format('M j, Y H:i') }}</td>
            </tr>
        @empty
            <tr><td colspan="5">No orders yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@if(!empty($showUpgradeOverlay))</div>@endif
@endsection
