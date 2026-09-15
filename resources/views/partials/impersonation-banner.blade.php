@if(session('impersonating'))
@php
    $impersonationLabel = $layoutRestaurant?->name ?? 'restaurant';
@endphp
<div class="impersonation-banner" role="status">
    <span>Viewing as {{ $impersonationLabel }} · Impersonating manager</span>
    <form method="post" action="{{ route('impersonation.leave') }}" style="margin:0;">
        @csrf
        <button type="submit" class="impersonation-banner__return">
            Return to Admin
        </button>
    </form>
</div>
@endif
