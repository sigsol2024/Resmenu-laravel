@extends('layouts.manager')

@section('title', 'Bank Transfers')
@push('head')
<link rel="stylesheet" href="{{ asset('legacy/css/pages/manager-orders.css') }}">
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">Pending bank transfers</h1>
    <p class="page-subtitle">Orders paid by bank transfer appear here until you confirm the money arrived. Approving creates the order and emails the customer; rejecting cancels it.</p>
</div>

<div class="card orders-list">
    <div class="table-wrapper">
    <table class="table">
        <thead>
            <tr>
                <th>Reference</th>
                <th>Customer</th>
                <th>Type</th>
                <th>Date</th>
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
                $type = $draft->payment_type ?? 'order';
                $rejectConfirm = $type === 'reservation'
                    ? 'Reject this transfer? The guest will be emailed that their deposit could not be confirmed, with a link to pay again.'
                    : 'Reject this bank transfer? The order will not be created.';
                $details = [
                    'reference' => $ref,
                    'type' => $type,
                    'status' => $claimed ? 'Customer says payment sent' : 'Awaiting customer payment',
                    'customer_name' => $draft->customer_name,
                    'customer_email' => $draft->customer_email,
                    'customer_phone' => $draft->customer_phone,
                    'delivery_address' => $type === 'reservation' ? '' : $draft->delivery_address,
                    'created_at' => \Illuminate\Support\Carbon::parse($draft->created_at)->format('M j, Y H:i'),
                    'claimed_at' => $draft->customer_claimed_at ? \Illuminate\Support\Carbon::parse($draft->customer_claimed_at)->format('M j, Y H:i') : '',
                    'subtotal' => (float) $draft->subtotal,
                    'delivery_fee' => (float) $draft->delivery_fee,
                    'tax' => (float) $draft->tax,
                    'total' => (float) $draft->total,
                    'items' => $draftLines[$draft->id] ?? [],
                    'approve_url' => route('manager.bank-transfers.approve', $draft->id),
                    'reject_url' => route('manager.bank-transfers.reject', $draft->id),
                    'reject_confirm' => $rejectConfirm,
                ];
            @endphp
            <tr>
                <td style="font-family:ui-monospace,Consolas,monospace;">#{{ $ref }}</td>
                <td>
                    <strong>{{ $draft->customer_name }}</strong>
                    <span class="cell-muted">{{ $draft->customer_email }}</span>
                </td>
                <td style="text-transform:capitalize;">{{ $type }}</td>
                <td>{{ \Illuminate\Support\Carbon::parse($draft->created_at)->format('M j, Y H:i') }}</td>
                <td><strong>₦{{ number_format((float) $draft->total, 2) }}</strong></td>
                <td>
                    <span class="status-pill status-pill--{{ $claimed ? 'customer_claimed' : 'pending' }}">{{ $claimed ? 'Customer claimed' : 'Pending' }}</span>
                </td>
                <td class="actions-cell text-right">
                    <button type="button" class="actions-btn" title="Actions" aria-label="Actions">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                        </svg>
                    </button>
                    <div class="actions-dropdown">
                        <button type="button" class="actions-dropdown-item bt-view-btn" data-details='@json($details)'>View {{ $type === 'reservation' ? 'deposit' : 'order' }}</button>
                        <div class="actions-dropdown-divider"></div>
                        <form method="post" action="{{ $details['approve_url'] }}" style="display:contents;">
                            @csrf
                            <button type="submit" class="actions-dropdown-item">Approve payment</button>
                        </form>
                        <form method="post" action="{{ $details['reject_url'] }}" style="display:contents;" onsubmit="return confirm(@js($rejectConfirm));">
                            @csrf
                            <button type="submit" class="actions-dropdown-item" style="color:#dc2626;">Reject</button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr><td colspan="7" class="table-empty">No pending bank transfers.</td></tr>
        @endforelse
        </tbody>
    </table>
    </div>
</div>

