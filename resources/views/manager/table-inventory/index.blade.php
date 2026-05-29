@extends('layouts.manager')
@section('title', 'Table Inventory')
@push('head')
<link rel="stylesheet" href="{{ asset('legacy/css/pages/manager-table-inventory.css') }}">
@endpush
@section('content')
<p style="margin-bottom:8px;">
    <a href="{{ route('manager.reservations.index') }}" class="btn btn-secondary" style="font-size:0.8rem;display:inline-flex;align-items:center;gap:6px;">← Back to Reservations</a>
</p>
<div class="page-header">
    <h1 class="page-title">Table Inventory</h1>
    <p class="page-subtitle">Manage daily table availability for {{ $restaurant->name }}</p>
</div>

<div class="settings-card" style="padding:24px;margin-bottom:24px;">
    <h3 style="font-size:0.875rem;font-weight:600;margin:0 0 12px;color:#374151;">Bulk Update</h3>
    <div style="display:flex;flex-wrap:wrap;gap:12px;align-items:flex-end;margin-bottom:24px;padding:16px;background:#f9fafb;border-radius:8px;border:1px solid #e5e7eb;">
        <div><label style="display:block;font-size:0.75rem;color:#6b7280;margin-bottom:4px;">Start Date</label><input type="date" id="bulk-start-date" class="form-input"/></div>
        <div><label style="display:block;font-size:0.75rem;color:#6b7280;margin-bottom:4px;">End Date</label><input type="date" id="bulk-end-date" class="form-input"/></div>
        <div><label style="display:block;font-size:0.75rem;color:#6b7280;margin-bottom:4px;">Total Tables</label><input type="number" id="bulk-total-tables" min="1" max="999" value="10" style="width:80px;" class="form-input"/></div>
        <button type="button" id="bulk-save-btn" class="btn btn-primary">Bulk Update</button>
        <button type="button" id="bulk-fill-month-btn" class="btn btn-secondary">Fill Entire Month</button>
        <span id="bulk-status" role="status" style="font-size:0.875rem;display:none;"></span>
    </div>
    <div style="display:flex;flex-wrap:wrap;justify-content:space-between;align-items:center;gap:16px;margin-bottom:20px;">
        <h3 style="font-size:1rem;font-weight:600;margin:0;" id="inventory-month-title">—</h3>
        <div style="display:flex;gap:8px;">
            <button type="button" id="inv-prev-month" class="btn btn-secondary">Previous</button>
            <button type="button" id="inv-next-month" class="btn btn-secondary">Next</button>
        </div>
    </div>
    <div id="inventory-calendar" class="inventory-calendar" style="display:grid;grid-template-columns:repeat(7,1fr);gap:8px;"></div>
</div>

<div id="inventory-day-panel" class="settings-card" style="padding:24px;display:none;">
    <h3 style="font-size:1rem;font-weight:600;">Day Details: <span id="day-panel-date"></span></h3>
    <div class="inventory-day-stats" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(120px,1fr));gap:16px;margin:20px 0;"></div>
    <div style="display:flex;flex-wrap:wrap;gap:16px;align-items:flex-end;margin-bottom:20px;">
        <div><label style="font-size:0.75rem;color:#6b7280;">Total Tables</label><input type="number" id="day-total-tables" min="1" max="999" value="10" class="form-input"/></div>
        <button type="button" id="day-save-total" class="btn btn-primary">Save Total</button>
        <span id="day-save-status" style="display:none;font-size:0.875rem;"></span>
    </div>
    <button type="button" id="day-add-walkin" class="btn btn-secondary" style="margin-bottom:16px;">Add Walk-in</button>
    <div id="day-reservations-list" style="font-size:0.875rem;color:#6b7280;">Loading...</div>
</div>

<div id="walkin-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;padding:24px;">
    <div style="background:#fff;border-radius:12px;max-width:400px;width:100%;padding:24px;">
        <h3 style="margin:0 0 16px;">Add Walk-in Guest</h3>
        <input type="text" id="walkin-guest-name" value="Walk-in" class="form-input w-full" style="margin-bottom:16px;"/>
        <div style="display:flex;gap:8px;justify-content:flex-end;">
            <button type="button" id="walkin-modal-cancel" class="btn btn-secondary">Cancel</button>
            <button type="button" id="walkin-modal-confirm" class="btn btn-primary">Add Walk-in</button>
        </div>
    </div>
</div>

<script>
window.TABLE_INVENTORY_CONFIG = {
    apiBase: @json($apiBase),
    csrfToken: @json(csrf_token()),
};
</script>
<script src="{{ asset('assets/js/table-inventory.js') }}"></script>
@endsection
