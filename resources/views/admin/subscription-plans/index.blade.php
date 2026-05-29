@extends('layouts.admin')

@section('title', 'Subscription Plans')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">Subscription Plans</h1>
        <p class="page-subtitle">Create and manage subscription plans for restaurants</p>
    </div>
    @if(!$showForm)
        <a href="{{ route('admin.subscription-plans.index', ['new' => 1]) }}" class="btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add New Plan
        </a>
    @endif
</div>

@if($showForm)
    @php
        $plan = $editPlan ?? new \App\Models\SubscriptionPlan([
            'is_active' => true,
            'monthly_price' => 10000,
            'yearly_discount_percent' => 20,
            'max_categories' => 5,
            'max_menu_items' => 50,
            'max_qr_styles' => 3,
            'max_templates' => 3,
            'display_order' => 0,
        ]);
        $planFeatures = is_array($plan->features ?? null)
            ? $plan->features
            : (json_decode($plan->features ?? '[]', true) ?: []);
        $monthly = (float) old('monthly_price', $plan->monthly_price ?? 10000);
        $discount = (float) old('yearly_discount_percent', $plan->yearly_discount_percent ?? 20);
        $annualPreview = round($monthly * 12 * (1 - $discount / 100), 2);
    @endphp

    <div class="form-card">
        <h2 class="form-title">{{ $editPlan ? 'Edit Plan: '.$editPlan->name : 'Create New Plan' }}</h2>

        <form method="post" action="{{ $editPlan ? route('admin.subscription-plans.update', $editPlan) : route('admin.subscription-plans.store') }}">
            @csrf
            @if($editPlan) @method('PUT') @endif

            <div class="section-title">Basic Information</div>
            <div class="form-grid">
                <div class="form-group">
                    <label for="name">Plan Name *</label>
                    <input type="text" id="name" name="name" required value="{{ old('name', $plan->name) }}" placeholder="e.g., Basic, Professional, Enterprise">
                </div>

                <div class="form-group">
                    <label for="slug">Slug</label>
                    <input type="text" id="slug" name="slug" value="{{ old('slug', $plan->slug) }}" placeholder="auto-generated if empty">
                    <div class="form-hint">URL-friendly identifier (lowercase, no spaces)</div>
                </div>

                <div class="form-group" style="grid-column: 1 / -1;">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" placeholder="Brief description of what this plan offers">{{ old('description', $plan->description) }}</textarea>
                </div>
            </div>

            <div class="section-title">Pricing (NGN)</div>
            <div class="form-grid">
                <div class="form-group">
                    <label for="monthly_price">Monthly Price (&#8358;)</label>
                    <input type="number" id="monthly_price" name="monthly_price" min="0" step="0.01" value="{{ old('monthly_price', $plan->monthly_price ?? 10000) }}">
                    <div class="form-hint">Base price; yearly is calculated from this and the discount % below.</div>
                </div>

                <div class="form-group">
                    <label for="yearly_discount_percent">Yearly discount (%)</label>
                    <input type="number" id="yearly_discount_percent" name="yearly_discount_percent" min="0" max="100" step="0.5" value="{{ old('yearly_discount_percent', $plan->yearly_discount_percent ?? 20) }}">
                    <div class="form-hint">Annual price = monthly &times; 12 &times; (1 &minus; this %). e.g. 20% &rarr; Save 20% off.</div>
                </div>

                <div class="form-group">
                    <label>Annual price (calculated)</label>
                    <div id="annual_price_display" class="form-readonly">&#8358;{{ number_format($annualPreview, 2) }}</div>
                </div>
            </div>

            <div class="section-title">Feature Limits</div>
            <div class="form-grid">
                <div class="form-group">
                    <label for="max_categories">Max Categories</label>
                    <input type="number" id="max_categories" name="max_categories" min="-1" value="{{ old('max_categories', $plan->max_categories ?? 5) }}">
                    <div class="form-hint">-1 for unlimited</div>
                </div>

                <div class="form-group">
                    <label for="max_menu_items">Max Menu Items</label>
                    <input type="number" id="max_menu_items" name="max_menu_items" min="-1" value="{{ old('max_menu_items', $plan->max_menu_items ?? 50) }}">
                    <div class="form-hint">-1 for unlimited</div>
                </div>

                <div class="form-group">
                    <label for="max_qr_styles">Max QR Styles</label>
                    <input type="number" id="max_qr_styles" name="max_qr_styles" min="-1" value="{{ old('max_qr_styles', $plan->max_qr_styles ?? 3) }}">
                    <div class="form-hint">-1 for unlimited</div>
                </div>

                <div class="form-group">
                    <label for="max_templates">Max Templates</label>
                    <input type="number" id="max_templates" name="max_templates" min="-1" value="{{ old('max_templates', $plan->max_templates ?? 3) }}">
                    <div class="form-hint">-1 for unlimited</div>
                </div>
            </div>

            <div class="section-title">Additional Features</div>
            <div class="form-group">
                <div class="checkbox-group">
                    @foreach([
                        'priority_support' => 'Priority Support',
                        'custom_domain' => 'Custom Domain',
                        'analytics_advanced' => 'Advanced Analytics',
                        'food_ordering' => 'Food Ordering',
                        'table_reservations' => 'Table Reservations',
                    ] as $key => $label)
                        <div class="checkbox-item">
                            <input type="checkbox" id="feature_{{ $key }}" name="feature_{{ $key }}" value="1" @checked(old('feature_'.$key, !empty($planFeatures[$key])))>
                            <label for="feature_{{ $key }}">{{ $label }}</label>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="section-title">Settings</div>
            <div class="form-grid">
                <div class="form-group">
                    <label for="display_order">Display Order</label>
                    <input type="number" id="display_order" name="display_order" min="0" value="{{ old('display_order', $plan->display_order ?? 0) }}">
                    <div class="form-hint">Lower numbers appear first</div>
                </div>

                <div class="form-group">
                    <div class="checkbox-item" style="margin-top: 32px;">
                        <input type="checkbox" id="is_active" name="is_active" value="1" @checked(old('is_active', $plan->is_active ?? true))>
                        <label for="is_active">Plan is Active</label>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">{{ $editPlan ? 'Update Plan' : 'Create Plan' }}</button>
                <a href="{{ route('admin.subscription-plans.index') }}" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
@endif

@if($plans->isNotEmpty())
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
                        <span class="price-value">&#8358;{{ number_format((float) $p->monthly_price, 2) }}</span>
                    </div>
                    <div class="price-row">
                        <span class="price-label">Annually</span>
                        <span class="price-value">&#8358;{{ number_format((float) $p->annual_price, 2) }}</span>
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
                            {!! !empty($features[$key]) ? '&#10003;' : '&#10007;' !!} {{ $label }}
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
@elseif(!$showForm)
    <div class="form-card plans-empty">
        <p style="color: #6b7280; margin-bottom: 20px;">No subscription plans found.</p>
        <a href="{{ route('admin.subscription-plans.index', ['new' => 1]) }}" class="btn-primary">Create Your First Plan</a>
    </div>
@endif
@endsection

@push('head')
<link rel="stylesheet" href="{{ asset('legacy/css/pages/admin-subscription-plans.css') }}">
@endpush

@push('scripts')
@if($showForm)
<script>
(function () {
    var monthly = document.getElementById('monthly_price');
    var discount = document.getElementById('yearly_discount_percent');
    var display = document.getElementById('annual_price_display');
    if (!monthly || !discount || !display) return;

    function updateAnnual() {
        var m = parseFloat(monthly.value) || 0;
        var d = Math.max(0, Math.min(100, parseFloat(discount.value) || 20));
        var annual = m * 12 * (1 - d / 100);
        display.textContent = '\u20A6' + (annual > 0
            ? annual.toLocaleString('en-NG', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
            : '0.00');
    }

    monthly.addEventListener('input', updateAnnual);
    discount.addEventListener('input', updateAnnual);
})();
</script>
@endif
@endpush
