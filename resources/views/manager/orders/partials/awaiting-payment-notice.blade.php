@if(($awaitingPayment ?? 0) > 0)
    <div style="display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:12px;padding:14px 16px;margin-bottom:16px;border-radius:8px;background:#fffbeb;border:1px solid #fcd34d;">
        <div style="font-size:0.875rem;color:#92400e;">
            <strong>{{ $awaitingPayment }} {{ $awaitingPayment === 1 ? 'order is' : 'orders are' }} waiting for you to confirm a bank transfer.</strong>
            These orders appear here once you approve the payment.
        </div>
        <a href="{{ route('manager.bank-transfers.index') }}" class="btn btn-primary" style="padding:8px 16px;font-size:0.875rem;white-space:nowrap;">Review payments</a>
    </div>
@endif
