<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SigSol Resmenu') - Restaurant Menu Platform</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('legacy/assets/css/marketing.css') }}">
    @stack('head')
</head>
<body>
@include('partials.marketing.header')
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
@include('partials.marketing.footer')
<script src="{{ asset('legacy/assets/js/public.js') }}"></script>
@stack('scripts')
</body>
</html>
