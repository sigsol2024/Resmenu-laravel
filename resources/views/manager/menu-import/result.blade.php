@extends('layouts.manager')
@section('title', 'Menu Import Complete')
@push('head')
<link rel="stylesheet" href="{{ resmenu_public_asset('css/pages/manager-menu-import.css') }}?v={{ is_file(public_path('assets/css/pages/manager-menu-import.css')) ? filemtime(public_path('assets/css/pages/manager-menu-import.css')) : '1' }}">
@endpush
@section('content')
<div class="page-header">
    <h1 class="page-title">Import complete</h1>
    <p class="page-subtitle">
        @if($filename){{ $filename }} was imported.@else Your menu was imported.@endif
        New sections, categories and items are active and visible on your menu.
    </p>
</div>

<div class="settings-card">
    <div class="mimp-result-grid">
        <div class="mimp-stat">
            <div class="mimp-stat-label">Sections</div>
            <div class="mimp-stat-value">{{ $result['sections_created'] }} created<br>{{ $result['sections_reused'] }} reused</div>
        </div>
        <div class="mimp-stat">
            <div class="mimp-stat-label">Categories</div>
            <div class="mimp-stat-value">{{ $result['categories_created'] }} created<br>{{ $result['categories_reused'] }} reused</div>
        </div>
        <div class="mimp-stat">
            <div class="mimp-stat-label">Menu items</div>
            <div class="mimp-stat-value">
                {{ $result['items_created'] }} created<br>
                {{ $result['items_updated'] }} updated<br>
                {{ $result['items_unchanged'] }} unchanged<br>
                {{ $result['items_skipped'] }} skipped (duplicates or excluded rows)
            </div>
        </div>
    </div>

    <div class="mimp-result-actions">
        <a href="{{ route('manager.sections.index') }}" class="btn btn-primary">View sections</a>
        <a href="{{ route('manager.menu-items.index') }}" class="btn btn-secondary">View menu items</a>
        @if($restaurant->slug ?? false)
            <a href="{{ route('public.menu', $restaurant->slug) }}" class="btn btn-secondary" target="_blank" rel="noopener">Open public menu</a>
        @endif
    </div>
</div>
@endsection
