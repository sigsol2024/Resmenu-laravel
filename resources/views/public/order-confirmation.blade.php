@php
    $primaryColor = $primaryColor ?? ($customization['primary_color'] ?? '#f20d0d');
    $restaurantName = $restaurant->name;
    $menuUrl = route('public.menu', $restaurant->slug);
    $isBankTransfer = ($order->payment_method ?? '') === 'bank_transfer';
    $currencySymbol = '₦';
@endphp
<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $isBankTransfer ? 'Order Details' : 'Thank You' }} - Order #{{ $order->displayNumber() }} - {{ $restaurantName }}</title>
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
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-2">
            {{ $isBankTransfer ? 'Thank you!' : 'Thank you for your order!' }}
        </h1>
        <p class="text-gray-600">
            @if($isBankTransfer)
                Your order has been recorded. It will be approved once the restaurant confirms your bank transfer.
            @else
                Your order has been received and is being processed.
            @endif
        </p>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex justify-between items-center">
                <span class="text-sm font-medium text-gray-600">Order Number</span>
                <span class="text-lg font-bold" style="color:{{ $primaryColor }}">#{{ $order->displayNumber() }}</span>
            </div>
        </div>
        <div class="p-6">
            <div class="mb-4">
                <p class="text-xs font-medium text-gray-500 uppercase mb-1">Delivery to</p>
                <p class="font-medium text-gray-900">{{ $order->customer_name }}</p>
                <p class="text-sm text-gray-600">{{ $order->delivery_address }}</p>
            </div>
            <div class="border-t border-gray-200 pt-4">
                <p class="text-xs font-medium text-gray-500 uppercase mb-3">Order summary</p>
                <div class="space-y-3">
                    @foreach($items as $item)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-900">{{ $item->name }} × {{ (int) $item->quantity }}</span>
                            <span class="font-medium text-gray-900">{{ $currencySymbol }}{{ number_format((float) $item->price * (int) $item->quantity, 2) }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="flex justify-between font-bold text-base mt-4 pt-4 border-t border-gray-200">
                    <span class="text-gray-900">Total</span>
                    <span style="color:{{ $primaryColor }}">{{ $currencySymbol }}{{ number_format((float) $order->total, 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center">
        <a href="{{ $menuUrl }}" class="inline-flex items-center justify-center gap-2 w-full sm:w-auto h-14 px-8 rounded-lg text-white font-bold text-base shadow-lg transition-all hover:opacity-90" style="background-color:{{ $primaryColor }}">
            @resmenuIcon('done', ['size' => 20]) Done
        </a>
    </div>
</main>

<script src="{{ asset('legacy/assets/js/cart.js') }}"></script>
<script>
(function() {
    var slug = @json($restaurant->slug);
    if (window.RESMENU_CART && slug) {
        window.RESMENU_CART.clearCart(slug);
    }
})();
</script>
</body>
</html>
