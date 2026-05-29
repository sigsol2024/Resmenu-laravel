@extends('layouts.auth-marketing')

@section('title', 'Register')
@section('auth_panel_class', 'bg-primary/10')

@section('auth_panel')
    <div class="absolute inset-0 z-10 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>
    <img class="absolute inset-0 h-full w-full object-cover object-center" alt="Professional chef at work" src="{{ asset('legacy/assets/images/woman_work.jpg') }}">
    <div class="relative z-20 flex h-full w-full flex-col justify-between p-12 text-white">
        <a href="{{ $marketingHomeUrl }}" class="inline-flex items-center gap-3 hover:opacity-90 transition-opacity">
            @if(!empty($siteLogoUrl))
                <img src="{{ $siteLogoUrl }}" alt="{{ $siteName }}" class="h-12 w-auto rounded-lg bg-white p-1.5">
            @else
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary">
                    @resmenuIcon('restaurant_menu', ['size' => 24, 'class' => 'text-white'])
                </div>
                <span class="text-2xl font-bold tracking-tight font-poppins text-white">{{ $siteName }}</span>
            @endif
        </a>
        <div class="max-w-md">
            <h1 class="text-5xl font-extrabold leading-tight mb-6 font-poppins">Join 1,000+ restaurants worldwide.</h1>
            <p class="text-xl text-slate-200">Create your restaurant account, start a 7-day Professional trial, and manage everything from one dashboard.</p>
            <div class="mt-8">
                <div class="flex -space-x-2">
                    @foreach($showcaseRestaurantLogos ?? [] as $logo)
                        <div class="h-10 w-10 rounded-full ring-2 ring-primary/70 bg-white/80 overflow-hidden">
                            <img src="{{ $logo }}" alt="Restaurant logo" class="h-full w-full object-cover">
                        </div>
                    @endforeach
                </div>
                <p class="mt-3 text-sm font-medium">Trusted by industry leaders</p>
            </div>
        </div>
        <div class="text-sm text-slate-300">
            © {{ date('Y') }} {{ $siteName }}. All rights reserved.
        </div>
    </div>
@endsection

@push('head')
    @if(!empty($recaptchaSiteKey))
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
@endpush

@section('content')
<div class="mb-6 flex items-center justify-between gap-4">
    <a href="{{ $marketingHomeUrl }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-primary transition-colors">
        @resmenuIcon('arrow_back', ['size' => 16, 'class' => 'text-base'])
        Back to Home
    </a>
    <a href="{{ $marketingHomeUrl }}" class="inline-flex items-center gap-2 hover:opacity-90 transition-opacity lg:hidden">
        @if(!empty($siteLogoUrl))
            <img src="{{ $siteLogoUrl }}" alt="{{ $siteName }}" class="h-10 w-auto">
        @else
            @resmenuIcon('restaurant_menu', ['size' => 28, 'class' => 'text-primary text-3xl'])
            <span class="text-lg font-bold font-poppins text-slate-900">{{ $siteName }}</span>
        @endif
    </a>
</div>

