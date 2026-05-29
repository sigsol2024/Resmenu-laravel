@php
    $primaryColor = $primaryColor ?? '#f20d0d';
    $restaurantName = $restaurant->name ?? 'Restaurant';
    $slug = $restaurant->slug ?? '';
    $menuUrl = route('public.menu', $slug);
    $reservationUrl = route('public.reservation', $slug);
    $currencySymbol = '₦';
    $cart = json_decode($draft->cart_json ?? '[]', true);
    if (! is_array($cart)) {
        $cart = [];
    }
    $isReservation = (($draft->payment_type ?? 'order') === 'reservation') && ! empty($draft->reservation_id);
    $displayRef = 'BT-'.strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $token), 0, 8));
    $orderCreatedAtUnix = strtotime($draft->created_at ?? 'now');
@endphp
<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Bank Transfer - {{ $restaurantName }}</title>
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
    <div id="order-details-view">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 text-blue-600 mb-4">
                @resmenuIcon('account_balance', ['size' => 40, 'class' => 'text-4xl'])
            </div>
            <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-2">{{ $isReservation ? 'Complete Your Reservation Deposit' : 'Complete Your Payment' }}</h1>
            <p class="text-gray-600">Complete your bank transfer using the details below. You have 15 minutes.</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-600">Reference</span>
                    <span class="text-lg font-bold" style="color:{{ $primaryColor }}">#{{ $displayRef }}</span>
                </div>
            </div>
            <div class="p-6">
                <div class="mb-6 p-5 rounded-lg bg-amber-50 border border-amber-200">
                    <p class="text-sm font-bold text-gray-900 mb-3">Bank Transfer Details</p>
                    <p class="text-base font-bold text-gray-900 mb-1">Bank: {{ $bankTransferMethod->bank_name ?? '-' }}</p>
                    <p class="text-base font-bold text-gray-900 mb-1">Account Number: {{ $bankTransferMethod->account_number ?? '-' }}</p>
                    <p class="text-base font-bold text-gray-900">Account Name: {{ $bankTransferMethod->account_name ?? '-' }}</p>
                    <p class="text-sm text-amber-800 mt-3">Transfer exactly <strong>{{ $currencySymbol }}{{ number_format((float) ($draft->total ?? 0), 2) }}</strong> and use <strong>#{{ $displayRef }}</strong> as the reference.</p>
                </div>
                <div id="countdown-box" class="mb-6 flex items-center justify-center gap-2 py-3 px-4 rounded-lg bg-gray-100 text-gray-800">
                    @resmenuIcon('schedule', ['size' => 20])
                    <span id="countdown-text" class="font-mono font-bold text-lg">15:00</span>
                </div>
                <div class="mb-4">
                    <p class="text-xs font-medium text-gray-500 uppercase mb-1">{{ $isReservation ? 'Reservation for' : 'Delivery to' }}</p>
                    <p class="font-medium text-gray-900">{{ $draft->customer_name }}</p>
                    @unless($isReservation)
                        <p class="text-sm text-gray-600">{{ $draft->delivery_address }}</p>
                    @endunless
                </div>
                <div class="border-t border-gray-200 pt-4">
                    <p class="text-xs font-medium text-gray-500 uppercase mb-3">{{ $isReservation ? 'Reservation deposit' : 'Order summary' }}</p>
                    @if($isReservation && $reservation)
                        <div class="space-y-2 text-sm">
                            <p><strong>Date:</strong> {{ \Illuminate\Support\Carbon::parse($reservation->reservation_date)->format('M j, Y') }}</p>
                            <p><strong>Time:</strong> {{ \Illuminate\Support\Carbon::parse($reservation->reservation_time)->format('g:i A') }}</p>
                            <p><strong>Guests:</strong> {{ (int) $reservation->party_size }}</p>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach($cart as $item)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-900">{{ $item['name'] ?? '' }} × {{ max(1, (int) ($item['quantity'] ?? 1)) }}</span>
                                    <span class="font-medium text-gray-900">{{ $currencySymbol }}{{ number_format((float) ($item['price'] ?? 0) * max(1, (int) ($item['quantity'] ?? 1)), 2) }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    <div class="flex justify-between font-bold text-base mt-4 pt-4 border-t border-gray-200">
                        <span class="text-gray-900">Total</span>
                        <span style="color:{{ $primaryColor }}">{{ $currencySymbol }}{{ number_format((float) ($draft->total ?? 0), 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center">
            <button type="button" id="payment-confirmed-btn" class="inline-flex items-center justify-center gap-2 w-full sm:w-auto h-12 px-8 rounded-lg text-white font-bold text-base shadow-lg transition-all hover:opacity-90" style="background-color:{{ $primaryColor }}">
                @resmenuIcon('check_circle', ['size' => 20]) I have made this payment
            </button>
        </div>
    </div>

    <div id="thank-you-view" class="hidden">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100 text-green-600 mb-4">
                @resmenuIcon('check_circle', ['size' => 40, 'class' => 'text-4xl'])
            </div>
            <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-2">Thank you!</h1>
            <p class="text-gray-600">{{ $isReservation ? 'Your reservation deposit has been recorded. We look forward to seeing you!' : 'Your order has been recorded. It will be approved once payment is confirmed.' }}</p>
        </div>
        <div class="text-center">
            <a href="{{ $isReservation ? $reservationUrl : $menuUrl }}" class="inline-flex items-center justify-center gap-2 w-full sm:w-auto h-14 px-8 rounded-lg text-white font-bold text-base shadow-lg transition-all hover:opacity-90" style="background-color:{{ $primaryColor }}">
                @resmenuIcon('done', ['size' => 20]) {{ $isReservation ? 'Back to Reservation' : 'Done' }}
            </a>
        </div>
    </div>

    <div id="expired-view" class="hidden">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-red-100 text-red-600 mb-4">
                @resmenuIcon('cancel', ['size' => 40, 'class' => 'text-4xl'])
            </div>
            <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-2">{{ $isReservation ? 'Payment window expired' : 'Order invoice expired' }}</h1>
            <p class="text-gray-600">{{ $isReservation ? 'The payment window has expired. Please make a new reservation if you still wish to book.' : 'The payment window has expired. This order was not recorded. Please place a new order if you still wish to order.' }}</p>
        </div>
        <div class="text-center">
            <a href="{{ $isReservation ? $reservationUrl : $menuUrl }}" class="inline-flex items-center justify-center gap-2 w-full sm:w-auto h-14 px-8 rounded-lg text-white font-bold text-base shadow-lg transition-all hover:opacity-90" style="background-color:{{ $primaryColor }}">
                @resmenuIcon('arrow_back', ['size' => 20]) {{ $isReservation ? 'Back to Reservation' : 'Back to Menu' }}
            </a>
        </div>
    </div>
</main>

<script src="{{ asset('legacy/assets/js/cart.js') }}"></script>
<script>
(function() {
    var token = @json($token);
    var slug = @json($slug);
    var orderCreatedAtUnix = @json((int) $orderCreatedAtUnix);
    var endTime = new Date(orderCreatedAtUnix * 1000 + 15 * 60 * 1000);

    function updateCountdown() {
        var now = new Date();
        var diff = Math.max(0, Math.floor((endTime - now) / 1000));
        var mins = Math.floor(diff / 60);
        var secs = diff % 60;
        var el = document.getElementById('countdown-text');
        if (el) el.textContent = String(mins).padStart(2, '0') + ':' + String(secs).padStart(2, '0');
        if (diff <= 0) {
            var orderDetails = document.getElementById('order-details-view');
            var expiredView = document.getElementById('expired-view');
            if (orderDetails && expiredView) {
                orderDetails.classList.add('hidden');
                expiredView.classList.remove('hidden');
                fetch(@json(url('/api/bank-transfer/expire')), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': @json(csrf_token()),
                    },
                    body: JSON.stringify({ token: token }),
                }).catch(function() {});
            }
            return;
        }
        setTimeout(updateCountdown, 1000);
    }
    updateCountdown();

    document.getElementById('payment-confirmed-btn').addEventListener('click', function() {
        var btn = this;
        btn.disabled = true;
        fetch(@json(url('/api/bank-transfer/confirm')), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': @json(csrf_token()),
            },
            body: JSON.stringify({ token: token }),
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            var redirect = data.redirect || (data.data && data.data.redirect);
            if (data.success && redirect) {
                window.location.href = redirect;
            } else {
                btn.disabled = false;
                alert(data.message || 'Something went wrong. Please try again.');
            }
        })
        .catch(function() {
            btn.disabled = false;
            alert('Something went wrong. Please try again.');
        });
    });

    if (window.RESMENU_CART && slug) {
        window.RESMENU_CART.clearCart(slug);
    }
})();
</script>
</body>
</html>
