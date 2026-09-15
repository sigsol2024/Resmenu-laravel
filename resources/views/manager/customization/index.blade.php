@extends('layouts.manager')

@section('title', 'Menu Designs')

@push('head')
<link rel="stylesheet" href="{{ resmenu_public_asset('css/pages/manager-customization.css') }}">
@if($inline = resmenu_inline_page_css('manager-customization.css'))
<style>{!! $inline !!}</style>
@endif
@endpush

@section('content')
@php $c = $customization; @endphp

<div class="customization-page">
<div class="page-header">
    <h1 class="page-title">Menu Designs</h1>
    <p class="page-subtitle">Choose a design for your restaurant's menu page</p>
</div>

@if($flashMessage ?? session('success'))
    <div class="alert alert-success">{{ $flashMessage ?? session('success') }}</div>
@endif
@if($errors->any())
    <div class="alert alert-error">{{ $errors->first() }}</div>
@endif

<div class="settings-card">
    <div class="section-header">
        <h2 class="section-title">Select Menu Design</h2>
    </div>
    <p style="margin-bottom: 20px; color: var(--muted); font-size: 0.875rem;">Choose a design for your restaurant's menu page.</p>
    <form method="POST" action="{{ route('manager.customization') }}">
        @csrf
        <input type="hidden" name="action" value="save_template">
        <div class="form-group">
            <label class="form-label">Select Menu Design</label>
            <select name="template_id" class="form-select" @disabled(empty($templatesCanUse))>
                @foreach($templatesCanUse as $template)
                    <option value="{{ $template['id'] }}" @selected($currentTemplateId == $template['id'] && $currentInCanUse)>
                        {{ $template['name'] }}
                    </option>
                @endforeach
            </select>
            @if(empty($templatesCanUse))
                <p style="margin-top: 8px; color: var(--muted); font-size: 0.875rem;">No menu designs available for your plan. Contact support or upgrade to access more designs.</p>
            @endif
        </div>
        @if(!empty($templatesUpgrade))
            <p style="margin-bottom: 16px; color: #6b7280; font-size: 0.875rem;">
                Menu designs assigned to you (upgrade required to use): {{ collect($templatesUpgrade)->pluck('name')->join(', ') }}
                — <a href="{{ route('manager.billing.index') }}">Upgrade plan</a>
            </p>
        @endif
        <button type="submit" class="btn btn-primary" @disabled(empty($templatesCanUse))>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            Save Menu Design
        </button>
    </form>
    <p style="margin-top: 15px; color: var(--muted); font-size: 14px;">
        <strong>Current Menu Design:</strong> Design {{ $currentTemplateId }}
        @if($currentTemplateName)
            - {{ $currentTemplateName }}
        @endif
    </p>
</div>

