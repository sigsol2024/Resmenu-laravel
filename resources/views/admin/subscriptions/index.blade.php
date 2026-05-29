@extends('layouts.admin')
@section('title', 'Subscriptions')
@section('content')
@include('partials.admin.page-header', ['title' => 'Subscriptions', 'subtitle' => 'Manage restaurant subscriptions'])

<form method="get" class="filters-bar card" style="padding:16px;margin-bottom:20px;">
  <div class="filters-row">
    <div class="filter-group"><label>Status</label>
      <select name="status"><option value="">All</option>
        @foreach(['trial','active','expired','cancelled','pending'] as $st)
          <option value="{{ $st }}" @selected($statusFilter===$st)>{{ ucfirst($st) }}</option>
        @endforeach
      </select>
    </div>
    <div class="filter-group"><label>Plan</label>
      <select name="plan_id"><option value="0">All</option>
        @foreach($plans as $p)<option value="{{ $p->id }}" @selected($planFilter==$p->id)>{{ $p->name }}</option>@endforeach
      </select>
    </div>
    <div class="filter-group"><label>Search</label><input type="text" name="q" value="{{ $search }}" placeholder="Restaurant name"></div>
    <button type="submit" class="btn-filter">Filter</button>
  </div>
</form>

<div class="card table-card">
<table class="data-table">
  <thead><tr><th>Restaurant</th><th>Plan</th><th>Status</th><th>Cycle</th><th>Trial ends</th><th>Period end</th><th>Actions</th></tr></thead>
  <tbody>
  @foreach($subscriptions as $s)
    <tr>
      <td><a href="{{ route('admin.restaurants.show', $s->restaurant_id) }}">{{ $s->restaurant->name ?? $s->restaurant_id }}</a></td>
      <td>{{ $s->plan->name ?? $s->plan_id }}</td>
      <td><span class="badge badge-{{ $s->status }}">{{ $s->status }}</span></td>
      <td>{{ $s->billing_cycle }}</td>
      <td>{{ $s->trial_ends_at?->format('Y-m-d') ?? '—' }}</td>
      <td>{{ $s->current_period_end?->format('Y-m-d') ?? '—' }}</td>
      <td>
        <form method="post" action="{{ route('admin.subscriptions.update', $s) }}" class="sub-actions">@csrf @method('PATCH')
          <select name="status">
            @foreach(['trial','active','expired','cancelled','pending'] as $st)
              <option value="{{ $st }}" @selected($s->status===$st)>{{ $st }}</option>
            @endforeach
          </select>
          <select name="plan_id">
            @foreach($plans as $p)<option value="{{ $p->id }}" @selected($s->plan_id==$p->id)>{{ $p->name }}</option>@endforeach
          </select>
          <select name="billing_cycle">
            <option value="monthly" @selected($s->billing_cycle==='monthly')>monthly</option>
            <option value="annual" @selected($s->billing_cycle==='annual')>annual</option>
          </select>
          <button type="submit">Update</button>
        </form>
        <form method="post" action="{{ route('admin.subscriptions.update', $s) }}" class="sub-extend">@csrf @method('PATCH')
          <input type="hidden" name="action" value="extend_period">
          <input type="number" name="days" value="7" min="1" max="365" style="width:60px">
          <button type="submit">Extend days</button>
        </form>
      </td>
    </tr>
  @endforeach
  </tbody>
</table>
{{ $subscriptions->links() }}
</div>
@endsection
@push('head')
<link rel="stylesheet" href="{{ asset('assets/css/admin-payments.css') }}">
<style>.sub-actions,.sub-extend{display:flex;flex-wrap:wrap;gap:6px;margin-bottom:6px;align-items:center;font-size:0.8rem}</style>
@endpush
