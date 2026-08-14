@extends('layouts.admin')
@section('title', 'Subscriptions')
@section('content')
@include('partials.admin.page-header', ['title' => 'Subscriptions Management', 'subtitle' => 'View and manage restaurant subscription status'])

@php
  $totalCount = array_sum($statusCounts);
@endphp

<div class="status-stats">
  <a href="{{ route('admin.subscriptions.index', request()->except('status', 'page')) }}" class="status-stat {{ $statusFilter === '' ? 'active' : '' }}">
    <div class="status-stat-icon all">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" /></svg>
    </div>
    <div class="status-stat-info">
      <span class="status-stat-count">{{ $totalCount }}</span>
      <span class="status-stat-label">All</span>
    </div>
  </a>
  @foreach(['trial' => 'trial', 'active' => 'active-status', 'expired' => 'expired', 'cancelled' => 'cancelled', 'pending' => 'pending'] as $st => $iconClass)
    <a href="{{ route('admin.subscriptions.index', array_merge(request()->except('page'), ['status' => $st])) }}" class="status-stat {{ $statusFilter === $st ? 'active' : '' }}">
      <div class="status-stat-icon {{ $iconClass }}">
        @if($st === 'trial')
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        @elseif($st === 'active')
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        @elseif($st === 'expired')
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
        @else
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        @endif
      </div>
      <div class="status-stat-info">
        <span class="status-stat-count">{{ $statusCounts[$st] ?? 0 }}</span>
        <span class="status-stat-label">{{ ucfirst($st) }}</span>
      </div>
    </a>
  @endforeach
</div>

<form method="get" class="filters-bar">
  @if($statusFilter)<input type="hidden" name="status" value="{{ $statusFilter }}">@endif
  @if($planFilter)<input type="hidden" name="plan_id" value="{{ $planFilter }}">@endif
  <div class="search-box"><input type="text" name="q" value="{{ $search }}" placeholder="Search by restaurant name..."></div>
  <button type="submit" class="btn-search">Search</button>
  @if($search || $statusFilter || $planFilter)
    <a href="{{ route('admin.subscriptions.index') }}" class="btn-clear">Clear</a>
  @endif
</form>

@if($restaurantsWithoutSubscription->isNotEmpty())
  <div class="admin-list-card" style="margin-bottom:20px;">
    <div class="table-card">
      <h3 style="margin:0 0 12px;font-size:1rem;">Restaurants with no subscription</h3>
      <p style="margin:0 0 16px;color:#6b7280;font-size:0.875rem;">These restaurants never received a trial. Assign one so they appear in the list below and can use billing.</p>
      <table class="subscriptions-table">
        <thead>
          <tr><th>Restaurant</th><th>Assign trial</th></tr>
        </thead>
        <tbody>
        @foreach($restaurantsWithoutSubscription as $restaurant)
          <tr>
            <td>
              <div class="restaurant-info">
                <span class="restaurant-name">{{ $restaurant->name }}</span>
                <span class="restaurant-slug">{{ $restaurant->slug }}</span>
              </div>
            </td>
            <td>
              <form method="post" action="{{ route('admin.subscriptions.store') }}" style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
                @csrf
                <input type="hidden" name="restaurant_id" value="{{ $restaurant->id }}">
                <select name="plan_id" class="form-input" style="min-width:160px;">
                  @foreach($plans as $plan)
                    <option value="{{ $plan->id }}" @selected($plan->slug === 'professional')>{{ $plan->name }}</option>
                  @endforeach
                </select>
                <button type="submit" class="btn-filter">Start 7-day trial</button>
              </form>
            </td>
          </tr>
        @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endif

