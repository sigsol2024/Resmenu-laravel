<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Table Reservation | {{ $restaurantName }}</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Epilogue:wght@300;400;500;600;700;800;900&amp;display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="{{ asset('legacy/assets/css/resmenu-icons.css') }}">
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "{{ $primaryColor }}",
                        "background-light": "{{ $bgColor }}",
                        "background-dark": "#221010",
                    },
                    fontFamily: { "display": ["Epilogue", "sans-serif"] },
                    borderRadius: { "DEFAULT": "0.5rem", "lg": "1rem", "xl": "1.5rem", "full": "9999px" },
                },
            },
        }
    </script>
    <style>
        body { font-family: 'Epilogue', sans-serif; }
        .hero-overlay { background: linear-gradient(rgba(34, 16, 16, 0.7), rgba(34, 16, 16, 0.85)); }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-white min-h-screen">
<!-- Hero Background -->
<div class="fixed inset-0 z-0">
    <img class="w-full h-full object-cover" alt="Restaurant interior" src="{{ $heroBgImage }}"/>
    <div class="absolute inset-0 hero-overlay"></div>
</div>

<!-- Navigation -->
<nav class="relative z-50 flex items-center px-6 md:px-8 py-6 max-w-7xl mx-auto w-full">
    <a href="{{ $menuUrl }}" class="flex items-center space-x-2">
        @if($restaurant->logo)
            <img src="{{ $uploadBaseUrl }}/logos/{{ $restaurant->logo }}" alt="{{ $restaurantName }}" class="h-10 w-auto object-contain">
        @else
            <div class="w-10 h-10 bg-primary flex items-center justify-center rounded-lg">
                @resmenuIcon('restaurant', ['size' => 24, 'class' => 'text-white'])
            </div>
            <span class="text-xl md:text-2xl font-extrabold tracking-tighter text-white uppercase">{{ $restaurantName }}</span>
        @endif
    </a>
    <a class="ml-auto text-sm font-medium text-white/80 hover:text-primary transition-colors" href="{{ $menuUrl }}">OUR MENU</a>
</nav>

