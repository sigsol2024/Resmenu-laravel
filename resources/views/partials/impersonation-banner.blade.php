@if(session('impersonating'))
@php
    $impersonationLabel = $layoutRestaurant?->name ?? 'restaurant';
@endphp
<div class="impersonation-banner" role="status" style="position:fixed;top:0;left:0;right:0;width:100%;z-index:10050;box-sizing:border-box;">
    <span>Viewing as {{ $impersonationLabel }} · Impersonating manager</span>
    <form method="post" action="{{ route('impersonation.leave') }}" style="margin:0;flex-shrink:0;">
        @csrf
        <button type="submit" class="impersonation-banner__return">
            Return to Admin
        </button>
    </form>
</div>
@endif
