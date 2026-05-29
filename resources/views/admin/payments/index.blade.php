@extends('layouts.admin')
@section('title', 'Payments')
@push('head')
<link rel="stylesheet" href="{{ asset('assets/css/admin-payments.css') }}">
@endpush
@section('content')
@include('partials.admin.page-header', ['title' => 'Payments', 'subtitle' => 'Subscription payment history'])

<div class="payment-stats">
  <div class="stat-card"><div class="stat-label">Total successful</div><div class="stat-value success">₦{{ number_format($totalSuccess, 0) }}</div></div>
  <div class="stat-card"><div class="stat-label">Total pending</div><div class="stat-value pending">₦{{ number_format($totalPending, 0) }}</div></div>
</div>

<form method="get" class="filters-bar">
  <div class="filters-row">
    <div class="filter-group"><label>Status</label>
      <select name="status"><option value="">All</option>
        @foreach(['pending','success','failed','refunded'] as $s)<option value="{{ $s }}" @selected($statusFilter===$s)>{{ ucfirst($s) }}</option>@endforeach
      </select></div>
    <div class="filter-group"><label>Gateway</label>
      <select name="gateway"><option value="">All</option>
        @foreach(['paystack','flutterwave','manual'] as $g)<option value="{{ $g }}" @selected($gatewayFilter===$g)>{{ ucfirst($g) }}</option>@endforeach
      </select></div>
    <div class="filter-group"><label>Restaurant</label>
      <select name="restaurant_id"><option value="0">All</option>
        @foreach($restaurants as $r)<option value="{{ $r->id }}" @selected($restaurantFilter==$r->id)>{{ $r->name }}</option>@endforeach
      </select></div>
    <div class="filter-group"><label>From</label><input type="date" name="date_from" value="{{ $dateFrom }}"></div>
    <div class="filter-group"><label>To</label><input type="date" name="date_to" value="{{ $dateTo }}"></div>
    <button type="submit" class="btn-filter">Filter</button>
    <a href="{{ route('admin.payments.index') }}" class="btn-clear">Clear</a>
  </div>
</form>

<div class="admin-list-card">
  <div class="table-card">
    <table class="data-table">
      <thead><tr><th>ID</th><th>Restaurant</th><th>Plan</th><th>Amount</th><th>Gateway</th><th>Reference</th><th>Status</th><th>Date</th><th>Actions</th></tr></thead>
      <tbody>
      @forelse($payments as $p)
        @php
          $createdAt = \Illuminate\Support\Carbon::parse($p->created_at);
          $payItems = [['type' => 'title', 'label' => 'Change Status']];
          foreach (['pending', 'success', 'failed', 'refunded'] as $st) {
            if ($st !== $p->status) {
              $payItems[] = [
                'type' => 'form',
                'label' => 'Mark as '.ucfirst($st),
                'action' => route('admin.payments.store'),
                'hidden' => ['action' => 'update_status', 'payment_id' => $p->id, 'new_status' => $st],
              ];
            }
          }
        @endphp
        <tr>
          <td>{{ $p->id }}</td>
          <td>{{ $p->restaurant_name }}</td>
          <td>{{ $p->plan_name ?? '—' }}</td>
          <td>₦{{ number_format($p->amount, 0) }}</td>
          <td>{{ ucfirst($p->payment_gateway) }}</td>
          <td class="mono">{{ $p->transaction_reference }}</td>
          <td><span class="status-badge status-{{ $p->status }}">{{ ucfirst($p->status) }}</span></td>
          <td>
            <div class="date-info">
              {{ $createdAt->format('M j, Y') }}<br>
              {{ $createdAt->format('g:i A') }}
            </div>
          </td>
          <td>@include('partials.admin.actions-dropdown', ['items' => $payItems])</td>
        </tr>
      @empty
        <tr><td colspan="9" style="text-align:center;padding:40px;color:#6b7280">No payments found.</td></tr>
      @endforelse
      </tbody>
    </table>
  </div>
  @if($payments->hasPages())
    <div style="padding:16px 24px">{{ $payments->links() }}</div>
  @endif
</div>

<details class="manual-payment-panel admin-list-card" style="margin-top:24px;">
  <summary>Record manual payment</summary>
  <form method="post" action="{{ route('admin.payments.store') }}" class="manual-form">@csrf
    <input type="hidden" name="action" value="create_manual">
    <div class="filters-row">
      <div class="filter-group"><label>Restaurant</label>
        <select name="restaurant_id" required>@foreach($restaurants as $r)<option value="{{ $r->id }}">{{ $r->name }}</option>@endforeach</select>
      </div>
      <div class="filter-group"><label>Subscription ID</label><input type="number" name="subscription_id" required min="1"></div>
      <div class="filter-group"><label>Amount</label><input type="number" name="amount" step="0.01" required min="0.01"></div>
      <div class="filter-group"><label>Status</label>
        <select name="status"><option value="success">success</option><option value="pending">pending</option></select>
      </div>
      <button type="submit" class="btn-filter">Record payment</button>
    </div>
  </form>
</details>
@endsection