<!-- Main Content -->
<main class="relative z-10 max-w-4xl mx-auto px-4 py-8 md:py-10">
    <div class="bg-white rounded-xl shadow-xl overflow-hidden border border-gray-200">
        <div class="p-6 md:p-8">
            @if($success)
                <header class="text-center mb-10">
                    <h1 class="text-3xl md:text-4xl font-bold mb-2 dark:text-white">Reservation Confirmed</h1>
                    <p class="text-slate-500 dark:text-slate-400">Thank you! We look forward to seeing you.</p>
                </header>
                <div class="text-center space-y-4">
                    <p class="text-slate-600 dark:text-slate-300">You will receive a confirmation shortly. If you need to modify your reservation, please contact us.</p>
                    <a href="{{ $menuUrl }}" class="inline-block py-3 px-8 bg-primary hover:bg-red-700 text-white font-bold rounded-lg transition-colors">Back to Menu</a>
                </div>
            @else
                <header class="text-center mb-10">
                    <h1 class="text-3xl md:text-4xl font-bold mb-2 dark:text-white">Book Your Table</h1>
                    <p class="text-slate-500 dark:text-slate-400">Join us for an unforgettable culinary experience.</p>
                </header>

                @if($errors->isNotEmpty())
                <div class="mb-6 p-4 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300">
                    <ul class="list-disc list-inside space-y-1 text-sm">
                        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                    </ul>
                </div>
                @endif

                <!-- Progress steps (compact) -->
                <div class="mb-6 md:mb-8">
                    <div class="flex items-center justify-between w-full text-xs md:text-sm">
                        <div class="flex items-center gap-2">
                            <div class="res-step-indicator w-8 h-8 md:w-9 md:h-9 rounded-full font-semibold shadow-md ring-2 flex items-center justify-center" id="step-ind-1" data-step="1" style="background-color:{{ $primaryColor }};color:white;ring-color:{{ $bgColor }}">1</div>
                            <span class="font-semibold text-gray-700">Date &amp; Time</span>
                        </div>
                        <div class="flex-1 h-px mx-2 bg-gray-200 hidden sm:block"></div>
                        <div class="flex items-center gap-2">
                            <div class="res-step-indicator w-8 h-8 md:w-9 md:h-9 rounded-full bg-white border border-gray-300 text-gray-500 font-medium ring-2 flex items-center justify-center" id="step-ind-2" data-step="2" style="ring-color:{{ $bgColor }}">2</div>
                            <span class="font-medium text-gray-600 hidden xs:inline">Guest Info</span>
                        </div>
                        <div class="flex-1 h-px mx-2 bg-gray-200 hidden sm:block"></div>
                        <div class="flex items-center gap-2">
                            <div class="res-step-indicator w-8 h-8 md:w-9 md:h-9 rounded-full bg-white border border-gray-300 text-gray-500 font-medium ring-2 flex items-center justify-center" id="step-ind-3" data-step="3" style="ring-color:{{ $bgColor }}">3</div>
                            <span class="font-medium text-gray-600 hidden md:inline">Requests</span>
                        </div>
                        <div class="flex-1 h-px mx-2 bg-gray-200 hidden sm:block"></div>
                        <div class="flex items-center gap-2">
                            <div class="res-step-indicator w-8 h-8 md:w-9 md:h-9 rounded-full bg-white border border-gray-300 text-gray-500 font-medium ring-2 flex items-center justify-center" id="step-ind-4" data-step="4" style="ring-color:{{ $bgColor }}">4</div>
                            <span class="font-medium text-gray-600 hidden lg:inline">Confirm</span>
                        </div>
                    </div>
                </div>

                <form method="post" id="reservation-form" action="{{ route("public.reservation", $restaurant->slug) }}">@csrf
                    <input type="hidden" name="slug" value="{{ $restaurant->slug }}"/>
                    <input type="hidden" name="party_size" id="party-size-input" value="{{ (int) old("party_size", 1) }}"/>

                    <!-- Step 1: Date, Number of Guests, Time -->
                    <div class="res-step" data-step="1">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-semibold uppercase tracking-wider mb-3 text-gray-700">Select Date</label>
                                <input type="hidden" name="reservation_date" id="reservation-date-input" value="{{ old("reservation_date", $selectedDate) }}" required/>
                                <div id="reservation-date-trigger" class="border border-gray-200 rounded-lg p-4 bg-gray-50 cursor-pointer hover:border-gray-300 transition-colors flex items-center justify-between" role="button" tabindex="0">
                                    <span id="res-date-display" class="text-gray-600 font-medium">Click to select date</span>
                                    @resmenuIcon('expand_more', ['size' => 20, 'class' => 'text-gray-500 text-lg'])
                                </div>
                                <div id="reservation-calendar-wrap" class="border border-gray-200 rounded-lg p-4 bg-gray-50 mt-3 hidden">
                                    <div class="flex justify-between items-center mb-4">
                                        <button type="button" id="res-cal-prev" class="p-2 rounded hover:bg-gray-200 text-gray-600">@resmenuIcon('chevron_left', ['size' => 20, 'class' => 'text-lg'])</button>
                                        <span id="res-cal-month" class="font-bold text-gray-800 text-sm"></span>
                                        <button type="button" id="res-cal-next" class="p-2 rounded hover:bg-gray-200 text-gray-600">@resmenuIcon('chevron_right', ['size' => 20, 'class' => 'text-lg'])</button>
                                    </div>
                                    <div id="reservation-calendar" class="grid grid-cols-7 gap-1 text-center text-xs"></div>
                                    <p id="res-cal-legend" class="mt-3 text-xs text-gray-500 flex flex-wrap gap-4"><span><span class="inline-block w-3 h-3 rounded bg-green-500 mr-1"></span>Available</span><span><span class="inline-block w-3 h-3 rounded bg-amber-400 mr-1"></span>Limited</span><span><span class="inline-block w-3 h-3 rounded bg-gray-300 mr-1"></span>Full</span></p>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold uppercase tracking-wider mb-3 text-gray-700">Number of Guests</label>
                                <div class="flex items-center justify-between px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg">
                                    <button type="button" id="party-minus" class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 border border-gray-300 text-gray-700 hover:bg-primary hover:text-white hover:border-primary transition-colors shadow-sm">
                                        @resmenuIcon('remove', ['size' => 16, 'class' => 'text-sm'])
                                    </button>
                                    <span id="party-display" class="font-bold text-lg px-4 text-gray-900">{{ (int) old("party_size", 1) }} Guest{{ (int) old("party_size", 1) !== 1 ? "s" : "" }}</span>
                                    <button type="button" id="party-plus" class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 border border-gray-300 text-gray-700 hover:bg-primary hover:text-white hover:border-primary transition-colors shadow-sm">
                                        @resmenuIcon('add', ['size' => 16, 'class' => 'text-sm'])
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold uppercase tracking-wider mb-4 text-gray-700 text-center">Available Time Slots</label>
                            <div id="time-slots-container" class="grid grid-cols-3 md:grid-cols-6 gap-3">
                                @foreach($timeSlots as $slot)
                                <button type="button" data-time="{{ $slot["time"] }}"
                                    class="time-slot py-3 px-2 text-sm font-bold rounded-lg transition-all border
                                    {{ $slot["available"] ? "border-gray-200 hover:border-primary text-gray-700" : "opacity-50 cursor-not-allowed line-through border-gray-200 text-gray-500" }}"
                                    {{ $slot["available"] ? "" : "disabled" }}>
                                    {{ $slot["time"] }}
                                </button>
                                @endforeach
                            </div>
                            <input type="hidden" name="reservation_time" id="reservation-time-input" value="{{ old("reservation_time") }}" required/>
                        </div>
                        <div class="flex justify-end mt-8">
                            <button type="button" class="res-next-btn px-8 py-3 font-bold rounded-lg text-white" style="background-color:{{ $primaryColor }}">Next</button>
                        </div>
                    </div>

                    <!-- Step 2: Guest Info -->
                    <div class="res-step hidden" data-step="2">
                        <label class="block text-sm font-semibold uppercase tracking-wider mb-4 text-gray-700">Guest Information</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <input name="guest_name" type="text" placeholder="Full Name" required
                                value="{{ old("guest_name") }}"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary text-gray-900"/>
                            <input name="guest_email" type="email" placeholder="Email Address" required
                                value="{{ old("guest_email") }}"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary text-gray-900"/>
                        </div>
                        <input name="guest_phone" type="tel" placeholder="Phone Number (numbers only)" required inputmode="numeric"
                            value="{{ old("guest_phone") }}"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary text-gray-900 mb-8"/>
                        <div class="flex justify-between">
                            <button type="button" class="res-back-btn px-8 py-3 font-bold rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">Back</button>
                            <button type="button" class="res-next-btn px-8 py-3 font-bold rounded-lg text-white" style="background-color:{{ $primaryColor }}">Next</button>
                        </div>
                    </div>

                    <!-- Step 3: Special Requests -->
                    <div class="res-step hidden" data-step="3">
                        <label class="block text-sm font-semibold uppercase tracking-wider mb-2 text-gray-700">Special Requests</label>
                        <div class="flex flex-wrap gap-2 mb-4">
                            @php $occasions = ['BIRTHDAY','ANNIVERSARY','BUSINESS','DATE_NIGHT']; $selectedOccasion = old('special_occasion', ''); @endphp
                            @foreach($occasions as $occ)
                                <button type="button" data-occasion="{{ $occ }}"
                                    class="occasion-btn px-4 py-2 text-xs font-bold rounded-full transition-colors border
                                    {{ $selectedOccasion === $occ ? "border-primary text-white" : "border-gray-200 bg-gray-50 text-gray-600 hover:bg-gray-100" }}"
                                    style="{{ $selectedOccasion === $occ ? "background-color:".$primaryColor : "" }}">
                                    {{ $occ }}
                                </button>
                            @endforeach
                        </div>
                        <input type="hidden" name="special_occasion" id="special-occasion-input" value="{{ old('special_occasion') }}"/>
                        <textarea name="notes" rows="3" placeholder="Dietary requirements or additional notes..."
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary text-gray-900 resize-none mb-8">{{ old("notes") }}</textarea>
                        <div class="flex justify-between">
                            <button type="button" class="res-back-btn px-8 py-3 font-bold rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">Back</button>
                            <button type="button" class="res-next-btn px-8 py-3 font-bold rounded-lg text-white" style="background-color:{{ $primaryColor }}">Next</button>
                        </div>
                    </div>

                    <!-- Step 4: Review & Confirm -->
                    <div class="res-step hidden" data-step="4">
                        <div id="res-review-summary" class="mb-6 p-4 bg-gray-50 border border-gray-200 rounded-lg text-gray-700 space-y-2 text-sm"></div>
                        @if($depositAmount > 0)
                        <p class="mb-4 text-gray-600">A deposit of <strong>₦{{ number_format($depositAmount, 2) }}</strong> will be required at checkout.</p>
                        @endif
                        <div class="flex justify-between">
                            <button type="button" class="res-back-btn px-8 py-3 font-bold rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">Back</button>
                            <button type="submit" class="px-8 py-3 font-bold rounded-lg text-white" style="background-color:{{ $primaryColor }}">Confirm Reservation</button>
                        </div>
                        <p class="text-center text-xs text-gray-500 mt-4">By booking, you agree to our terms and cancellation policy.</p>
                    </div>
                </form>
            @endif
        </div>
    </div>
