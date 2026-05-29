@extends('layouts.manager')

@section('title', 'Template & customization')

@push('head')
<link rel="stylesheet" href="{{ asset('legacy/css/pages/manager-customization.css') }}">
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">Template selection</h1>
    <p class="page-subtitle">Choose a design template and customize colors for your menu</p>
</div>

@if($flashMessage ?? session('success'))
    <div class="alert alert-success">{{ $flashMessage ?? session('success') }}</div>
@endif
@if($errors->any())
    <div class="alert alert-error">{{ $errors->first() }}</div>
@endif

<div class="settings-card">
    <h2 class="section-title">Select template</h2>
    <form method="post" action="{{ route('manager.customization') }}">
        @csrf
        <input type="hidden" name="action" value="save_template">
        <div class="form-group">
            <label class="form-label">Template</label>
            <select name="template_id" class="form-select" @disabled(empty($templatesCanUse))>
                @foreach($templatesCanUse as $t)
                    <option value="{{ $t['id'] }}" @selected($restaurant->template_id == $t['id'])>{{ $t['name'] }}</option>
                @endforeach
            </select>
            @if(empty($templatesCanUse))
                <p class="text-muted" style="margin-top:8px;">No templates available for your plan. <a href="{{ route('manager.billing') }}">Upgrade plan</a></p>
            @endif
            @if(!empty($templatesUpgrade))
                <p class="text-muted" style="margin-top:8px;">Upgrade required to use: {{ collect($templatesUpgrade)->pluck('name')->join(', ') }}</p>
            @endif
        </div>
        <button type="submit" class="btn btn-primary" @disabled(empty($templatesCanUse))>Save template</button>
    </form>
    <p style="margin-top:12px;color:#6b7280;font-size:0.875rem;">Current: Template {{ $restaurant->template_id }}</p>
</div>

<div class="settings-card" style="margin-top:24px;">
    <h2 class="section-title">Ordering & reservations</h2>
    <form method="post" action="{{ route('manager.customization') }}">
        @csrf
        <input type="hidden" name="action" value="save_feature_toggles">
        <label style="display:flex;align-items:center;gap:8px;margin-bottom:12px;">
            <input type="checkbox" name="enable_food_ordering" value="1" @checked($enableFoodOrdering) @disabled(! $planHasOrdering)>
            Enable food ordering on public menu
            @unless($planHasOrdering)<span class="text-muted">(Professional+ plan)</span>@endunless
        </label>
        <label style="display:flex;align-items:center;gap:8px;margin-bottom:16px;">
            <input type="checkbox" name="enable_table_reservations" value="1" @checked($enableTableReservations) @disabled(! $planHasReservations)>
            Enable table reservations
            @unless($planHasReservations)<span class="text-muted">(Enterprise plan)</span>@endunless
        </label>
        <button type="submit" class="btn btn-primary">Save feature toggles</button>
    </form>
</div>

<div class="settings-card" style="margin-top:24px;">
    <h2 class="section-title">Template colors & styles</h2>
    <p class="text-muted" style="font-size:0.875rem;margin-bottom:16px;">Settings are saved per template. Switching templates keeps each template's colors.</p>
    <form method="post" action="{{ route('manager.customization') }}" class="space-y-4">
        @csrf
        <input type="hidden" name="action" value="save_customization">
        @php $c = $customization; @endphp
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;">
            @foreach([
                ['menu_title_color', 'Menu title color', 'color'],
                ['price_color', 'Price color', 'color'],
                ['description_color', 'Description color', 'color'],
                ['category_title_color', 'Category title color', 'color'],
                ['background_color', 'Background', 'color'],
                ['header_background_color', 'Header background', 'color'],
                ['primary_color', 'Primary', 'color'],
                ['secondary_color', 'Secondary', 'color'],
            ] as [$field, $label, $type])
                <div>
                    <label class="form-label">{{ $label }}</label>
                    <input type="{{ $type }}" name="{{ $field }}" value="{{ $c[$field] ?? '#000000' }}" class="form-input" style="height:40px;padding:4px;">
                </div>
            @endforeach
            @foreach([
                ['menu_title_size', 'Menu title size'],
                ['price_size', 'Price size'],
                ['description_size', 'Description size'],
                ['category_title_size', 'Category title size'],
            ] as [$field, $label])
                <div>
                    <label class="form-label">{{ $label }} (px)</label>
                    <input type="number" name="{{ $field }}" value="{{ $c[$field] ?? 16 }}" class="form-input" min="10" max="72">
                </div>
            @endforeach
            @foreach([
                ['menu_title_font', 'Menu title font'],
                ['price_font', 'Price font'],
                ['description_font', 'Description font'],
                ['category_title_font', 'Category title font'],
            ] as [$field, $label])
                <div>
                    <label class="form-label">{{ $label }}</label>
                    <select name="{{ $field }}" class="form-select">
                        @foreach(['Inter', 'Poppins', 'Epilogue'] as $font)
                            <option value="{{ $font }}" @selected(($c[$field] ?? 'Inter') === $font)>{{ $font }}</option>
                        @endforeach
                    </select>
                </div>
            @endforeach
        </div>
        <button type="submit" class="btn btn-primary" style="margin-top:16px;">Save customization</button>
    </form>
</div>
@endsection
