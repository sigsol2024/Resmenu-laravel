@extends('layouts.auth-marketing')

@section('title', 'Reset Password')
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

    <h1 class="text-2xl font-bold text-slate-900 mb-2">Reset Password</h1>
    <p class="text-sm text-slate-600 mb-6">Create a new password for your account.</p>

    @if($errors->any())
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
        </div>
    @endif

    @if(session('success'))
        <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            {{ session('success') }}
        </div>
        <a href="{{ route('login') }}" class="inline-flex w-full items-center justify-center rounded-lg bg-primary px-4 py-3 font-semibold text-white hover:bg-primary/90 transition-colors">Go to Login</a>
    @elseif(empty($token))
        <div class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-700">
            Missing reset token. Please request a new password reset link.
        </div>
        <a href="{{ route('password.request') }}" class="mt-4 inline-flex w-full items-center justify-center rounded-lg bg-primary px-4 py-3 font-semibold text-white hover:bg-primary/90 transition-colors">Request New Link</a>
    @else
        <form method="post" action="{{ route('password.update') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div>
                <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">New Password</label>
                <div class="relative">
                    <input
                        id="password"
                        name="password"
                        type="password"
                        minlength="{{ config('resmenu.password_min_length', 8) }}"
                        class="block w-full rounded-lg border-slate-200 bg-slate-50 pl-4 pr-12 py-3 text-slate-900 focus:border-primary focus:ring-primary"
                        placeholder="Enter new password"
                        required
                    />
                    <button type="button" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-primary transition-colors" aria-label="Toggle password visibility" data-password-toggle="password">
                        @resmenuPasswordToggle(20, 'text-xl')
                    </button>
                </div>
            </div>
            <div>
                <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-2">Confirm New Password</label>
                <div class="relative">
                    <input
                        id="password_confirmation"
                        name="password_confirmation"
                        type="password"
                        minlength="{{ config('resmenu.password_min_length', 8) }}"
                        class="block w-full rounded-lg border-slate-200 bg-slate-50 pl-4 pr-12 py-3 text-slate-900 focus:border-primary focus:ring-primary"
                        placeholder="Confirm new password"
                        required
                    />
                    <button type="button" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-primary transition-colors" aria-label="Toggle password visibility" data-password-toggle="password_confirmation">
                        @resmenuPasswordToggle(20, 'text-xl')
                    </button>
                </div>
            </div>
            <button type="submit" class="w-full rounded-lg bg-primary px-4 py-3 font-semibold text-white hover:bg-primary/90 transition-colors">
                Update Password
            </button>
        </form>
    @endif
</div>
@endsection