<div class="admin-list-card">
  <div class="table-card">
    @if($subscriptions->isEmpty())
      <div class="empty-state" style="padding:40px;text-align:center;color:#6b7280">No subscriptions found.</div>
    @else
      <table class="subscriptions-table">
        <thead>
          <tr><th>Restaurant</th><th>Plan</th><th>Status</th><th>Billing</th><th>Period</th><th>Actions</th></tr>
        </thead>
        <tbody>
        @foreach($subscriptions as $s)
          @php
            $price = ($s->billing_cycle === 'annual') ? ($s->plan->annual_price ?? 0) : ($s->plan->monthly_price ?? 0);
            $periodEnd = $s->current_period_end?->format('M j, Y') ?? 'N/A';
            $trialEnd = $s->trial_ends_at?->format('M j, Y') ?? 'N/A';
          @endphp
          <tr>
            <td>
              <div class="restaurant-info">
                <span class="restaurant-name">{{ $s->restaurant->name ?? $s->restaurant_id }}</span>
                <span class="restaurant-slug">{{ $s->restaurant->slug ?? '' }}</span>
              </div>
            </td>
            <td><span class="plan-badge">{{ $s->plan->name ?? $s->plan_id }}</span></td>
            <td><span class="status-badge status-{{ $s->status }}">{{ ucfirst($s->status) }}</span></td>
            <td>
              <div class="billing-info">
                <div class="billing-cycle">{{ ucfirst($s->billing_cycle ?? 'monthly') }}</div>
                <div class="billing-price">₦{{ number_format((float) $price, 0) }}</div>
              </div>
            </td>
            <td>
              <div class="date-info">
                @if($s->status === 'trial')
                  <span class="date-label">Trial ends:</span><br>{{ $trialEnd }}
                @elseif($s->status === 'expired')
                  <span class="date-label">Ended:</span><br>{{ $s->current_period_end ? $periodEnd : ($s->trial_ends_at ? $trialEnd : 'N/A') }}
                @else
                  <span class="date-label">Renews:</span><br>{{ $periodEnd }}
                @endif
              </div>
            </td>
            <td>
              @php
                $subItems = [['type' => 'title', 'label' => 'Change Status']];
                foreach (['trial', 'active', 'expired', 'cancelled', 'pending'] as $st) {
                  if ($st !== $s->status) {
                    $subItems[] = [
                      'type' => 'form',
                      'label' => 'Set to '.ucfirst($st),
                      'action' => route('admin.subscriptions.update', $s),
                      'method' => 'PATCH',
                      'hidden' => ['action' => 'update_status', 'new_status' => $st],
                    ];
                  }
                }
                $subItems[] = ['type' => 'divider'];
                $subItems[] = ['type' => 'title', 'label' => 'Change Plan'];
                foreach ($plans as $plan) {
                  if ($plan->id != $s->plan_id) {
                    $subItems[] = [
                      'type' => 'form',
                      'label' => $plan->name,
                      'action' => route('admin.subscriptions.update', $s),
                      'method' => 'PATCH',
                      'hidden' => ['action' => 'change_plan', 'new_plan_id' => $plan->id],
                      'confirm' => 'Change subscription plan to '.$plan->name.'?',
                    ];
                  }
                }
                $subItems[] = ['type' => 'divider'];
                $subItems[] = ['type' => 'title', 'label' => 'Extend Period'];
                foreach ([7, 30, 90, 365] as $days) {
                  $subItems[] = [
                    'type' => 'form',
                    'label' => '+ '.$days.' days',
                    'action' => route('admin.subscriptions.update', $s),
                    'method' => 'PATCH',
                    'hidden' => ['action' => 'extend_period', 'days' => $days],
                  ];
                }
                $subItems[] = ['type' => 'divider'];
                if ($s->restaurant) {
                  $subItems[] = ['label' => 'View Restaurant', 'url' => route('admin.restaurants.hub', $s->restaurant)];
                  $subItems[] = ['label' => 'View Payments', 'url' => route('admin.payments.index', ['restaurant_id' => $s->restaurant_id])];
                }
              @endphp
              @include('partials.admin.actions-dropdown', ['items' => $subItems])
            </td>
          </tr>
        @endforeach
        </tbody>
      </table>
    @endif
  </div>
  @if($subscriptions->hasPages())
    {{ $subscriptions->links() }}
  @endif
</div>
@endsection
@push('head')
<link rel="stylesheet" href="{{ asset('legacy/css/pages/admin-subscriptions.css') }}">
@endpush
