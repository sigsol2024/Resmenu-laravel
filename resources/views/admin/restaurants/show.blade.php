@extends('layouts.admin')
@section('title', $restaurant->name)
@section('content')
@include('partials.admin.page-header', ['title' => $restaurant->name, 'subtitle' => '/'.$restaurant->slug])

@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:16px;margin-bottom:24px;">
  <div class="card">
    <h3 style="margin:0 0 12px;font-size:1rem;">Overview</h3>
    <p><strong>Status:</strong> {{ $restaurant->is_active ? 'Active' : 'Inactive' }}</p>
    <p><strong>Template:</strong> #{{ $restaurant->template_id }}</p>
    <p><strong>Food ordering:</strong> {{ $restaurant->enable_food_ordering ? 'On' : 'Off' }}</p>
    <p><strong>Reservations:</strong> {{ $restaurant->enable_table_reservations ? 'On' : 'Off' }}</p>
    @if($manager)
      <p><strong>Manager:</strong> {{ $manager->username }} ({{ $manager->email }})</p>
    @endif
  </div>
  <div class="card">
    <h3 style="margin:0 0 12px;font-size:1rem;">Menu content</h3>
    <p>Sections: <strong>{{ $stats['sections'] }}</strong></p>
    <p>Categories: <strong>{{ $stats['categories'] }}</strong></p>
    <p>Menu items: <strong>{{ $stats['menu_items'] }}</strong></p>
  </div>
  <div class="card">
    <h3 style="margin:0 0 12px;font-size:1rem;">Activity</h3>
    <p>Orders: <strong>{{ $stats['orders'] }}</strong></p>
    <p>Reservations: <strong>{{ $stats['reservations'] }}</strong></p>
    @if($subscription)
      <p><strong>Plan:</strong> {{ $subscription->plan->name ?? $subscription->plan_id }}</p>
      <p><strong>Status:</strong> {{ $subscription->status }} ({{ $subscription->billing_cycle }})</p>
      @if($subscription->trial_ends_at)
        <p>Trial ends: {{ $subscription->trial_ends_at->format('M j, Y') }}</p>
      @endif
      @if($subscription->current_period_end)
        <p>Period ends: {{ $subscription->current_period_end->format('M j, Y') }}</p>
      @endif
    @else
      <p class="text-muted">No subscription</p>
    @endif
  </div>
  <div class="card">
    <h3 style="margin:0 0 12px;font-size:1rem;">Contact</h3>
    <p>{{ $restaurant->email ?? 'â€”' }}</p>
    <p>{{ $restaurant->phone ?? 'â€”' }}</p>
    <p style="font-size:0.875rem;color:#6b7280;">{{ $restaurant->address }}</p>
  </div>
</div>

@if($customization)
<div class="card" style="margin-bottom:24px;">
  <h3 style="margin:0 0 12px;font-size:1rem;">Customization (template {{ $restaurant->template_id }})</h3>
  <p style="font-size:0.875rem;">
    Primary: <span style="display:inline-block;width:16px;height:16px;background:{{ $customization->primary_color }};vertical-align:middle;border:1px solid #ccc;"></span>
    Background: <span style="display:inline-block;width:16px;height:16px;background:{{ $customization->background_color }};vertical-align:middle;border:1px solid #ccc;"></span>
  </p>
</div>
@endif

<div class="actions" style="display:flex;flex-wrap:wrap;gap:8px;">
  <a href="{{ route('admin.restaurants.hub', $restaurant) }}" class="btn-manage">Manage menu &amp; settings</a>
  <a href="{{ route('admin.restaurants.edit', $restaurant) }}" class="btn-manage">Edit restaurant</a>
  <a href="{{ route('public.menu', $restaurant->slug) }}" target="_blank" class="btn-view">View menu</a>
  <a href="{{ url('/manager/'.$restaurant->slug) }}" target="_blank" class="btn-view">Manager dashboard</a>
  <a href="{{ route('admin.subscriptions.index', ['q' => $restaurant->slug]) }}" class="btn-view">Subscriptions</a>
  <a href="{{ route('admin.payments.index') }}" class="btn-view">Payments</a>
</div>

<p style="margin-top:24px"><a href="{{ route('admin.restaurants.index') }}">â† Back to restaurants</a></p>
@endsection
@push('head')<link rel="stylesheet" href="{{ asset('legacy/css/pages/admin-dashboard.css') }}">@endpush
