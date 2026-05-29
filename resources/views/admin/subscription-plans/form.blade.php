@extends('layouts.admin')
@section('title', $plan->exists ? 'Edit plan' : 'New plan')
@section('content')
@include('partials.admin.page-header', ['title' => $plan->exists ? 'Edit plan' : 'New plan'])

<div class="card" style="max-width:640px;">
  <form method="post" action="{{ $plan->exists ? route('admin.subscription-plans.update', $plan) : route('admin.subscription-plans.store') }}">
    @csrf
    @if($plan->exists) @method('PUT') @endif
    <div class="form-grid">
      <div class="filter-group"><label>Name</label><input name="name" value="{{ old('name', $plan->name) }}" required></div>
      <div class="filter-group"><label>Slug</label><input name="slug" value="{{ old('slug', $plan->slug) }}"></div>
      <div class="filter-group"><label>Description</label><textarea name="description" rows="3">{{ old('description', $plan->description) }}</textarea></div>
      <div class="filter-group"><label>Monthly price (₦)</label><input type="number" step="0.01" name="monthly_price" value="{{ old('monthly_price', $plan->monthly_price) }}" required></div>
      <div class="filter-group"><label>Yearly discount %</label><input type="number" name="yearly_discount_percent" value="{{ old('yearly_discount_percent', $plan->yearly_discount_percent ?? 20) }}"></div>
      <div class="filter-group"><label>Max categories (-1 unlimited)</label><input type="number" name="max_categories" value="{{ old('max_categories', $plan->max_categories ?? 5) }}"></div>
      <div class="filter-group"><label>Max menu items</label><input type="number" name="max_menu_items" value="{{ old('max_menu_items', $plan->max_menu_items ?? 50) }}"></div>
      <div class="filter-group"><label>Max QR styles</label><input type="number" name="max_qr_styles" value="{{ old('max_qr_styles', $plan->max_qr_styles ?? 3) }}"></div>
      <div class="filter-group"><label>Max templates</label><input type="number" name="max_templates" value="{{ old('max_templates', $plan->max_templates ?? 3) }}"></div>
      <div class="filter-group"><label>Display order</label><input type="number" name="display_order" value="{{ old('display_order', $plan->display_order ?? 0) }}"></div>
    </div>
    <div style="margin:16px 0;">
      <label><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $plan->is_active ?? true))> Active</label><br>
      @foreach(['priority_support' => 'Priority Support', 'custom_domain' => 'Custom Domain', 'analytics_advanced' => 'Advanced Analytics', 'food_ordering' => 'Food Ordering', 'table_reservations' => 'Table Reservations'] as $key => $label)
        <label><input type="checkbox" name="feature_{{ $key }}" value="1" @checked(old('feature_'.$key, data_get($plan->features, $key)))> {{ $label }}</label><br>
      @endforeach
    </div>
    <button type="submit" class="btn-filter">Save plan</button>
    <a href="{{ route('admin.subscription-plans.index') }}" class="btn-clear">Cancel</a>
  </form>
</div>
@endsection
@push('head')
<link rel="stylesheet" href="{{ asset('legacy/css/pages/admin-payments.css') }}">
<style>.form-grid{display:grid;gap:12px}.form-grid textarea,.form-grid input{width:100%;padding:10px;border:1px solid #d1d5db;border-radius:6px}</style>
@endpush