<div class="mx-auto w-full max-w-md">
    <div class="mb-6">
        <h2 class="text-3xl font-extrabold tracking-tight text-slate-900 font-poppins">Create Your Account</h2>
        <p class="mt-2 text-slate-500">7-day Professional trial starts immediately after registration.</p>
    </div>

    @if($errors->any())
        <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
        </div>
    @endif

    @if(session('success'))
        <div class="mb-5 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-6">
        <div class="h-2 w-full rounded-full bg-slate-200 overflow-hidden">
            <div id="progressFill" class="h-full bg-primary transition-all duration-300" style="width: 33%"></div>
        </div>
        <p id="progressText" class="mt-2 text-xs text-slate-500">Step 1 of 3 - Restaurant Details</p>
    </div>

    <form id="registerForm" class="space-y-5" method="post" action="{{ route('register.submit') }}">
        @csrf
        @if(!empty($plan))<input type="hidden" name="plan_id" value="{{ $plan }}">@endif

        <div class="space-y-5" data-step="1">
            <h3 class="text-lg font-bold text-slate-900">Restaurant Details</h3>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5" for="restaurant_name">Restaurant Name *</label>
                <input class="block w-full rounded-lg border-slate-200 bg-white px-4 py-3 text-slate-900 placeholder:text-slate-400 focus:border-primary focus:ring-primary sm:text-sm shadow-sm" id="restaurant_name" name="restaurant_name" placeholder="The Tasty Bistro" type="text" value="{{ old('restaurant_name') }}" required>
            </div>
        </div>

        <div class="space-y-5 hidden" data-step="2">
            <h3 class="text-lg font-bold text-slate-900">Manager Account</h3>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5" for="username">Username *</label>
                <input class="block w-full rounded-lg border-slate-200 bg-white px-4 py-3 text-slate-900 placeholder:text-slate-400 focus:border-primary focus:ring-primary sm:text-sm shadow-sm" id="username" name="username" placeholder="manager" type="text" value="{{ old('username') }}" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5" for="email">Manager Email *</label>
                <input class="block w-full rounded-lg border-slate-200 bg-white px-4 py-3 text-slate-900 placeholder:text-slate-400 focus:border-primary focus:ring-primary sm:text-sm shadow-sm" id="email" name="email" placeholder="manager@restaurant.com" type="email" value="{{ old('email') }}" required>
                <p class="mt-1 text-xs text-slate-500">This email will be used for manager login.</p>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5" for="password">Manager Password *</label>
                <div class="relative">
                    <input class="block w-full rounded-lg border-slate-200 bg-white px-4 py-3 pr-12 text-slate-900 placeholder:text-slate-400 focus:border-primary focus:ring-primary sm:text-sm shadow-sm" id="password" name="password" placeholder="Enter password" type="password" minlength="8" required>
                    <button type="button" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-primary transition-colors" aria-label="Toggle password visibility" data-password-toggle="password">
                        @resmenuPasswordToggle(20, 'text-xl')
                    </button>
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5" for="password_confirm">Confirm Manager Password *</label>
                <div class="relative">
                    <input class="block w-full rounded-lg border-slate-200 bg-white px-4 py-3 pr-12 text-slate-900 placeholder:text-slate-400 focus:border-primary focus:ring-primary sm:text-sm shadow-sm" id="password_confirm" name="password_confirm" placeholder="Confirm password" type="password" minlength="8" required>
                    <button type="button" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-primary transition-colors" aria-label="Toggle password visibility" data-password-toggle="password_confirm">
                        @resmenuPasswordToggle(20, 'text-xl')
                    </button>
                </div>
            </div>
        </div>

        <div class="space-y-5 hidden" data-step="3">
            <h3 class="text-lg font-bold text-slate-900">Verify your email</h3>
            <p class="text-sm text-slate-600">We will send a 6-digit code to your manager email address.</p>
            <button type="button" id="sendOtpBtn" class="w-full rounded-xl bg-slate-100 px-5 py-3 text-sm font-bold text-slate-900 hover:bg-slate-200 transition-colors">Send verification code</button>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5" for="otp">6-digit code *</label>
                <input class="block w-full rounded-lg border-slate-200 bg-white px-4 py-3 text-center tracking-[0.5em] font-extrabold text-slate-900 placeholder:text-slate-400 focus:border-primary focus:ring-primary sm:text-lg shadow-sm" id="otp" name="otp" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" placeholder="••••••" required>
            </div>
            @if(!empty($recaptchaSiteKey))
                <div class="pt-2 flex justify-center">
                    <div class="g-recaptcha" data-sitekey="{{ $recaptchaSiteKey }}"></div>
                </div>
                <p class="text-xs text-slate-500 text-center">Please complete the CAPTCHA before creating your account.</p>
            @endif
        </div>

        <div class="flex items-center gap-3 pt-2">
            <button id="prevBtn" class="hidden w-full sm:w-auto rounded-xl bg-slate-100 px-5 py-3 text-sm font-bold text-slate-900 hover:bg-slate-200 transition-colors" type="button">Back</button>
            <button id="nextBtn" class="w-full sm:w-auto rounded-xl bg-primary px-5 py-3 text-sm font-bold text-white hover:bg-primary/90 transition-colors" type="button">Next Step</button>
            <button id="submitBtn" class="hidden flex-1 rounded-xl bg-primary px-5 py-3 text-sm font-bold text-white shadow-lg shadow-primary/20 hover:bg-primary/90 transition-colors" type="submit">Create Account &amp; Start Trial</button>
        </div>
    </form>

    <div class="mt-8 text-center">
        <p class="text-sm text-slate-600">
            Already have an account?
            <a class="font-semibold text-primary hover:text-primary/80 transition-colors" href="{{ route('login') }}">Log In</a>
        </p>
    </div>
