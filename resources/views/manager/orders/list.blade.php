@extends('layouts.manager')
@section('title', 'All orders')
@push('head')
<link rel="stylesheet" href="{{ asset('legacy/css/pages/manager-restaurant-orders.css') }}">
@endpush
@section('content')
<div class="page-header">
    <h1 class="page-title">All Orders</h1>
    <p class="page-subtitle">Full order list with filters for {{ $restaurant->name ?? 'your restaurant' }}</p>
</div>
<p style="margin-bottom:20px;">
    <a href="{{ route('manager.orders.index') }}" class="btn btn-secondary">Back to Orders Overview</a>
</p>
<div class="card" style="margin-bottom:20px;">
<form method="get" style="display:flex;flex-wrap:wrap;gap:12px;align-items:end;">
    <div class="form-group" style="margin:0;">
        <label for="start_date">From</label>
        <input type="date" name="start_date" id="start_date" value="{{ $filters['start_date'] ?? '' }}">
    </div>
    <div class="form-group" style="margin:0;">
        <label for="end_date">To</label>
        <input type="date" name="end_date" id="end_date" value="{{ $filters['end_date'] ?? '' }}">
    </div>
    <div class="form-group" style="margin:0;">
        <label for="status">Status</label>
        <select name="status" id="status">
            <option value="all">All</option>
            @foreach($statuses as $s)
                <option value="{{ $s }}" @selected(($filters['status'] ?? 'all') === $s)>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Filter</button>
</form>
</div>
<div class="card">
<div class="table-responsive">
<table class="table">
    <thead><tr><th>Order</th><th>Customer</th><th>Phone</th><th>Total</th><th>Status</th><th>Date</th><th class="text-right">Change status</th></tr></thead>
    <tbody>
    @forelse($orders as $order)
        <tr>
            <td>{{ $order->displayNumber() }}</td>
            <td>{{ $order->customer_name }}</td>
            <td>{{ $order->customer_phone }}</td>
            <td>{{ $price::format($order->total) }}</td>
            <td><span class="status-pill status-pill--{{ $order->status }}">{{ str_replace('_', ' ', $order->status) }}</span></td>
            <td>{{ $order->created_at?->format('M j, Y H:i') }}</td>
            <td class="text-right">
                <form method="post" action="{{ route('manager.orders.status', $order) }}" style="display:inline;">
                    @csrf @method('PATCH')
                    <input type="hidden" name="return_to" value="list">
                    <select name="status" onchange="this.form.submit()" style="max-width:140px;padding:6px 8px;border:1px solid #d1d5db;border-radius:8px;">
                        @foreach($statuses as $s)
                            <option value="{{ $s }}" @selected($order->status === $s)>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                        @endforeach
                    </select>
                </form>
            </td>
        </tr>
    @empty
        <tr><td colspan="7" class="table-empty">No orders match your filters.</td></tr>
    @endforelse
    </tbody>
</table>
</div>
{{ $orders->links() }}
</div>
@endsection
