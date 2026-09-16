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
                                        @if($st === 'success')
                                            <input type="hidden" name="note" value="Manual admin confirmation of payment #{{ $p->id }}">
                                        @endif
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
            {{ $payments->links() }}
        @endif
    @endif
</div>

<!-- Manual Payment Modal -->
<div class="modal" id="manualPaymentModal" aria-hidden="true">
    <div class="modal-overlay" onclick="closeManualPaymentModal()"></div>
    <div class="modal-content" role="dialog" aria-modal="true" aria-labelledby="manualPaymentTitle">
        <div class="modal-header">
            <h2 class="modal-title" id="manualPaymentTitle">Record Manual Payment</h2>
            <button class="modal-close" type="button" onclick="closeManualPaymentModal()" aria-label="Close">&times;</button>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{ route('admin.payments.store') }}" id="manualPaymentForm">
                @csrf
                <input type="hidden" name="action" value="create_manual">
                <div class="form-group">
                    <label class="form-label" for="manual_restaurant_id">Restaurant</label>
                    <select id="manual_restaurant_id" name="restaurant_id" class="form-select" required>
                        <option value="">Select restaurant</option>
                        @foreach($restaurants as $r)
                            <option value="{{ $r->id }}" @selected($restaurantFilter == $r->id)>{{ $r->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="current-sub-box" id="manualCurrentSub">
                    Select a restaurant to see the current subscription.
                </div>
                <div class="form-group">
                    <label class="form-label" for="manual_plan_id">New Plan</label>
                    <select id="manual_plan_id" name="plan_id" class="form-select" required>
                        <option value="">Select plan</option>
                        @foreach($plans as $plan)
                            <option value="{{ $plan['id'] }}">{{ $plan['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="manual_billing_cycle">Billing Cycle</label>
                    <select id="manual_billing_cycle" name="billing_cycle" class="form-select" required>
                        <option value="monthly">Monthly</option>
                        <option value="annual">Annual</option>
                    </select>
                </div>
                <div class="quote-box" id="manualQuoteBox">
                    Choose restaurant, plan, and billing cycle to see the amount payable.
                </div>
                <div class="form-group">
                    <label class="form-label" for="manual_status">Payment Status</label>
                    <select id="manual_status" name="status" class="form-select">
                        <option value="success">Success</option>
                        <option value="pending">Pending</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeManualPaymentModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="manualSubmitBtn" disabled>Record Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
(function () {
    const quoteUrl = @json(route('admin.payments.quote'));
    const openManual = @json(!empty($openManual));
    const preselectRestaurantId = @json($restaurantFilter > 0 ? $restaurantFilter : null);

    const restaurantSelect = document.getElementById('manual_restaurant_id');
    const planSelect = document.getElementById('manual_plan_id');
    const cycleSelect = document.getElementById('manual_billing_cycle');
    const quoteBox = document.getElementById('manualQuoteBox');
    const currentBox = document.getElementById('manualCurrentSub');
    const submitBtn = document.getElementById('manualSubmitBtn');
    const modal = document.getElementById('manualPaymentModal');
    let quoteTimer = null;

    // Keep overlay on <body> so layout parents cannot pin it to the page bottom.
    if (modal && modal.parentElement !== document.body) {
        document.body.appendChild(modal);
    }

    window.openManualPaymentModal = function openManualPaymentModal(restaurantId) {
        if (!modal) return;
        if (modal.parentElement !== document.body) {
            document.body.appendChild(modal);
        }
        if (restaurantId) {
            restaurantSelect.value = String(restaurantId);
        }
        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
        refreshManualQuote();
    };

    window.closeManualPaymentModal = function closeManualPaymentModal() {
        if (!modal) return;
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    };

    function setSubmitEnabled(enabled, label) {
        submitBtn.disabled = !enabled;
        if (label) {
            submitBtn.textContent = label;
        }
    }

    function refreshManualQuote() {
        const restaurantId = restaurantSelect.value;
        const planId = planSelect.value;
        const cycle = cycleSelect.value;

        if (!restaurantId) {
            quoteBox.className = 'quote-box';
            quoteBox.textContent = 'Choose restaurant, plan, and billing cycle to see the amount payable.';
            currentBox.textContent = 'Select a restaurant to see the current subscription.';
            setSubmitEnabled(false, 'Record Payment');
            return;
        }

        if (!planId || !cycle) {
            quoteBox.className = 'quote-box';
            quoteBox.textContent = 'Choose a plan and billing cycle to see the amount payable.';
            currentBox.textContent = 'Select a plan to load subscription details.';
            setSubmitEnabled(false, 'Record Payment');
            return;
        }

        quoteBox.className = 'quote-box';
        quoteBox.textContent = 'Calculating…';
        setSubmitEnabled(false, 'Record Payment');

        const params = new URLSearchParams({
            restaurant_id: restaurantId,
            plan_id: planId,
            billing_cycle: cycle,
        });

        fetch(quoteUrl + '?' + params.toString(), {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then(r => {
                if (!r.ok) throw new Error('Quote failed');
                return r.json();
            })
            .then(data => {
                renderCurrent(data.current);

                if (data.outcome === 'charge') {
                    quoteBox.className = 'quote-box';
                    quoteBox.innerHTML = '<div>' + (data.message || 'Amount payable') + '</div>'
                        + '<div class="quote-amount">' + (data.formatted_amount || ('₦' + Number(data.amount || 0).toLocaleString())) + '</div>';
                    setSubmitEnabled(true, Number(data.amount || 0) > 0 ? 'Record Payment' : 'Apply Plan Change');
                    return;
                }

                if (data.outcome === 'schedule_downgrade') {
                    quoteBox.className = 'quote-box';
                    quoteBox.textContent = data.message || 'Downgrade will be scheduled at period end. No payment now.';
                    setSubmitEnabled(true, 'Schedule Downgrade');
                    return;
                }

                quoteBox.className = 'quote-box quote-error';
                quoteBox.textContent = data.message || 'This plan change is not allowed.';
                setSubmitEnabled(false, 'Record Payment');
            })
            .catch(() => {
                quoteBox.className = 'quote-box quote-error';
                quoteBox.textContent = 'Unable to calculate amount. Please try again.';
                setSubmitEnabled(false, 'Record Payment');
            });
    }

    function renderCurrent(current) {
        if (!current) {
            currentBox.textContent = 'No current subscription for this restaurant.';
            return;
        }
        const parts = [
            'Current: ' + (current.plan_name || 'Unknown plan'),
            (current.billing_cycle || 'monthly'),
            (current.status_label || current.status || ''),
        ];
        if (current.period_end) {
            parts.push('Period ends ' + current.period_end);
        }
        currentBox.textContent = parts.filter(Boolean).join(' · ');
    }

    function queueQuote() {
        clearTimeout(quoteTimer);
        quoteTimer = setTimeout(refreshManualQuote, 150);
    }

    restaurantSelect.addEventListener('change', queueQuote);
    planSelect.addEventListener('change', queueQuote);
    cycleSelect.addEventListener('change', queueQuote);

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && modal && modal.classList.contains('is-open')) {
            closeManualPaymentModal();
        }
    });

    if (openManual) {
        openManualPaymentModal(preselectRestaurantId);
    }
})();
</script>
@endpush