</div>
@endsection

@push('scripts')
<script>
(() => {
    const steps = Array.from(document.querySelectorAll('[data-step]'));
    const progressFill = document.getElementById('progressFill');
    const progressText = document.getElementById('progressText');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');
    let currentStep = 1;

    const labels = {
        1: 'Step 1 of 3 - Restaurant Details',
        2: 'Step 2 of 3 - Manager Account',
        3: 'Step 3 of 3 - Email Verification'
    };

    function updateStep() {
        steps.forEach((el, idx) => {
            el.classList.toggle('hidden', idx + 1 !== currentStep);
        });
        progressFill.style.width = (currentStep / steps.length * 100) + '%';
        progressText.textContent = labels[currentStep] || '';
        prevBtn.classList.toggle('hidden', currentStep === 1);
        nextBtn.classList.toggle('hidden', currentStep === steps.length);
        submitBtn.classList.toggle('hidden', currentStep !== steps.length);
    }

    function validateCurrentStep() {
        const current = document.querySelector('[data-step="' + currentStep + '"]');
        if (!current) return true;
        const fields = Array.from(current.querySelectorAll('input,textarea,select')).filter((field) => field.hasAttribute('required'));
        for (const field of fields) {
            if (!field.reportValidity()) return false;
        }
        if (currentStep === 2) {
            const pw = document.getElementById('password');
            const cpw = document.getElementById('password_confirm');
            if (pw && cpw && pw.value !== cpw.value) {
                cpw.setCustomValidity('Passwords do not match.');
                cpw.reportValidity();
                return false;
            }
            if (cpw) cpw.setCustomValidity('');
        }
        return true;
    }

    nextBtn.addEventListener('click', () => {
        if (!validateCurrentStep()) return;
        currentStep = Math.min(steps.length, currentStep + 1);
        updateStep();
    });

    prevBtn.addEventListener('click', () => {
        currentStep = Math.max(1, currentStep - 1);
        updateStep();
    });

    function captchaResponse() {
        return (typeof grecaptcha !== 'undefined' && grecaptcha.getResponse) ? grecaptcha.getResponse() : '';
    }

    document.getElementById('sendOtpBtn').addEventListener('click', async function () {
        const email = document.getElementById('email').value;
        if (!email) {
            alert('Please enter your manager email first.');
            return;
        }
        const btn = this;
        btn.disabled = true;
        try {
            const r = await fetch(@json(route('register.otp')), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': @json(csrf_token()),
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ email, 'g-recaptcha-response': captchaResponse() })
            });
            const data = await r.json().catch(() => ({}));
            alert(r.ok ? (data.message || 'Code sent') : (data.message || 'Could not send code'));
        } finally {
            btn.disabled = false;
        }
    });

    document.getElementById('registerForm').addEventListener('submit', function(e) {
        @if(!empty($recaptchaSiteKey))
        if (typeof grecaptcha !== 'undefined' && grecaptcha.getResponse && !grecaptcha.getResponse()) {
            e.preventDefault();
            alert('Please complete the CAPTCHA to continue.');
        }
        @endif
    });

    updateStep();
})();
</script>
@endpush
