@extends('layouts.admin')

@section('title', 'Subscription plans')

@section('content')

<div class="admin-page-header-row">

  <div>

    @include('partials.admin.page-header', ['title' => 'Subscription Plans', 'subtitle' => 'Create and manage subscription plans for restaurants'])

  </div>

  @if(!$showForm)

    <a href="{{ route('admin.subscription-plans.index', ['new' => 1]) }}" class="btn-primary">Add New Plan</a>

  @endif

</div>



@if($showForm)

  @php $plan = $editPlan ?? new \App\Models\SubscriptionPlan(['is_active' => true, 'monthly_price' => 10000, 'yearly_discount_percent' => 20, 'max_categories' => 5, 'max_menu_items' => 50, 'max_qr_styles' => 3, 'max_templates' => 3]); @endphp

  <div class="form-card" style="background:#fff;border-radius:8px;box-shadow:0 1px 3px rgba(0,0,0,.1);padding:24px;margin-bottom:24px">

    <h2 class="form-title" style="font-size:1.25rem;font-weight:600;margin:0 0 24px">{{ $editPlan ? 'Edit Plan: '.$editPlan->name : 'Create New Plan' }}</h2>

    <form method="post" action="{{ $editPlan ? route('admin.subscription-plans.update', $editPlan) : route('admin.subscription-plans.store') }}">

      @csrf

      @if($editPlan) @method('PUT') @endif

      <div class="form-grid" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:16px">

        <div class="filter-group"><label>Name *</label><input name="name" value="{{ old('name', $plan->name) }}" required style="width:100%"></div>

        <div class="filter-group"><label>Slug</label><input name="slug" value="{{ old('slug', $plan->slug) }}" style="width:100%"></div>

        <div class="filter-group" style="grid-column:1/-1"><label>Description</label><textarea name="description" rows="3" style="width:100%">{{ old('description', $plan->description) }}</textarea></div>

        <div class="filter-group"><label>Monthly price (₦)</label><input type="number" step="0.01" name="monthly_price" value="{{ old('monthly_price', $plan->monthly_price) }}" required style="width:100%"></div>

        <div class="filter-group"><label>Yearly discount %</label><input type="number" name="yearly_discount_percent" value="{{ old('yearly_discount_percent', $plan->yearly_discount_percent ?? 20) }}" style="width:100%"></div>

        <div class="filter-group"><label>Max categories (-1 unlimited)</label><input type="number" name="max_categories" value="{{ old('max_categories', $plan->max_categories ?? 5) }}" style="width:100%"></div>

        <div class="filter-group"><label>Max menu items</label><input type="number" name="max_menu_items" value="{{ old('max_menu_items', $plan->max_menu_items ?? 50) }}" style="width:100%"></div>

        <div class="filter-group"><label>Max QR styles</label><input type="number" name="max_qr_styles" value="{{ old('max_qr_styles', $plan->max_qr_styles ?? 3) }}" style="width:100%"></div>

        <div class="filter-group"><label>Max templates</label><input type="number" name="max_templates" value="{{ old('max_templates', $plan->max_templates ?? 3) }}" style="width:100%"></div>

        <div class="filter-group"><label>Display order</label><input type="number" name="display_order" value="{{ old('display_order', $plan->display_order ?? 0) }}" style="width:100%"></div>

      </div>

      <div style="margin:16px 0;display:grid;gap:8px">

        <label><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $plan->is_active ?? true))> Active</label>

        @foreach(['priority_support' => 'Priority Support', 'custom_domain' => 'Custom Domain', 'analytics_advanced' => 'Advanced Analytics', 'food_ordering' => 'Food Ordering', 'table_reservations' => 'Table Reservations'] as $key => $label)

          <label><input type="checkbox" name="feature_{{ $key }}" value="1" @checked(old('feature_'.$key, data_get($plan->features, $key)))> {{ $label }}</label>

        @endforeach

      </div>

      <div style="display:flex;gap:8px;flex-wrap:wrap">

        <button type="submit" class="btn-filter">{{ $editPlan ? 'Update Plan' : 'Create Plan' }}</button>

        <a href="{{ route('admin.subscription-plans.index') }}" class="btn-clear">Cancel</a>

      </div>

    </form>

  </div>

@endif