</main>

<!-- Footer -->
<footer class="relative z-10 mt-20 bg-zinc-900 text-white py-16">
    <div class="max-w-7xl mx-auto px-6 md:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
            <div class="space-y-4">
                <div class="flex items-center space-x-2 mb-6">
                    @if($restaurant->logo)
                        <img src="{{ $uploadBaseUrl }}/logos/{{ $restaurant->logo }}" alt="{{ $restaurantName }}" class="h-10 w-auto object-contain">
                    @else
                        <div class="w-8 h-8 bg-primary flex items-center justify-center rounded-lg">
                            @resmenuIcon('restaurant', ['size' => 18, 'class' => 'text-white text-sm'])
                        </div>
                        <span class="text-xl font-extrabold tracking-tighter uppercase">{{ $restaurantName }}</span>
                    @endif
                </div>
                @if($restaurant->footer_content)
                    <p class="text-zinc-400 text-sm leading-relaxed">{!! nl2br(e($restaurant->footer_content)) !!}</p>
                @elseif($restaurant->description)
                    <p class="text-zinc-400 text-sm leading-relaxed">{{ $restaurant->description }}</p>
                @endif
                <div class="flex gap-4">
                    @if($restaurant->instagram_url)
                        <a class="w-10 h-10 rounded-full border border-zinc-700 flex items-center justify-center hover:bg-primary hover:border-primary transition-colors" href="{{ $restaurant->instagram_url }}" target="_blank" rel="noopener">
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073z"/></svg>
                        </a>
                    @endif
                    @if($restaurant->facebook_url)
                        <a class="w-10 h-10 rounded-full border border-zinc-700 flex items-center justify-center hover:bg-primary hover:border-primary transition-colors" href="{{ $restaurant->facebook_url }}" target="_blank" rel="noopener">
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg>
                        </a>
                    @endif
                    @if($restaurant->twitter_url)
                        <a class="w-10 h-10 rounded-full border border-zinc-700 flex items-center justify-center hover:bg-primary hover:border-primary transition-colors" href="{{ $restaurant->twitter_url }}" target="_blank" rel="noopener">
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                        </a>
                    @endif
                </div>
            </div>
            @if($restaurant->address || $restaurant->phone || $restaurant->email)
            <div>
                <h4 class="font-bold text-sm uppercase tracking-widest mb-6">Contact</h4>
                <ul class="space-y-3 text-zinc-400 text-sm">
                    @if($restaurant->address)
                        <li class="flex items-center gap-3">@resmenuIcon('place', ['size' => 16, 'class' => 'text-primary text-sm']) {!! nl2br(e($restaurant->address)) !!}</li>
                    @endif
                    @if($restaurant->phone)
                        <li class="flex items-center gap-3">@resmenuIcon('phone', ['size' => 16, 'class' => 'text-primary text-sm']) <a href="tel:{{ preg_replace('/\s+/', '', $restaurant->phone) }}" class="hover:text-white">{{ $restaurant->phone }}</a></li>
                    @endif
                    @if($restaurant->email)
                        <li class="flex items-center gap-3">@resmenuIcon('email', ['size' => 16, 'class' => 'text-primary text-sm']) <a href="mailto:{{ $restaurant->email }}" class="hover:text-white">{{ $restaurant->email }}</a></li>
                    @endif
                </ul>
            </div>
            @endif
        </div>
        <div class="border-t border-zinc-800 mt-16 pt-8 text-center text-xs text-zinc-500 uppercase tracking-widest">
            &copy; {{ date('Y') }} {{ $restaurantName }}. All Rights Reserved.
        </div>
    </div>
</footer>

@if(!$success)
<script>
window.RESERVATION_CONFIG = @json([
    'primaryColor' => $primaryColor,
    'baseUrl' => rtrim(url('/'), '/'),
    'slug' => $restaurant->slug,
    'partySize' => (int) old('party_size', 1),
    'minDate' => $minDate,
    'slotsUrl' => url('/api/reservations/slots'),
    'availabilityUrl' => url('/api/reservations/availability'),
]);
</script>
<script src="{{ asset('assets/js/reservation-wizard.js') }}"></script>
@endif
</body>
</html>
