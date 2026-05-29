@php
    $primaryColor = $primaryColor ?? '#f20d0d';
    $restaurantName = $restaurantName ?? 'Restaurant';
    $menuUrl = $menuUrl ?? route('login');
@endphp
<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Failed - {{ $restaurantName }}</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('legacy/assets/css/resmenu-icons.css') }}">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { primary: @json($primaryColor) },
                    fontFamily: { display: ["Work Sans", "sans-serif"] },
                },
            },
        };
    </script>
</head>
<body class="bg-[#f2f4f7] font-display min-h-screen flex flex-col">
<header class="sticky top-0 z-50 flex items-center justify-between border-b border-gray-200 px-6 lg:px-10 py-3 bg-white">
    <a href="{{ $menuUrl }}" class="flex items-center gap-4 text-gray-900">
        @resmenuIcon('restaurant_menu', ['size' => 24, 'class' => 'text-2xl', 'style' => 'color:'.$primaryColor])
        <h2 class="text-xl font-bold">{{ $restaurantName }}</h2>
    </a>
</header>

<main class="flex-grow w-full max-w-[640px] mx-auto px-4 lg:px-10 py-12">
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-red-100 text-red-600 mb-4">
            @resmenuIcon('cancel', ['size' => 40, 'class' => 'text-4xl'])
        </div>
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-2">Payment Failed</h1>
        <p class="text-gray-600">{{ $message }}</p>
    </div>

    <div class="text-center">
        <a href="{{ $menuUrl }}" class="inline-flex items-center justify-center gap-2 w-full sm:w-auto h-14 px-8 rounded-lg text-white font-bold text-base shadow-lg transition-all hover:opacity-90" style="background-color:{{ $primaryColor }}">
            @resmenuIcon('arrow_back', ['size' => 20]) Back to Menu
        </a>
    </div>
</main>
</body>
</html>
