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
    if (request()->routeIs('admin.restaurants.show', 'admin.restaurants.impersonate', 'admin.restaurants.hub')) {
        $activeId = 'restaurants';
    }
    if (request()->routeIs('admin.admins.*')) {
        $activeId = 'admins';
    }
    if (request()->routeIs('admin.profile.*')) {
        $activeId = 'profile';
    }
@endphp
<button type="button" onclick="toggleMobile()" class="mobile-hamburger" aria-label="Toggle sidebar">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="hamburger-icon">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
    </svg>
</button>
<div class="sidebar-overlay" onclick="toggleMobile()"></div>
<aside class="sidebar-modern" id="sidebar">
    <div class="sidebar-header-modern">
        <div class="sidebar-logo-wrapper">
            <div class="sidebar-logo">
                <div class="logo-icon-modern">S</div>
                <div class="logo-text">
                    <span class="logo-title">{{ $layoutRoleLabel ?? 'Administrator' }}</span>
                    <span class="logo-subtitle">Dashboard</span>
                </div>
            </div>
            <div class="logo-icon-modern-centered" style="display: none;">S</div>
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
            @if(!request()->cookie('sidebar_collapsed') || request()->cookie('sidebar_collapsed') !== 'true')
                <a href="{{ $layoutProfileUrl ?? route('admin.profile.show') }}" class="profile-card" style="text-decoration:none;color:inherit;display:flex;align-items:center;gap:12px;" title="Profile">
                    <div class="profile-avatar">{{ $layoutUserInitials }}</div>
                    <div class="profile-info">
                        <p class="profile-name">{{ $layoutUsername }}</p>
                        <p class="profile-role">{{ $layoutUserEmail ?: 'Administrator' }}</p>
                    </div>
                    <div class="profile-status" title="Online"></div>
                </a>
            @else
                <a href="{{ $layoutProfileUrl ?? route('admin.profile.show') }}" class="profile-avatar-centered" title="Profile" style="text-decoration:none;color:inherit;">
                    <div class="profile-avatar-small">{{ $layoutUserInitials }}</div>
                    <div class="profile-status-small"></div>
                </a>
            @endif
        </div>
        <div class="sidebar-logout">
            <a href="{{ $layoutProfileUrl ?? route('admin.profile.show') }}" class="logout-btn" title="Profile" style="margin-bottom:4px;">
                <div class="nav-icon-wrapper" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="nav-icon">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                </div>
                <span class="nav-text">Profile</span>
            </a>
            <form action="{{ route('admin.logout') }}" method="post">
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
@include('partials.legacy.sidebar-scripts')
