@extends('layouts.admin')
@section('title', 'Dashboard')
@push('head')
<link rel="stylesheet" href="{{ asset('legacy/css/pages/admin-dashboard.css') }}">
@endpush
@section('content')
<!-- Page Header -->
<div class="page-header">
    <h1 class="page-title">Dashboard</h1>
    <p class="page-subtitle">Overview of your restaurant platform</p>
</div>

<!-- STATS -->
<section class="stats">
  <div class="stat-card">
    <div class="stat-label">Restaurants</div>
    <div class="stat-value">{{ $stats['restaurants'] }}</div>
  </div>
  <div class="stat-card">
    <div class="stat-label">Categories</div>
    <div class="stat-value">{{ $stats['categories'] }}</div>
  </div>
  <div class="stat-card">
    <div class="stat-label">Menu Items</div>
    <div class="stat-value">{{ $stats['menu_items'] }}</div>
  </div>
  <div class="stat-card">
    <div class="stat-label">Managers</div>
    <div class="stat-value">{{ $stats['managers'] }}</div>
  </div>
  <div class="stat-card">
    <div class="stat-label">Total Revenue (All Restaurants)</div>
    <div class="stat-value">₦{{ number_format($stats['total_revenue'], 0) }}</div>
  </div>
</section>

<!-- BAR CHART -->
@if(!empty($chartData))
<section class="chart-card">
  <h2 class="chart-title">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
    </svg>
    Statistics Overview
  </h2>
  <div class="simple-bar-chart gradient-bars">
    @foreach($chartData as $item)
      <div class="item" style="--clr: {{ $item['color'] }}; --val: {{ round($item['percentage'], 1) }}">
        <div class="label">{{ $item['label'] }}</div>
        <div class="value">{{ number_format($item['value']) }}</div>
      </div>
    @endforeach
  </div>
</section>
@endif

<!-- RESTAURANT LIST -->
<section class="list-card">
  <div class="list-card-header">
    <h2 class="list-card-title">Recent Restaurants</h2>
  </div>
  @forelse($recentRestaurants as $restaurant)
    <div class="restaurant" onclick="toggleRestaurant(this)">
      <div class="restaurant-header">
        <div class="restaurant-info">
          <span class="restaurant-name">{{ $restaurant->name }}</span>
          <span class="restaurant-slug">{{ $restaurant->slug }}</span>
          @if($restaurant->created_at)
            <span class="restaurant-date" style="font-size: 0.75rem; color: #6b7280; margin-top: 4px;">
              Created: {{ $restaurant->created_at->format('M d, Y g:i A') }}
            </span>
          @endif
        </div>
        <span class="restaurant-toggle">▼</span>
      </div>
      <div class="restaurant-body">
        <div class="actions-cell">
          <button class="actions-btn" type="button" title="Actions">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
            </svg>
          </button>
          <div class="actions-dropdown">
            <a href="{{ route('admin.restaurants.hub', $restaurant) }}" class="actions-dropdown-item">Manage</a>
            <a href="{{ route('admin.restaurants.edit', $restaurant) }}" class="actions-dropdown-item">Edit</a>
            <a href="{{ route('public.menu', $restaurant->slug) }}" target="_blank" class="actions-dropdown-item">View Menu</a>
          </div>
        </div>
      </div>
    </div>
  @empty
    <div class="empty-state">
      <p>No restaurants found.</p>
      <p>
        <a href="{{ route('admin.restaurants.index', ['new' => 1]) }}">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          Create your first restaurant
        </a>
      </p>
    </div>
  @endforelse
</section>
@endsection
@push('scripts')
<script>
function toggleRestaurant(el){
  el.classList.toggle('open');
}
</script>
@endpush
