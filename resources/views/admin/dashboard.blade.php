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
      <div class="item{{ $item['value'] > 0 ? ' item-has-value' : '' }}" style="--clr: {{ $item['color'] }}; --val: {{ round($item['percentage'], 1) }}">
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
  @if($recentRestaurants->isNotEmpty())
    <div class="restaurant-list-head">
      <span>Name</span>
      <span>Slug</span>
      <span>Created</span>
      <span>Actions</span>
    </div>
  @endif
  @forelse($recentRestaurants as $restaurant)
    <div class="restaurant" onclick="toggleRestaurantMobile(event, this)">
      <div class="restaurant-header">
        <span class="restaurant-name">{{ $restaurant->name }}</span>
        <span class="restaurant-slug">{{ $restaurant->slug }}</span>
        @if($restaurant->created_at)
          <span class="restaurant-date">Created: {{ $restaurant->created_at->format('M d, Y g:i A') }}</span>
        @else
          <span class="restaurant-date">—</span>
        @endif
        <div class="restaurant-actions" onclick="event.stopPropagation()">
          @include('partials.admin.actions-dropdown', [
            'items' => [
              ['label' => 'Manage', 'url' => route('admin.restaurants.hub', $restaurant)],
              ['label' => 'Edit', 'url' => route('admin.restaurants.edit', $restaurant)],
              ['label' => 'View Menu', 'url' => route('public.menu', $restaurant->slug), 'target' => '_blank', 'rel' => 'noopener'],
            ],
            'stopPropagation' => false,
          ])
        </div>
        <span class="restaurant-toggle" aria-hidden="true">▼</span>
      </div>
      <div class="restaurant-body" onclick="event.stopPropagation()">
        @include('partials.admin.actions-dropdown', [
          'items' => [
            ['label' => 'Manage', 'url' => route('admin.restaurants.hub', $restaurant)],
            ['label' => 'Edit', 'url' => route('admin.restaurants.edit', $restaurant)],
            ['label' => 'View Menu', 'url' => route('public.menu', $restaurant->slug), 'target' => '_blank', 'rel' => 'noopener'],
          ],
          'stopPropagation' => false,
        ])
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
function toggleRestaurantMobile(event, el) {
  if (window.innerWidth > 768) return;
  if (event.target.closest('.actions-cell, .actions-btn, .actions-dropdown, a, button')) return;
  el.classList.toggle('open');
}
</script>
@endpush
