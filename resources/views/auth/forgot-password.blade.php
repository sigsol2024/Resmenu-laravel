@extends('layouts.auth-marketing')

@section('title', 'Forgot Password')
@section('auth_layout', 'centered')

@section('content')
<div class="w-full max-w-md rounded-2xl bg-white shadow-xl border border-slate-200 p-6 sm:p-8">
    <div class="mb-6 flex items-center justify-between gap-4">
        <a href="{{ $marketingHomeUrl ?? 'https://resmenu.net/' }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-primary transition-colors">
            @resmenuIcon('arrow_back', ['size' => 16, 'class' => 'text-base'])
            Back to Home
        </a>
        <a href="{{ $marketingHomeUrl ?? 'https://resmenu.net/' }}" class="inline-flex items-center gap-2">
            @if(!empty($siteLogoUrl))
                <img src="{{ $siteLogoUrl }}" alt="{{ $siteName }}" class="h-9 w-auto">
            @else
                @resmenuIcon('restaurant_menu', ['size' => 28, 'class' => 'text-primary text-3xl'])
            @endif
        </a>
    </div>

    <h1 class="text-2xl font-bold text-slate-900 mb-2">Forgot Password</h1>
    <p class="text-sm text-slate-600 mb-6">Enter your email and we will send a password reset link.</p>

    @if($errors->any())
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
        </div>
    @endif

    @if(session('success'))
        <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <form method="post" action="{{ route('password.email') }}" class="space-y-4">
        @csrf
        <div>
            <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">Email or Username</label>
            <input
                id="email"
                name="email"
                type="email"
                class="block w-full rounded-lg border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 focus:border-primary focus:ring-primary"
                placeholder="your email address"
                value="{{ old('email') }}"
                required
            />
        </div>
        @if(!empty($recaptchaSiteKey))
            <div class="g-recaptcha" data-sitekey="{{ $recaptchaSiteKey }}"></div>
        @endif
        <button type="submit" class="w-full rounded-lg bg-primary px-4 py-3 font-semibold text-white hover:bg-primary/90 transition-colors">
            Send Reset Link
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-slate-600">
        Remember your password?
        <a href="{{ route('login') }}" class="font-semibold text-primary hover:underline">Back to Login</a>
    </p>
</div>
@endsection

@push('head')
    @if(!empty($recaptchaSiteKey))
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
@endpush