@if($plans->isEmpty() && !$showForm)

  <div class="plans-empty">

    <p style="margin-bottom:20px">No subscription plans found.</p>

    <a href="{{ route('admin.subscription-plans.index', ['new' => 1]) }}" class="btn-primary">Create Your First Plan</a>

  </div>

@elseif($plans->isNotEmpty())

  <div class="plans-grid">

    @foreach($plans as $p)

      @php

        $features = is_array($p->features) ? $p->features : (json_decode($p->features ?? '[]', true) ?: []);

        $limitLabel = fn ($n) => ((int) $n === -1) ? 'Unlimited' : (string) $n;

        $limitClass = fn ($n) => ((int) $n === -1) ? 'limit-unlimited' : '';

      @endphp

      <div class="plan-card {{ $p->is_active ? '' : 'inactive' }}">

        <span class="status-badge {{ $p->is_active ? 'status-active' : 'status-inactive' }}">

          {{ $p->is_active ? 'Active' : 'Inactive' }}

        </span>



        <div class="plan-header">

          <div class="plan-name">{{ $p->name }}</div>

          <div class="plan-description">{{ $p->description }}</div>

        </div>



        <div class="plan-pricing">

          <div class="price-row">

            <span class="price-label">Monthly</span>

            <span class="price-value">₦{{ number_format((float) $p->monthly_price, 2) }}</span>

          </div>

          <div class="price-row">

            <span class="price-label">Annually</span>

            <span class="price-value">₦{{ number_format((float) $p->annual_price, 2) }}</span>

          </div>

          @if((int) ($p->yearly_discount_percent ?? 0) > 0)

            <div class="price-row price-hint">Save {{ (int) $p->yearly_discount_percent }}% off</div>

          @endif

        </div>



        <div class="plan-limits">

          <div class="limit-item">

            <span class="limit-label">Categories</span>

            <span class="limit-value {{ $limitClass($p->max_categories) }}">{{ $limitLabel($p->max_categories) }}</span>

          </div>

          <div class="limit-item">

            <span class="limit-label">Menu Items</span>

            <span class="limit-value {{ $limitClass($p->max_menu_items) }}">{{ $limitLabel($p->max_menu_items) }}</span>

          </div>

          <div class="limit-item">

            <span class="limit-label">QR Styles</span>

            <span class="limit-value {{ $limitClass($p->max_qr_styles) }}">{{ $limitLabel($p->max_qr_styles) }}</span>

          </div>

          <div class="limit-item">

            <span class="limit-label">Templates</span>

            <span class="limit-value {{ $limitClass($p->max_templates) }}">{{ $limitLabel($p->max_templates) }}</span>

          </div>

        </div>



        <div class="plan-features">

          @foreach([

            'priority_support' => 'Priority Support',

            'custom_domain' => 'Custom Domain',

            'analytics_advanced' => 'Advanced Analytics',

            'food_ordering' => 'Food Ordering',

            'table_reservations' => 'Reservations',

          ] as $key => $label)

            <span class="feature-badge {{ !empty($features[$key]) ? 'feature-enabled' : 'feature-disabled' }}">

              {{ !empty($features[$key]) ? '✓' : '✗' }} {{ $label }}

            </span>

          @endforeach

        </div>



        <div class="plan-actions">

          @include('partials.admin.actions-dropdown', [

            'items' => [

              ['label' => 'Edit', 'url' => route('admin.subscription-plans.index', ['edit' => $p->id])],

              [

                'type' => 'form',

                'label' => $p->is_active ? 'Deactivate' : 'Activate',

                'action' => route('admin.subscription-plans.toggle', $p),

              ],

              ['type' => 'divider'],

              [

                'type' => 'form',

                'label' => 'Delete',

                'action' => route('admin.subscription-plans.destroy', $p),

                'method' => 'DELETE',

                'class' => 'danger',

                'confirm' => 'Are you sure you want to delete this plan?',

              ],

            ],

          ])

        </div>

      </div>

    @endforeach

  </div>

@endif

@endsection

@push('head')

<link rel="stylesheet" href="{{ asset('assets/css/admin-subscription-plans.css') }}">

<link rel="stylesheet" href="{{ asset('assets/css/admin-payments.css') }}">

@endpush

