@php
    $primaryColor = $primaryColor ?? ($customization['primary_color'] ?? '#f20d0d');
    $logoUrls = [
        'paystack' => 'https://upload.wikimedia.org/wikipedia/commons/0/0b/Paystack_Logo.png',
        'flutterwave' => 'https://static.cdnlogo.com/logos/f/6/flutterwave.svg',
    ];
    $hasBankTransfer = collect($paymentMethods)->contains(fn ($m) => ($m['code'] ?? '') === 'bank_transfer');
@endphp
<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Checkout - {{ $restaurant->name }}</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/resmenu-icons.css') }}">
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: @json($primaryColor),
                        "background-light": "#f2f4f7",
                    },
                    fontFamily: { display: ["Work Sans", "sans-serif"] },
                },
            },
        };
    </script>
</head>
<body class="bg-[#f2f4f7] font-display min-h-screen flex flex-col">
<header class="sticky top-0 z-50 flex items-center justify-between whitespace-nowrap border-b border-solid border-gray-200 px-6 lg:px-10 py-3 bg-white">
    <a href="{{ $menuUrl }}" class="flex items-center gap-4 text-gray-900">
        @resmenuIcon('restaurant_menu', ['size' => 24, 'class' => 'text-2xl', 'style' => 'color:'.$primaryColor])
        <h2 class="text-xl font-bold">{{ $restaurant->name }}</h2>
    </a>
    <a href="{{ $menuUrl }}" class="text-sm font-medium text-gray-700 hover:opacity-80">Menu</a>
</header>

