@php
    $activeId = 'dashboard';
    $path = request()->path();
    foreach ($layoutNavItems as $item) {
        if (str_contains($path, trim(parse_url($item['href'], PHP_URL_PATH) ?? '', '/'))) {
            $activeId = $item['id'];
        }
    }
    if (request()->routeIs('manager.dashboard')) {
        $activeId = 'dashboard';
    }
    if (request()->routeIs('manager.qr.*')) {
        $activeId = 'qr-code';
    }
    if (request()->routeIs('manager.customization')) {
        $activeId = 'customization';
    }
    if (request()->routeIs('manager.billing.payment-settings')) {
        $activeId = 'payment-settings';
    }
    if (request()->routeIs('manager.billing.*') && ! request()->routeIs('manager.billing.payment-settings')) {
        $activeId = 'billing';
    }
    if (request()->routeIs('manager.table-inventory.*')) {
        $activeId = 'table-inventory';
    }
@endphp
<button type="button" onclick="toggleMobile()" class="mobile-hamburger" aria-label="Toggle sidebar">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="hamburger-icon">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
    </svg>
</button>
<div class="sidebar-overlay" onclick="toggleMobile()"></div>
<aside class="sidebar-modern open" id="sidebar">
    <div class="sidebar-header-modern">
        <div class="sidebar-logo-wrapper">
            <div class="sidebar-logo">
                @if($layoutLogoUrl)
                    <img src="{{ $layoutLogoUrl }}" alt="{{ $layoutRestaurant->name ?? 'Restaurant' }}" class="logo-image-modern">
                @else
                    <div class="logo-icon-modern">{{ strtoupper(substr($layoutRestaurant->name ?? 'M', 0, 1)) }}</div>
                @endif
                <div class="logo-text">
                    <span class="logo-title">{{ $layoutRestaurant->name ?? 'Manager' }}</span>
                    <span class="logo-subtitle">Restaurant Dashboard</span>
                </div>
            </div>
        </div>
        <button type="button" onclick="toggleCollapse()" class="collapse-btn-modern" aria-label="Collapse sidebar">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="collapse-icon">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
            </svg>
        </button>
    </div>
    <nav class="sidebar-nav">
        <ul class="nav-list">
            @foreach($layoutNavItems as $item)
                <li>
                    <a href="{{ $item['href'] }}" class="nav-item {{ $activeId === $item['id'] ? 'active' : '' }}" title="{{ $item['name'] }}">
                        <div class="nav-icon-wrapper">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="nav-icon">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
                            </svg>
                        </div>
                        <span class="nav-text">{{ $item['name'] }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </nav>
    <div class="sidebar-footer-modern">
        <div class="sidebar-profile">
            <div class="profile-card">
                <div class="profile-avatar">{{ $layoutUserInitials }}</div>
                <div class="profile-info">
                    <p class="profile-name">{{ $layoutUsername }}</p>
                    <p class="profile-role">{{ $layoutRestaurant->name ?? '' }}</p>
                </div>
                <div class="profile-status" title="Online"></div>
            </div>
        </div>
        <div class="sidebar-logout">
            <a href="https://resmenu.net/contact" class="logout-btn help-btn" target="_blank" rel="noopener noreferrer" title="Help Center">
                <div class="nav-icon-wrapper" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="nav-icon">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.09 9a3 3 0 015.82 1c0 2-3 2-3 4m.008 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="nav-text">Help Center</span>
            </a>
        </div>
        <div class="sidebar-logout">
            <form action="{{ route('logout') }}" method="post">
                @csrf
                <button type="submit" class="logout-btn" title="Logout">
                    <div class="nav-icon-wrapper" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="nav-icon logout-icon">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 2v10m6.364-6.364a9 9 0 1 1-12.728 0" />
                        </svg>
                    </div>
                    <span class="nav-text">Logout</span>
                </button>
            </form>
        </div>
    </div>
</aside>