<div id="bt-modal" class="order-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;padding:24px;">
    <div class="order-modal-content" style="background:#fff;border-radius:12px;max-width:560px;width:100%;max-height:90vh;overflow-y:auto;padding:24px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
            <h3 id="bt-modal-title" style="font-size:1.25rem;font-weight:600;color:#111827;margin:0;">Bank transfer</h3>
            <button type="button" id="bt-modal-close" aria-label="Close" style="background:0;border:0;cursor:pointer;padding:4px;color:#6b7280;font-size:1.5rem;line-height:1;">&times;</button>
        </div>
        <div id="bt-modal-body"></div>
        <div style="display:flex;gap:12px;justify-content:flex-end;margin-top:20px;">
            <form id="bt-modal-reject" method="post" action="">
                @csrf
                <button type="submit" class="btn btn-danger">Reject</button>
            </form>
            <form id="bt-modal-approve" method="post" action="">
                @csrf
                <button type="submit" class="btn btn-primary">Approve payment</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    var modal = document.getElementById('bt-modal');
    var body = document.getElementById('bt-modal-body');
    var title = document.getElementById('bt-modal-title');
    var approveForm = document.getElementById('bt-modal-approve');
    var rejectForm = document.getElementById('bt-modal-reject');
    var rejectConfirm = '';

    function esc(s) {
        if (s == null) return '';
        return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#39;');
    }
    function money(n) {
        return '₦' + (parseFloat(n) || 0).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }
    function item(label, value) {
        return '<div class="detail-modal-item"><span class="detail-label">' + label + '</span><span class="detail-value">' + (value || '-') + '</span></div>';
    }
    function close() { modal.style.display = 'none'; }

    document.getElementById('bt-modal-close').addEventListener('click', close);
    modal.addEventListener('click', function (e) { if (e.target === modal) close(); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') close(); });
    rejectForm.addEventListener('submit', function (e) { if (!confirm(rejectConfirm)) e.preventDefault(); });

    document.querySelectorAll('.bt-view-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            if (window.ResmenuActionsDropdown) window.ResmenuActionsDropdown.close();
            var d = JSON.parse(this.getAttribute('data-details'));
            var phone = (d.customer_phone || '').replace(/\s/g, '');
            var rows = (d.items || []).map(function (i) {
                return '<tr><td>' + esc(i.name) + '</td><td style="text-align:center;">' + i.quantity + '</td><td style="text-align:right;">' + money(i.price) + '</td><td style="text-align:right;">' + money(i.price * i.quantity) + '</td></tr>';
            }).join('');
            var isOrder = d.type !== 'reservation';

            title.textContent = (isOrder ? 'Order' : 'Reservation deposit') + ' #' + d.reference;
            body.innerHTML = '<div class="detail-modal">' +
                '<div class="detail-modal-section"><h4 class="detail-modal-heading">Customer</h4><div class="detail-modal-grid">' +
                    item('Name', esc(d.customer_name)) +
                    item('Phone', phone ? '<a class="detail-link" href="tel:' + esc(phone) + '">' + esc(d.customer_phone) + '</a>' : '') +
                    item('Email', d.customer_email ? '<a class="detail-link" href="mailto:' + esc(d.customer_email) + '">' + esc(d.customer_email) + '</a>' : '') +
                    (isOrder ? item('Delivery address', esc(d.delivery_address)) : '') +
                '</div></div>' +
                '<div class="detail-modal-section"><h4 class="detail-modal-heading">Payment</h4><div class="detail-modal-grid">' +
                    item('Status', '<span class="detail-badge" style="background:#fef3c7;color:#92400e;">' + esc(d.status) + '</span>') +
                    item('Placed', esc(d.created_at)) +
                    item('Customer marked paid', esc(d.claimed_at)) +
                '</div></div>' +
                (isOrder ? '<div class="detail-modal-section"><h4 class="detail-modal-heading">Items ordered</h4>' +
                    '<table class="detail-items-table" style="width:100%;border-collapse:collapse;"><thead><tr style="border-bottom:1px solid #e5e7eb;">' +
                    '<th style="text-align:left;padding:8px 12px;font-size:0.7rem;color:#6b7280;">Item</th><th style="text-align:center;padding:8px 12px;font-size:0.7rem;color:#6b7280;">Qty</th>' +
                    '<th style="text-align:right;padding:8px 12px;font-size:0.7rem;color:#6b7280;">Price</th><th style="text-align:right;padding:8px 12px;font-size:0.7rem;color:#6b7280;">Total</th></tr></thead>' +
                    '<tbody>' + (rows || '<tr><td colspan="4">No items recorded.</td></tr>') + '</tbody></table></div>' : '') +
                '<div class="detail-modal-section detail-modal-footer"><div class="detail-modal-grid">' +
                    (isOrder ? item('Subtotal', money(d.subtotal)) + item('Delivery', money(d.delivery_fee)) + item('Tax', money(d.tax)) : '') +
                    '<div class="detail-modal-item"><span class="detail-label">Amount to confirm</span><span class="detail-value detail-total">' + money(d.total) + '</span></div>' +
                '</div></div>' +
            '</div>';

            approveForm.action = d.approve_url;
            rejectForm.action = d.reject_url;
            rejectConfirm = d.reject_confirm;
            modal.style.display = 'flex';
        });
    });
})();
</script>
@endpush
