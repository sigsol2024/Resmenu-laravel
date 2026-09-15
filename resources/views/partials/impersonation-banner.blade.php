@if(session('impersonating'))
<div class="impersonation-banner" role="status" style="position:sticky;top:0;z-index:100;background:#92400e;color:#fffbeb;padding:10px 16px;display:flex;flex-wrap:wrap;align-items:center;justify-content:center;gap:12px;font-size:0.875rem;font-weight:500;">
    <span>Viewing as {{ $layoutRestaurant?->name ?? 'restaurant' }} — you are impersonating the manager account.</span>
    <form method="post" action="{{ route('impersonation.leave') }}" style="margin:0;">
        @csrf
        <button type="submit" style="background:#fffbeb;color:#92400e;border:0;border-radius:8px;padding:6px 12px;font-weight:600;cursor:pointer;">
            Return to Admin
        </button>
    </form>
</div>
@endif
