@php
    $activeId = 'dashboard';
    $path = request()->path();
    foreach ($layoutNavItems as $item) {
        $itemPath = trim(parse_url($item['href'], PHP_URL_PATH) ?? '', '/');
        if ($itemPath !== '' && str_contains($path, $itemPath)) {
            $activeId = $item['id'];
        }
    }
    if (request()->routeIs('admin.dashboard')) {
        $activeId = 'dashboard';
    }
    if (request()->routeIs('admin.restaurants.show')) {
        $activeId = 'restaurants';
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
                <div class="logo-icon-modern">S</div>
                <div class="logo-text">
                    <span class="logo-title">Super Admin</span>
                    <span class="logo-subtitle">Dashboard</span>
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
                    <p class="profile-role">{{ $layoutUserEmail ?: 'Administrator' }}</p>
                </div>
                <div class="profile-status" title="Online"></div>
            </div>
        </div>
        <div class="sidebar-logout">
            <form action="{{ route('admin.logout') }}" method="post">
                @csrf
                <button type="submit" class="logout-btn" title="Logout">
                    <div class="nav-icon-wrapper" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="nav-icon logout-icon">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                        </svg>
                    </div>
                    <span class="nav-text">Logout</span>
                </button>
            </form>
        </div>
    </div>
</aside>
