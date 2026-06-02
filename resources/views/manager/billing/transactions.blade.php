@extends('layouts.manager')

@section('title', 'Transactions')

@push('head')
<link rel="stylesheet" href="{{ asset('legacy/css/pages/manager-transaction-history.css') }}">
<link rel="stylesheet" href="{{ resmenu_public_asset('css/pages/manager-billing.css') }}">
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">Transaction History</h1>
    <p class="page-subtitle">View your subscription payment history</p>
</div>
<div class="card">
    <table class="table">
        <thead><tr><th>Date</th><th>Plan</th><th>Amount</th><th>Status</th></tr></thead>
        <tbody>
        @forelse($transactions as $tx)
            <tr>
                <td>{{ \Illuminate\Support\Carbon::parse($tx['created_at'])->format('M j, Y g:i A') }}</td>
                <td>{{ $tx['plan_name'] ?? 'Subscription' }}</td>
                <td>{{ $formatPrice($tx['amount'] ?? 0, $tx['currency'] ?? 'NGN') }}</td>
                <td><span class="payment-status {{ $tx['display']['css_class'] ?? $tx['status'] }}">{{ $tx['display']['label'] ?? ucfirst($tx['status']) }}</span></td>
            </tr>
        @empty
            <tr><td colspan="4">No transactions yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
