@extends('layouts.manager')

@section('title', 'Bank Transfers')

@section('content')
<div class="page-header">
    <h1 class="page-title">Pending bank transfers</h1>
    <p class="page-subtitle">Review customer bank transfer claims and approve or reject them. Guests are only emailed a confirmation after you approve.</p>
</div>

<div class="card">
    <div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>Reference</th>
                <th>Customer</th>
                <th>Type</th>
                <th>Amount</th>
                <th>Status</th>
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($drafts as $draft)
            @php
                $ref = 'BT-'.strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $draft->token), 0, 8));
                $claimed = ($draft->status ?? 'pending') === 'customer_claimed';
            @endphp
            <tr>
                <td style="font-family:ui-monospace,Consolas,monospace;">#{{ $ref }}</td>
                <td>
                    <strong>{{ $draft->customer_name }}</strong>
                    <span class="cell-muted">{{ $draft->customer_email }}</span>
                </td>
                <td style="text-transform:capitalize;">{{ $draft->payment_type ?? 'order' }}</td>
                <td><strong>₦{{ number_format((float) $draft->total, 2) }}</strong></td>
                <td>
                    <span class="status-pill status-pill--{{ $claimed ? 'customer_claimed' : 'pending' }}">{{ $claimed ? 'Customer claimed' : 'Pending' }}</span>
                </td>
                <td class="text-right">
                    <div class="table-actions">
                        <form method="post" action="{{ route('manager.bank-transfers.approve', $draft->id) }}">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-small">Approve</button>
                        </form>
                        <form method="post" action="{{ route('manager.bank-transfers.reject', $draft->id) }}" onsubmit="return confirm(@js(($draft->payment_type ?? 'order') === 'reservation' ? 'Reject this transfer? The guest will be emailed that their deposit could not be confirmed, with a link to pay again.' : 'Reject this bank transfer?'));">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-small">Reject</button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="table-empty">No pending bank transfers.</td></tr>
        @endforelse
        </tbody>
    </table>
    </div>
</div>
@endsection
