<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Admin') - Restaurant Menu Platform</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('legacy/assets/css/admin.css') }}">
<link rel="stylesheet" href="{{ asset('legacy/css/admin-shell.css') }}">
@stack('head')
</head>
<body>
<div class="app">
  @include('partials.admin-sidebar')
  <div class="content">
    <div class="header">
      <div>
        @if($showBackToDashboard ?? false)
          <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-small" style="text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 16px; height: 16px;">
              <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Dashboard
          </a>
        @endif
      </div>
      <div class="header-title">@yield('title', 'Admin')</div>
      <div></div>
    </div>
    <main>
      @if(session('success'))
        <div class="message message-success">{{ session('success') }}</div>
      @endif
      @if(session('error'))
        <div class="message message-error">{{ session('error') }}</div>
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
@include('partials.legacy.admin-footer')
@stack('scripts')
</body>
</html>
