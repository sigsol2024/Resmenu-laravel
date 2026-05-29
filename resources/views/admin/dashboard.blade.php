@extends('layouts.admin')
@section('title', 'Dashboard')
@push('head')
<link rel="stylesheet" href="{{ asset('assets/css/admin-dashboard.css') }}">
@endpush
@section('content')
@include('partials.admin.page-header', ['title' => 'Dashboard', 'subtitle' => 'Overview of your restaurant platform'])

<section class="stats">
  <div class="stat-card"><div class="stat-label">Restaurants</div><div class="stat-value">{{ $stats['restaurants'] }}</div></div>
  <div class="stat-card"><div class="stat-label">Categories</div><div class="stat-value">{{ $stats['categories'] }}</div></div>
  <div class="stat-card"><div class="stat-label">Menu Items</div><div class="stat-value">{{ $stats['menu_items'] }}</div></div>
  <div class="stat-card"><div class="stat-label">Managers</div><div class="stat-value">{{ $stats['managers'] }}</div></div>
  <div class="stat-card"><div class="stat-label">Total Revenue (All Restaurants)</div><div class="stat-value">₦{{ number_format($stats['total_revenue'], 0) }}</div></div>
</section>

<section class="chart-card">
  <h2 class="chart-title">Statistics Overview</h2>
  <div class="simple-bar-chart">
    @foreach($chartData as $item)
      <div class="item" style="--clr: {{ $item['color'] }}; --val: {{ round($item['percentage'], 1) }}">
        <div class="label">{{ $item['label'] }}</div>
        <div class="value">{{ number_format($item['value']) }}</div>
      </div>
    @endforeach
  </div>
</section>

<section class="list-card">
  <div class="list-card-header"><h2 class="list-card-title">Recent Restaurants</h2></div>
  @forelse($recentRestaurants as $restaurant)
    <div class="restaurant" onclick="toggleRestaurant(this)">
      <div class="restaurant-header">
        <div class="restaurant-info">
          <span class="restaurant-name">{{ $restaurant->name }}</span>
          <span class="restaurant-slug">{{ $restaurant->slug }}</span>
          @if($restaurant->created_at)
            <span class="restaurant-date">Created: {{ $restaurant->created_at->format('M d, Y g:i A') }}</span>
          @endif
        </div>
        <span class="restaurant-toggle">▼</span>
      </div>
      <div class="restaurant-body">
        <div class="actions">
          <a href="{{ route('admin.restaurants.show', $restaurant) }}" class="btn-manage">Manage</a>
          <a href="{{ route('public.menu', $restaurant->slug) }}" target="_blank" class="btn-view">View Menu</a>
        </div>
      </div>
    </div>
  @empty
    <div class="empty-state">
      <p>No restaurants found.</p>
      <p><a href="{{ route('admin.restaurants.index') }}">View all restaurants</a></p>
    </div>
  @endforelse
</section>
@endsection
@push('scripts')
<script>
function toggleRestaurant(el){ el.classList.toggle('open'); }
</script>
@endpush