<div class="settings-card template-colors-card" style="margin-top: 24px;">
    <div class="section-header template-colors-toggle" id="template-colors-toggle" style="display:flex;align-items:center;justify-content:space-between;cursor:pointer;">
        <div>
            <h2 class="section-title">Menu Design Colors & Styles</h2>
            <p style="margin: 4px 0 0; color: var(--muted); font-size: 0.8rem;">Click to expand and customize colors for this menu design.</p>
        </div>
        <span id="template-colors-chevron" style="font-size: 1rem; color: #6b7280; transition: transform 0.2s;">▼</span>
    </div>
    <div class="template-colors-body" id="template-colors-body" style="display: none; margin-top: 16px;">
        <p style="margin-bottom: 16px; color: var(--muted); font-size: 0.875rem;">Customize all colors and styles for the selected menu design. Each design remembers its own settings when you switch between them.</p>
        <form method="POST" action="{{ route('manager.customization') }}">
            @csrf
            <input type="hidden" name="action" value="save_customization">
            <input type="hidden" name="template_id" value="{{ $currentTemplateId }}">

            <div class="card" style="margin-bottom: 24px;">
                <div class="card-header"><h2 class="card-title">Menu Title</h2></div>
                <div class="color-input-group">
                    <div class="form-group">
                        <label class="form-label">Color</label>
                        <input type="color" name="menu_title_color" value="{{ $c['menu_title_color'] ?? '#000000' }}" style="width: 60px; height: 40px; border: 2px solid #e5e7eb; border-radius: 8px; cursor: pointer;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Size (px)</label>
                        <input type="number" name="menu_title_size" class="form-input" value="{{ $c['menu_title_size'] ?? 24 }}" min="12" max="72">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Font</label>
                        <select name="menu_title_font" class="form-select">
                            @foreach(['Inter', 'Poppins'] as $font)
                                <option value="{{ $font }}" @selected(($c['menu_title_font'] ?? 'Inter') === $font)>{{ $font }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="card" style="margin-bottom: 24px;">
                <div class="card-header"><h2 class="card-title">Price</h2></div>
                <div class="color-input-group">
                    <div class="form-group">
                        <label class="form-label">Color</label>
                        <input type="color" name="price_color" value="{{ $c['price_color'] ?? '#000000' }}" style="width: 60px; height: 40px; border: 2px solid #e5e7eb; border-radius: 8px; cursor: pointer;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Size (px)</label>
                        <input type="number" name="price_size" class="form-input" value="{{ $c['price_size'] ?? 18 }}" min="12" max="48">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Font</label>
                        <select name="price_font" class="form-select">
                            @foreach(['Inter', 'Poppins'] as $font)
                                <option value="{{ $font }}" @selected(($c['price_font'] ?? 'Inter') === $font)>{{ $font }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="card" style="margin-bottom: 24px;">
                <div class="card-header"><h2 class="card-title">Description</h2></div>
                <div class="color-input-group">
                    <div class="form-group">
                        <label class="form-label">Color</label>
                        <input type="color" name="description_color" value="{{ $c['description_color'] ?? '#666666' }}" style="width: 60px; height: 40px; border: 2px solid #e5e7eb; border-radius: 8px; cursor: pointer;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Size (px)</label>
                        <input type="number" name="description_size" class="form-input" value="{{ $c['description_size'] ?? 14 }}" min="10" max="24">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Font</label>
                        <select name="description_font" class="form-select">
                            @foreach(['Inter', 'Poppins'] as $font)
                                <option value="{{ $font }}" @selected(($c['description_font'] ?? 'Inter') === $font)>{{ $font }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="card" style="margin-bottom: 24px;">
                <div class="card-header"><h2 class="card-title">Category Title</h2></div>
                <div class="color-input-group">
                    <div class="form-group">
                        <label class="form-label">Color</label>
                        <input type="color" name="category_title_color" value="{{ $c['category_title_color'] ?? '#ffffff' }}" style="width: 60px; height: 40px; border: 2px solid #e5e7eb; border-radius: 8px; cursor: pointer;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Size (px)</label>
                        <input type="number" name="category_title_size" class="form-input" value="{{ $c['category_title_size'] ?? 20 }}" min="12" max="48">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Font</label>
                        <select name="category_title_font" class="form-select">
                            @foreach(['Inter', 'Poppins'] as $font)
                                <option value="{{ $font }}" @selected(($c['category_title_font'] ?? 'Inter') === $font)>{{ $font }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="card" style="margin-bottom: 24px;">
                <div class="card-header"><h2 class="card-title">Background & Accent Colors</h2></div>
                <div class="color-input-group">
                    <div class="form-group">
                        <label class="form-label">Page Background</label>
                        <input type="color" name="background_color" value="{{ $c['background_color'] ?? '#FFFFFF' }}" style="width: 60px; height: 40px; border: 2px solid #e5e7eb; border-radius: 8px; cursor: pointer;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Header Background</label>
                        <input type="color" name="header_background_color" value="{{ $c['header_background_color'] ?? '#FFFFFF' }}" style="width: 60px; height: 40px; border: 2px solid #e5e7eb; border-radius: 8px; cursor: pointer;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Primary Color</label>
                        <input type="color" name="primary_color" value="{{ $c['primary_color'] ?? '#111111' }}" style="width: 60px; height: 40px; border: 2px solid #e5e7eb; border-radius: 8px; cursor: pointer;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Secondary Color</label>
                        <input type="color" name="secondary_color" value="{{ $c['secondary_color'] ?? '#FFFFFF' }}" style="width: 60px; height: 40px; border: 2px solid #e5e7eb; border-radius: 8px; cursor: pointer;">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Save Colors & Styles
            </button>
        </form>
    </div>
</div>

<div class="settings-card" style="margin-top: 24px;">
    <div class="section-header">
        <h2 class="section-title">Ordering & Reservations</h2>
    </div>
    <p style="margin-bottom: 16px; color: var(--muted); font-size: 0.875rem;">
        Turn food ordering and table reservations on or off for your menu page. These settings apply to any menu design you select.
    </p>
    <form method="POST" action="{{ route('manager.customization') }}">
        @csrf
        <input type="hidden" name="action" value="save_feature_toggles">

        <div class="form-group">
            <span class="form-label">Food Ordering</span>
            @if($planHasOrdering)
                <label class="form-label" style="display:flex;align-items:center;gap:8px;font-weight:400;">
                    <input type="checkbox" name="enable_food_ordering" value="1" @checked($enableFoodOrdering)>
                    <span>Enable food ordering on my menu</span>
                </label>
                <p style="margin-top: 4px; color: var(--muted); font-size: 0.8rem;">
                    When turned off, all “Add to bag” buttons are hidden on your public menu, even if your plan includes ordering.
                </p>
            @else
                <p style="margin-top: 4px; color: #b91c1c; font-size: 0.875rem;">
                    Not included in your current plan. <a href="{{ route('manager.billing.index') }}">Upgrade your plan</a> to enable food ordering.
                </p>
            @endif
        </div>

        <div class="form-group" style="margin-top: 12px;">
            <span class="form-label">Table Reservations</span>
            @if($planHasReservations)
                <label class="form-label" style="display:flex;align-items:center;gap:8px;font-weight:400;">
                    <input type="checkbox" name="enable_table_reservations" value="1" @checked($enableTableReservations)>
                    <span>Enable table reservations on my menu</span>
                </label>
                <p style="margin-top: 4px; color: var(--muted); font-size: 0.8rem;">
                    When turned off, all “Reserve Table” buttons and reservation entry points are hidden on your public menu.
                </p>
            @else
                <p style="margin-top: 4px; color: #b91c1c; font-size: 0.875rem;">
                    Not included in your current plan. <a href="{{ route('manager.billing.index') }}">Upgrade your plan</a> to enable table reservations.
                </p>
            @endif
        </div>

        @if($planHasOrdering || $planHasReservations)
            <button type="submit" class="btn btn-primary" style="margin-top: 12px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Save Settings
            </button>
        @endif
    </form>
</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var toggle = document.getElementById('template-colors-toggle');
    var body = document.getElementById('template-colors-body');
    var chevron = document.getElementById('template-colors-chevron');
    if (!toggle || !body) return;
    body.style.display = 'none';
    toggle.addEventListener('click', function() {
        var isOpen = body.style.display === 'block';
        body.style.display = isOpen ? 'none' : 'block';
        if (chevron) chevron.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(180deg)';
    });
});
</script>
@endsection
