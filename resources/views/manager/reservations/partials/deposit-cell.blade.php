@if((float) ($r->deposit_amount ?? 0) <= 0)
    <span class="cell-muted">None</span>
@elseif($r->deposit_paid)
    <span class="status-pill status-pill--paid">Paid</span>
@else
    <span class="status-pill status-pill--pending">Awaiting payment</span>
@endif
