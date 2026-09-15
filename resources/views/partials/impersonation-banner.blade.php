@if(session('impersonating'))
@php
    $impersonationLabel = $layoutRestaurant?->name ?? 'restaurant';
@endphp
<style>
  body.is-impersonating {
    --impersonation-banner-height: 48px;
  }
  @media (max-width: 768px) {
    body.is-impersonating {
      --impersonation-banner-height: 56px;
    }
  }
  .impersonation-banner {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    right: 0 !important;
    width: 100% !important;
    max-width: none !important;
    margin: 0 !important;
    z-index: 10050 !important;
    min-height: 48px;
    box-sizing: border-box !important;
    display: flex !important;
    flex-wrap: wrap;
    align-items: center;
    justify-content: center;
    gap: 10px 14px;
    padding: 10px 16px !important;
    background: #92400e !important;
    color: #fffbeb !important;
    font-family: Inter, sans-serif;
    font-size: 0.875rem;
    font-weight: 500;
    line-height: 1.4;
    text-align: center;
  }
  .impersonation-banner span {
    color: #fffbeb !important;
    margin: 0;
  }
  .impersonation-banner form {
    margin: 0 !important;
    display: inline-flex;
    flex-shrink: 0;
  }
  .impersonation-banner__return {
    background: #fffbeb !important;
    color: #92400e !important;
    border: none !important;
    border-radius: 6px;
    padding: 6px 12px !important;
    font-weight: 600;
    font-size: 0.8125rem;
    font-family: inherit;
    cursor: pointer;
    white-space: nowrap;
    line-height: 1.2;
  }
  body.is-impersonating .app {
    padding-top: var(--impersonation-banner-height) !important;
  }
  body.is-impersonating .sidebar-modern {
    top: var(--impersonation-banner-height) !important;
    height: calc(100% - var(--impersonation-banner-height)) !important;
  }
  body.is-impersonating .header {
    top: var(--impersonation-banner-height) !important;
  }
  body.is-impersonating .mobile-hamburger {
    top: calc(var(--impersonation-banner-height) + 12px) !important;
  }
  @media (max-width: 768px) {
    .impersonation-banner {
      min-height: 56px;
      padding: 8px 12px !important;
      font-size: 0.8125rem;
    }
  }
</style>
<div class="impersonation-banner" role="status">
    <span>Viewing as {{ $impersonationLabel }} · Impersonating manager</span>
    <form method="post" action="{{ route('impersonation.leave') }}">
        @csrf
        <button type="submit" class="impersonation-banner__return">
            Return to Admin
        </button>
    </form>
</div>
@endif
