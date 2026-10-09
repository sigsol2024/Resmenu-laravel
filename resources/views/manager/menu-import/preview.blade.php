@extends('layouts.manager')
@section('title', 'Review Menu Import')
@push('head')
<link rel="stylesheet" href="{{ resmenu_public_asset('css/pages/manager-menu-import.css') }}?v={{ is_file(public_path('assets/css/pages/manager-menu-import.css')) ? filemtime(public_path('assets/css/pages/manager-menu-import.css')) : '1' }}">
@endpush
@section('content')
<div class="page-header">
    <h1 class="page-title">Review your menu import</h1>
    <p class="page-subtitle">
        {{ $filename }} &middot; {{ count($analysis['row_order']) }} {{ count($analysis['row_order']) === 1 ? 'row' : 'rows' }}.
        Nothing has been saved yet. Check the preview, fix anything highlighted, then choose Verify &amp; Import.
    </p>
</div>

@foreach($notices as $notice)
    <div class="mimp-notice">{{ $notice }}</div>
@endforeach

<details class="mimp-format settings-card">
    <summary>Expected format</summary>
    <p class="mimp-intro">Your file needs these 5 columns in the first row. Each row is one menu item.</p>
    @include('manager.menu-import.partials.sample-table')
    <a href="{{ route('manager.menu-import.sample') }}" class="mimp-link">Download sample CSV</a>
</details>

<noscript>
    <div class="mimp-alert mimp-alert--error">The import preview needs JavaScript. Please enable it and reload this page.</div>
</noscript>

<div id="mimpBanner"></div>
<div id="mimpSummary" class="mimp-summary" aria-live="polite"></div>
<div id="mimpErrors"></div>
<div id="mimpAttention"></div>
<div id="mimpTree"></div>
<div id="mimpExcluded"></div>
<div id="mimpWarnings"></div>

<div class="mimp-footer">
    <form method="POST" action="{{ route('manager.menu-import.cancel', $token) }}" onsubmit="return confirm('Cancel this import? Nothing will be saved.');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-secondary">Cancel import</button>
    </form>
    <div class="mimp-status" id="mimpStatus" aria-live="polite"></div>
    <button type="button" class="btn btn-primary" id="mimpVerify" disabled>Verify &amp; Import</button>
</div>

<div class="mimp-modal" id="mimpConfirmModal" role="dialog" aria-modal="true" aria-labelledby="mimpConfirmTitle">
    <div class="mimp-modal-overlay" data-close-confirm></div>
    <div class="mimp-modal-content mimp-modal-content--narrow">
        <div class="mimp-modal-header">
            <h2 class="mimp-modal-title" id="mimpConfirmTitle">Confirm import</h2>
            <button type="button" class="mimp-modal-close" data-close-confirm aria-label="Close">&times;</button>
        </div>
        <div class="mimp-modal-body">
            <div id="mimpConfirmMessage"></div>
            <ul class="mimp-confirm-list" id="mimpConfirmSummary"></ul>
            <div id="mimpConfirmWarnings"></div>
        </div>
        <div class="mimp-modal-footer">
            <button type="button" class="btn btn-secondary" data-close-confirm>Back to preview</button>
            <button type="button" class="btn btn-primary" id="mimpConfirmButton">Confirm import</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    window.MENU_IMPORT = {
        analysis: @json($analysis),
        csrf: @json(csrf_token()),
        urls: {
            analyze: @json(route('manager.menu-import.analyze', $token)),
            import: @json(route('manager.menu-import.import', $token)),
            sections: @json(route('manager.sections.index')),
        },
    };
</script>
<script src="{{ resmenu_public_asset('js/manager-menu-import.js') }}?v={{ is_file(public_path('assets/js/manager-menu-import.js')) ? filemtime(public_path('assets/js/manager-menu-import.js')) : '1' }}"></script>
@endpush
@endsection
