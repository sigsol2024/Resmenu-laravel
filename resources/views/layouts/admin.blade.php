<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') — Resmenu</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/manager-shell.css') }}">
    @stack('head')
</head>
<body>
<div class="app">
    @include('partials.admin-sidebar')
    @include('partials.manager-sidebar-scripts')
    <div class="content">
        <div class="header">
            <div></div>
            <div class="header-title">@yield('title', 'Admin')</div>
            <div class="header-actions"></div>
        </div>
        <main>
            @include('partials.admin.flash-messages')
            @yield('content')
        </main>
    </div>
</div>
<script src="{{ asset('assets/js/actions-dropdown.js') }}"></script>
@stack('scripts')
</body>
</html>
