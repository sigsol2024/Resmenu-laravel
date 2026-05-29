@if(!empty($showUpgradeOverlay))
<div class="upgrade-overlay" style="position:relative;">
    <div style="filter:blur(4px);pointer-events:none;user-select:none;opacity:0.5;" aria-hidden="true">
        {{ $slot ?? '' }}
    </div>
    <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;background:rgba(255,255,255,0.85);padding:24px;text-align:center;z-index:10;">
        <div style="max-width:420px;">
            <h2 style="margin:0 0 12px;font-size:1.25rem;">Upgrade required</h2>
            <p style="color:#4b5563;margin:0 0 16px;">{{ $upgradeMessage ?? 'This feature is not included in your current plan.' }}</p>
            <a href="{{ route('manager.billing') }}" class="btn btn-primary">View plans & upgrade</a>
        </div>
    </div>
</div>
@else
    {{ $slot ?? '' }}
@endif
