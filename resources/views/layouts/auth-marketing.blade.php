<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sign in') | {{ $siteName ?? config('app.name', 'Resmenu') }}</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/resmenu-icons.css') }}">
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#f97415",
                        "background-light": "#f8f7f5",
                        "background-dark": "#111827",
                    },
                    fontFamily: {
                        display: ["Inter", "sans-serif"],
                        poppins: ["Poppins", "sans-serif"],
                    },
                    borderRadius: {
                        DEFAULT: "0.5rem",
                        lg: "1rem",
                        xl: "1.5rem",
                        full: "9999px",
                    },
                },
            },
        };
    </script>
    @stack('head')
</head>
<body class="bg-background-light dark:bg-background-dark font-display antialiased min-h-screen lg:h-screen overflow-x-hidden lg:overflow-hidden">
<div class="flex min-h-screen lg:h-screen flex-col lg:flex-row">
    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-primary">
        <div class="absolute inset-0 opacity-10" style="background-image: url('https://resmenu.net/assets/images/bh_pattern-black.png'); background-repeat: repeat; background-size: 280px 280px;"></div>
        <div class="absolute inset-0 z-10 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>
        <div class="relative z-20 flex h-full w-full flex-col justify-between p-12 text-white">
            <a href="{{ $marketingHomeUrl ?? 'https://resmenu.net/' }}" class="inline-flex items-center gap-3 hover:opacity-90 transition-opacity">
                @if(!empty($siteLogoUrl))
                    <img src="{{ $siteLogoUrl }}" alt="{{ $siteName }}" class="h-12 w-auto rounded-lg bg-white p-1.5">
                @else
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/20">
                        @resmenuIcon('restaurant_menu', ['size' => 24, 'class' => 'text-white'])
                    </div>
                    <span class="text-2xl font-bold tracking-tight font-poppins text-white">{{ $siteName }}</span>
                @endif
            </a>
            <div class="max-w-md">
                <h1 class="text-5xl font-extrabold leading-tight mb-6 font-poppins">Elevate Your Dining Experience</h1>
                <p class="text-xl text-slate-200">Join thousands of restaurants managing their digital menus with ease and style.</p>
                <div class="mt-8">
                    <div class="flex -space-x-2">
                        @foreach($showcaseRestaurantLogos ?? [] as $logo)
                            <div class="h-10 w-10 rounded-full ring-2 ring-white/50 bg-white/80 overflow-hidden">
                                <img src="{{ $logo }}" alt="Restaurant logo" class="h-full w-full object-cover">
                            </div>
                        @endforeach
                    </div>
                    <p class="mt-3 text-sm font-medium">Trusted by industry leaders</p>
                </div>
            </div>
            <div class="text-sm text-white/80">
                © {{ date('Y') }} {{ $siteName }}. All rights reserved.
            </div>
        </div>
    </div>
    <div class="flex flex-1 flex-col justify-center lg:justify-start px-4 py-6 sm:px-6 sm:py-8 lg:px-16 lg:py-8 xl:px-20 bg-background-light lg:overflow-y-auto">
        @yield('content')
    </div>
</div>
<script src="{{ asset('assets/js/resmenu-icons.js') }}"></script>
@stack('scripts')
</body>
</html>
