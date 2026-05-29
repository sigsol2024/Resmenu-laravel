<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Manager') - Restaurant Menu Platform</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('legacy/assets/css/admin.css') }}">
<link rel="stylesheet" href="{{ asset('legacy/css/manager-shell.css') }}">
@stack('head')
</head>
<body>
<div class="app">
  @include('partials.manager-sidebar')
  <div class="content">
    <div class="header">
      <div></div>
      <div class="header-title">@yield('title', $layoutPageTitle ?? 'Manager')</div>
      <div class="header-actions">
        @if($layoutRestaurant?->slug ?? false)
          <a href="{{ route('public.menu', $layoutRestaurant->slug) }}" target="_blank" rel="noopener" class="btn-view-menu" style="display:inline-flex;align-items:center;gap:6px;text-decoration:none;padding:8px 14px;border-radius:8px;background:#f3f4f6;color:#374151;font-size:0.875rem;font-weight:500;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:18px;height:18px;">
              <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <span>View Menu</span>
          </a>
        @endif
      </div>
    </div>
    <main>
      @if(session('success'))
        <div class="message message-success">{{ session('success') }}</div>
      @endif
      @if($errors->any())
        <div class="message message-error">
          @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
        </div>
      @endif
      @yield('content')
    </main>
  </div>
</div>
<script src="{{ asset('legacy/assets/js/actions-dropdown.js') }}"></script>
@include('partials.legacy.sidebar-scripts')
<script src="{{ asset('legacy/assets/js/admin.js') }}"></script>
@include('partials.legacy.password-toggle')
@stack('scripts')
</body>
</html>
