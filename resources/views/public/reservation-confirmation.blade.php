@php
    $primaryColor = $primaryColor ?? '#f20d0d';
    $restaurantName = $restaurant->name ?? 'Restaurant';
    $menuUrl = route('public.menu', $restaurant->slug);
    $reservationUrl = route('public.reservation', $restaurant->slug);
    $displayNumber = $reservation->reservation_number ?? $reservation->id;
    $currencySymbol = '₦';
@endphp
<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Confirmed - {{ $restaurantName }}</title>
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
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100 text-green-600 mb-4">
            @resmenuIcon('check_circle', ['size' => 40, 'class' => 'text-4xl'])
        </div>
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-2">Reservation Confirmed!</h1>
        <p class="text-gray-600">Your deposit has been paid. We look forward to seeing you.</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex justify-between items-center">
                <span class="text-sm font-medium text-gray-600">Reservation</span>
                <span class="text-lg font-bold" style="color:{{ $primaryColor }}">#{{ $displayNumber }}</span>
            </div>
        </div>
        <div class="p-6">
            <div class="space-y-2 text-sm text-gray-700">
                <p><strong>Date:</strong> {{ $reservation->reservation_date?->format('l, F j, Y') }}</p>
                <p><strong>Time:</strong> {{ \Illuminate\Support\Carbon::parse($reservation->reservation_time)->format('g:i A') }}</p>
                <p><strong>Guests:</strong> {{ (int) $reservation->party_size }}</p>
                <p><strong>Name:</strong> {{ $reservation->guest_name }}</p>
                <p><strong>Email:</strong> {{ $reservation->guest_email }}</p>
                <p><strong>Phone:</strong> {{ $reservation->guest_phone }}</p>
                @if((float) ($reservation->deposit_amount ?? 0) > 0)
                    <p><strong>Deposit paid:</strong> {{ $currencySymbol }}{{ number_format((float) $reservation->deposit_amount, 2) }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row gap-3 justify-center">
        <a href="{{ $menuUrl }}" class="inline-flex items-center justify-center gap-2 w-full sm:w-auto h-12 px-8 rounded-lg text-white font-bold text-base shadow-lg transition-all hover:opacity-90" style="background-color:{{ $primaryColor }}">
            @resmenuIcon('restaurant_menu', ['size' => 20]) View Menu
        </a>
        <a href="{{ $reservationUrl }}" class="inline-flex items-center justify-center gap-2 w-full sm:w-auto h-12 px-8 rounded-lg border-2 border-gray-200 text-gray-700 font-bold text-base transition-all hover:bg-gray-50">
            @resmenuIcon('event_seat', ['size' => 20]) Make Another Reservation
        </a>
    </div>
</main>
</body>
</html>
