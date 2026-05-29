@extends('layouts.admin')
@section('title', 'Payments')
@push('head')
<link rel="stylesheet" href="{{ asset('legacy/css/pages/admin-payments.css') }}">
@endpush
@section('content')
<!-- Page Header -->
<div class="page-header">
    <h1 class="page-title">Payments</h1>
    <p class="page-subtitle">View subscription payment history and transactions</p>
</div>

<!-- Stats -->
<div class="payment-stats">
    <div class="stat-card">
        <div class="stat-label">Total Successful</div>
        <div class="stat-value success">₦{{ number_format($totalSuccess, 0) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Pending</div>
        <div class="stat-value pending">₦{{ number_format($totalPending, 0) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Total Transactions</div>
        <div class="stat-value">{{ number_format($totalCount) }}</div>
    </div>
</div>

<!-- Filters -->
<form class="filters-bar" method="GET" action="{{ route('admin.payments.index') }}">
    <div class="filters-row">
        <div class="filter-group">
            <label>Status</label>
            <select name="status">
                <option value="">All Statuses</option>
                @foreach(['pending','success','failed','refunded'] as $s)
                    <option value="{{ $s }}" @selected($statusFilter === $s)>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-group">
            <label>Gateway</label>
            <select name="gateway">
                <option value="">All Gateways</option>
                @foreach(['paystack','flutterwave','manual'] as $g)
                    <option value="{{ $g }}" @selected($gatewayFilter === $g)>{{ ucfirst($g) }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-group">
            <label>Restaurant</label>
            <select name="restaurant_id">
                <option value="">All Restaurants</option>
                @foreach($restaurants as $r)
                    <option value="{{ $r->id }}" @selected($restaurantFilter == $r->id)>{{ $r->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-group">
            <label>From Date</label>
            <input type="date" name="date_from" value="{{ $dateFrom }}">
        </div>
        <div class="filter-group">
            <label>To Date</label>
            <input type="date" name="date_to" value="{{ $dateTo }}">
        </div>
        <div class="filter-actions">
            <button type="submit" class="btn-filter">Filter</button>
            <a href="{{ route('admin.payments.index') }}" class="btn-clear">Clear</a>
        </div>
        <button type="button" class="btn-add" onclick="openManualPaymentModal()">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="16" height="16">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Manual Payment
        </button>
    </div>
</form>

<!-- Payments Table -->
<div class="table-card">
    @if($payments->isEmpty())
        <div class="empty-state">
            <p>No payments found.</p>
        </div>
    @else
        <table class="payments-table">
            <thead>
                <tr>
                    <th>Restaurant</th>
                    <th>Amount</th>
                    <th>Gateway</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @foreach($payments as $p)
                @php $createdAt = \Illuminate\Support\Carbon::parse($p->created_at); @endphp
                <tr>
                    <td>
                        <div class="restaurant-info">
                            <span class="restaurant-name">{{ $p->restaurant_name }}</span>
                            <span class="transaction-ref">{{ $p->transaction_reference ?? 'N/A' }}</span>
                        </div>
                    </td>
                    <td>
                        <span class="amount">₦{{ number_format((float) $p->amount, 0) }}</span>
                    </td>
                    <td>
                        <span class="gateway-badge gateway-{{ $p->payment_gateway }}">{{ ucfirst($p->payment_gateway) }}</span>
                    </td>
                    <td>
                        <span class="status-badge status-{{ $p->status }}">{{ ucfirst($p->status) }}</span>
                    </td>
                    <td>
                        <div class="date-info">
                            {{ $createdAt->format('M j, Y') }}<br>
                            {{ $createdAt->format('g:i A') }}
                        </div>
                    </td>
                    <td class="actions-cell">
                        <button class="actions-btn" type="button" title="Actions">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                            </svg>
                        </button>
                        <div class="actions-dropdown">
                            <div class="actions-dropdown-title">Change Status</div>
                            @foreach(['pending','success','failed','refunded'] as $st)
                                @if($st !== $p->status)
                                    <form method="POST" action="{{ route('admin.payments.store') }}" style="display: contents;">
                                        @csrf
                                        <input type="hidden" name="action" value="update_status">
                                        <input type="hidden" name="payment_id" value="{{ $p->id }}">
                                        <input type="hidden" name="new_status" value="{{ $st }}">
                                        <button type="submit" class="actions-dropdown-item">Mark as {{ ucfirst($st) }}</button>
                                    </form>
                                @endif
                            @endforeach
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @if($payments->hasPages())
            <div class="pagination">{{ $payments->links() }}</div>
        @endif
    @endif
</div>

<!-- Manual Payment Modal -->
<div class="modal" id="manualPaymentModal" style="display: none;">
    <div class="modal-overlay" onclick="closeManualPaymentModal()"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Record Manual Payment</h2>
            <button class="modal-close" onclick="closeManualPaymentModal()" aria-label="Close">&times;</button>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{ route('admin.payments.store') }}">
                @csrf
                <input type="hidden" name="action" value="create_manual">
                <div class="form-group">
                    <label class="form-label" for="manual_restaurant_id">Restaurant</label>
                    <select id="manual_restaurant_id" name="restaurant_id" class="form-select" required>
                        @foreach($restaurants as $r)
                            <option value="{{ $r->id }}">{{ $r->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="subscription_id">Subscription ID</label>
                    <input type="number" id="subscription_id" name="subscription_id" class="form-input" required min="1">
                </div>
                <div class="form-group">
                    <label class="form-label" for="amount">Amount (₦)</label>
                    <input type="number" id="amount" name="amount" class="form-input" step="0.01" required min="0.01">
                </div>
                <div class="form-group">
                    <label class="form-label" for="manual_status">Status</label>
                    <select id="manual_status" name="status" class="form-select">
                        <option value="success">Success</option>
                        <option value="pending">Pending</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeManualPaymentModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Record Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
function openManualPaymentModal() {
    document.getElementById('manualPaymentModal').style.display = 'flex';
}
function closeManualPaymentModal() {
    document.getElementById('manualPaymentModal').style.display = 'none';
}
</script>
@endpush