<main class="flex-grow w-full max-w-[1280px] mx-auto px-4 lg:px-10 py-8 lg:py-12">
    <div class="mb-10 text-center lg:text-left">
        <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-2">{{ $isReservationCheckout ? 'Reservation Deposit' : 'Secure Checkout' }}</h1>
        <p class="text-gray-600">{{ $isReservationCheckout ? 'Pay your reservation deposit to confirm your table.' : 'Complete your details below to finalize your order.' }}</p>
    </div>

    @if($errors->any())
        <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200 text-red-700">
            <ul class="list-disc list-inside space-y-1 text-sm">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form method="post" id="checkout-form" action="{{ route('public.checkout.submit', $restaurant->slug) }}" class="flex flex-col lg:flex-row gap-8 lg:gap-16">
        @csrf
        <input type="hidden" name="cart_json" id="cart-json-input" value="">
        @if($isReservationCheckout && $reservation)
            <input type="hidden" name="reservation_id" value="{{ $reservation->id }}">
        @endif

        <div class="flex-1 min-w-0">
            @unless($isReservationCheckout)
            <div class="mb-10">
                <div class="flex items-center justify-between w-full relative">
                    <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-0.5 bg-gray-200 -z-10"></div>
                    <div class="flex flex-col items-center gap-2 bg-[#f2f4f7] px-2">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full text-white font-bold shadow-lg ring-4 ring-[#f2f4f7]" style="background-color:{{ $primaryColor }}">1</div>
                        <span class="text-sm font-bold whitespace-nowrap" style="color:{{ $primaryColor }}">Delivery</span>
                    </div>
                    <div class="flex flex-col items-center gap-2 bg-[#f2f4f7] px-2">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-white border-2 border-gray-200 text-gray-500 font-medium ring-4 ring-[#f2f4f7]">2</div>
                        <span class="text-sm font-medium text-gray-500 whitespace-nowrap hidden sm:block">Payment</span>
                    </div>
                    <div class="flex flex-col items-center gap-2 bg-[#f2f4f7] px-2">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-white border-2 border-gray-200 text-gray-500 font-medium ring-4 ring-[#f2f4f7]">3</div>
                        <span class="text-sm font-medium text-gray-500 whitespace-nowrap hidden sm:block">Review</span>
                    </div>
                </div>
            </div>
            @endunless

            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 lg:p-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900">Contact Information</h3>
                    @unless($isReservationCheckout)
                        <span class="text-xs font-medium px-2 py-1 rounded" style="color:{{ $primaryColor }};background-color:rgba(242,13,13,0.1)">Step 1 of 3</span>
                    @endunless
                </div>
                <div class="grid grid-cols-1 gap-6 mb-8">
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700">Full Name</label>
                        <input name="customer_name" type="text" placeholder="e.g. Jonathan Doe" required
                            class="w-full h-12 px-4 rounded-lg border border-gray-200 bg-white text-gray-900 focus:border-primary focus:ring-1 focus:ring-primary placeholder-gray-400"
                            value="{{ old('customer_name', $reservation->guest_name ?? '') }}">
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700">Phone Number</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none">@resmenuIcon('call', ['size' => 20])</span>
                            <input name="customer_phone" type="tel" placeholder="(555) 000-0000" required
                                class="w-full h-12 pl-12 pr-4 rounded-lg border border-gray-200 bg-white text-gray-900 focus:border-primary focus:ring-1 focus:ring-primary placeholder-gray-400"
                                value="{{ old('customer_phone', $reservation->guest_phone ?? '') }}">
                        </div>
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700">Email Address</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none">@resmenuIcon('email', ['size' => 20])</span>
                            <input name="customer_email" type="email" placeholder="you@example.com" required
                                class="w-full h-12 pl-12 pr-4 rounded-lg border border-gray-200 bg-white text-gray-900 focus:border-primary focus:ring-1 focus:ring-primary placeholder-gray-400"
                                value="{{ old('customer_email', $reservation->guest_email ?? '') }}">
                        </div>
                    </div>
                </div>

                @unless($isReservationCheckout)
                <div class="flex items-center justify-between mb-6 pt-6 border-t border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900">Delivery Address</h3>
                </div>
                <div class="gap-6 mb-8">
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700">House Address</label>
                        <div class="relative">
                            <span class="absolute left-4 top-4 text-gray-500 pointer-events-none">@resmenuIcon('location_on', ['size' => 20])</span>
                            <textarea name="delivery_address" rows="3" placeholder="Street address, apartment/suite, city, state, zip" required
                                class="w-full min-h-[100px] pl-12 pr-4 py-3 rounded-lg border border-gray-200 bg-white text-gray-900 focus:border-primary focus:ring-1 focus:ring-primary placeholder-gray-400 resize-y">{{ old('delivery_address') }}</textarea>
                        </div>
                    </div>
                </div>
                @else
                    <input type="hidden" name="delivery_address" value="Table reservation">
                @endunless

                <div class="flex items-center justify-between mb-6 pt-6 border-t border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900">Payment Method</h3>
                </div>
                @if(empty($paymentMethods))
                    <div class="mb-8 p-4 rounded-lg bg-amber-50 border border-amber-200 text-amber-800 text-sm">
                        No payment methods configured. Please contact the restaurant.
                    </div>
                @else
                    <div class="flex flex-wrap gap-4 mb-8">
                        @foreach($paymentMethods as $pm)
                            @php $code = $pm['code'] ?? ''; @endphp
                            <label class="flex-1 min-w-[140px] cursor-pointer">
                                <input type="radio" name="payment_method" value="{{ $code }}" class="peer sr-only"
                                    @checked(old('payment_method') === $code || ($loop->first && !old('payment_method')))>
                                <div class="border-2 border-gray-200 rounded-lg p-4 flex flex-col items-center justify-center gap-2 peer-checked:border-primary peer-checked:ring-2 peer-checked:ring-primary transition-all">
                                    @if(!empty($logoUrls[$code]))
                                        <img src="{{ $logoUrls[$code] }}" alt="{{ $pm['label'] }}" class="h-8 object-contain max-w-[120px]" loading="lazy">
                                    @else
                                        @resmenuIcon('account_balance', ['size' => 24, 'class' => 'text-gray-500'])
                                    @endif
                                    <span class="font-medium text-gray-900 text-center text-sm">{{ $pm['label'] }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @if($hasBankTransfer)
                        <div id="bank-transfer-note" class="mb-8 p-4 rounded-lg bg-blue-50 border border-blue-200" style="display:none">
                            <p class="text-sm text-gray-700">After placing your order, you'll be redirected to the Order Details page where you will find our bank account information. Please complete the transfer within 15 minutes to confirm your order.</p>
                        </div>
                    @endif
                @endif
            </div>
        </div>

        <div class="w-full lg:w-[380px] flex-shrink-0">
            <div class="sticky top-24 bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        @resmenuIcon($isReservationCheckout ? 'event_seat' : 'receipt_long', ['size' => 24, 'style' => 'color:'.$primaryColor])
                        {{ $isReservationCheckout ? 'Reservation Summary' : 'Order Summary' }}
                    </h3>
                </div>
                <div class="p-6 flex flex-col gap-6">
                    @if($isReservationCheckout && $reservation)
                        <div id="checkout-reservation-summary" class="space-y-2 text-sm text-gray-700">
                            <p><strong>Date:</strong> {{ $reservation->reservation_date?->format('M j, Y') }}</p>
                            <p><strong>Time:</strong> {{ \Illuminate\Support\Carbon::parse($reservation->reservation_time)->format('g:i A') }}</p>
                            <p><strong>Guests:</strong> {{ (int) $reservation->party_size }}</p>
                            <p><strong>Deposit:</strong> {{ $currencySymbol }}{{ number_format((float) $reservation->deposit_amount, 2) }}</p>
                        </div>
                    @else
                        <div id="checkout-order-items" class="flex flex-col gap-4 max-h-[300px] overflow-y-auto pr-2"></div>
                    @endif
                    <div class="h-px bg-gray-200 w-full"></div>
                    <div class="flex flex-col gap-2 pt-2">
                        @unless($isReservationCheckout)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal</span>
                                <span id="checkout-subtotal" class="font-medium text-gray-900">{{ $currencySymbol }}0.00</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Delivery Fee</span>
                                <span id="checkout-delivery" class="font-medium text-gray-900">{{ $currencySymbol }}0.00</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Tax</span>
                                <span id="checkout-tax" class="font-medium text-gray-900">{{ $currencySymbol }}0.00</span>
                            </div>
                        @endunless
                    </div>
                    <div class="flex justify-between items-end pt-4 border-t border-dashed border-gray-200">
                        <span class="text-base font-bold text-gray-900">Total</span>
                        <span id="checkout-total" class="text-2xl font-bold" style="color:{{ $primaryColor }}">
                            @if($isReservationCheckout && $reservation)
                                {{ $currencySymbol }}{{ number_format((float) $reservation->deposit_amount, 2) }}
                            @else
                                {{ $currencySymbol }}0.00
                            @endif
                        </span>
                    </div>
                    <button type="submit" class="w-full mt-4 h-14 px-6 rounded-lg text-white font-bold text-base shadow-lg transition-all flex items-center justify-center gap-2 group" style="background-color:{{ $primaryColor }}">
                        {{ $isReservationCheckout ? 'Pay Deposit' : 'Proceed to Payment' }}
                        @resmenuIcon('arrow_forward', ['size' => 20, 'class' => 'group-hover:translate-x-1 transition-transform'])
                    </button>
                    <a href="{{ $isReservationCheckout ? $reservationUrl : $menuUrl }}" class="inline-flex items-center justify-center gap-1.5 py-2 text-sm text-gray-500 hover:text-gray-700 transition-colors">
                        @resmenuIcon('arrow_back', ['size' => 16, 'class' => 'text-base'])
                        {{ $isReservationCheckout ? 'Back to Reservation' : 'Back to Menu' }}
                    </a>
                </div>
                <div class="bg-gray-50 p-4 text-center">
                    <p class="text-xs text-gray-600 flex items-center justify-center gap-1">
                        @resmenuIcon('lock', ['size' => 16, 'class' => 'text-sm'])
                        Secure 256-bit SSL Encrypted Payment
                    </p>
                </div>
            </div>
        </div>
    </form>
</main>

<footer class="mt-auto border-t border-gray-200 bg-white py-10 px-6 lg:px-10">
    <div class="flex flex-col md:flex-row justify-between items-center gap-6 max-w-[1280px] mx-auto">
        <span class="text-sm font-bold text-gray-900">{{ $restaurant->name }}</span>
        <p class="text-xs text-gray-600">© {{ date('Y') }} {{ $restaurant->name }}. All rights reserved.</p>
    </div>
</footer>

<script src="{{ asset('assets/js/cart.js') }}"></script>
<script>
(function() {
    const isReservation = @json($isReservationCheckout);
    const slug = @json($restaurant->slug);
    const symbol = @json($currencySymbol);
    const uploadBaseUrl = @json($uploadBaseUrl);
    const deliveryFee = @json($deliveryFee);
    const taxRate = @json($taxRate);
    const queryCart = @json($cartJson);

    document.querySelectorAll('input[name="payment_method"]').forEach(function(r) {
        r.addEventListener('change', function() {
            var note = document.getElementById('bank-transfer-note');
            if (note) note.style.display = this.value === 'bank_transfer' ? 'block' : 'none';
        });
        if (r.checked && r.value === 'bank_transfer') {
            var note = document.getElementById('bank-transfer-note');
            if (note) note.style.display = 'block';
        }
    });

    if (isReservation) {
        document.getElementById('cart-json-input').value = '[]';
    } else {
        const CART = window.RESMENU_CART;
        let items = CART ? CART.getCart(slug) : [];
        if ((!items || !items.length) && queryCart) {
            try {
                const parsed = JSON.parse(queryCart);
                if (Array.isArray(parsed) && parsed.length) items = parsed;
            } catch (e) {}
        }
        const subtotal = CART ? CART.getTotalAmount(slug) : items.reduce((s, i) => s + (parseFloat(i.price) || 0) * (i.quantity || 1), 0);
        const tax = subtotal * taxRate;
        const total = subtotal + deliveryFee + tax;

        document.getElementById('cart-json-input').value = JSON.stringify(items);

        const itemsEl = document.getElementById('checkout-order-items');
        if (itemsEl) {
            const itemsHtml = (items || []).map(function(item) {
                const imgUrl = item.image ? (uploadBaseUrl + '/menu-items/' + item.image) : '';
                const imgStyle = imgUrl ? "background-image:url('" + imgUrl.replace(/'/g, "\\'") + "')" : 'background:#e5e5e5';
                const lineTotal = (parseFloat(item.price) || 0) * (item.quantity || 1);
                const fmt = CART ? CART.formatPrice(lineTotal, symbol) : symbol + lineTotal.toFixed(2);
                return '<div class="flex gap-4"><div class="w-16 h-16 rounded-lg bg-gray-100 overflow-hidden flex-shrink-0 bg-cover bg-center" style="' + imgStyle + '"></div><div class="flex-1 flex flex-col justify-between"><div class="flex justify-between items-start"><p class="text-sm font-bold text-gray-900">' + (item.name || '').replace(/</g, '&lt;') + '</p><p class="text-sm font-bold text-gray-900">' + fmt + '</p></div><p class="text-xs text-gray-600">Qty: ' + (item.quantity || 1) + '</p></div></div>';
            }).join('');
            itemsEl.innerHTML = itemsHtml || '<p class="text-gray-600 py-4">No items in cart.</p>';
        }

        const fmt = (n) => CART ? CART.formatPrice(n, symbol) : symbol + Number(n).toFixed(2);
        const subEl = document.getElementById('checkout-subtotal');
        if (subEl) subEl.textContent = fmt(subtotal);
        const delEl = document.getElementById('checkout-delivery');
        if (delEl) delEl.textContent = fmt(deliveryFee);
        const taxEl = document.getElementById('checkout-tax');
        if (taxEl) taxEl.textContent = fmt(tax);
        const totalEl = document.getElementById('checkout-total');
        if (totalEl) totalEl.textContent = fmt(total);

        document.getElementById('checkout-form').addEventListener('submit', function(e) {
            if (!items || items.length === 0) {
                e.preventDefault();
                alert('Your cart is empty. Please add items before checkout.');
            }
        });
    }
})();
</script>
</body>
</html>
