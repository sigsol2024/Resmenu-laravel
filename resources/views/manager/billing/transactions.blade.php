@extends('layouts.manager')

@section('title', 'Transactions')

@push('head')
<link rel="stylesheet" href="{{ asset('legacy/css/pages/manager-transaction-history.css') }}">
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">Transaction History</h1>
    <p class="page-subtitle">View your subscription payment history</p>
</div>
<div class="card">
    <table class="table">
        <thead><tr><th>Date</th><th>Amount</th><th>Status</th></tr></thead>
        <tbody>
        @forelse($transactions as $tx)
            <tr><td>{{ $tx->created_at }}</td><td>₦{{ number_format($tx->amount ?? 0, 0) }}</td><td>{{ $tx->status ?? '-' }}</td></tr>
        @empty
            <tr><td colspan="3">No transactions yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
