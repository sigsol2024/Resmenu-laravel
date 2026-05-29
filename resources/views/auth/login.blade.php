@extends('layouts.auth-marketing')

@section('title', 'Login')

@section('content')
<div class="mx-auto w-full max-w-md max-xl:rounded-2xl max-xl:border max-xl:border-slate-200/90 max-xl:bg-white/80 max-xl:backdrop-blur-md max-xl:shadow-lg max-xl:px-4 max-xl:py-5 sm:max-xl:px-6 sm:max-xl:py-6 max-xl:ring-1 max-xl:ring-slate-900/5">
    <div class="mb-5 flex w-full items-center justify-between gap-3 min-h-[2.5rem]">
        <a href="{{ $marketingHomeUrl }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-primary transition-colors sm:text-sm shrink-0 self-center">
            @resmenuIcon('arrow_back', ['size' => 20, 'class' => 'text-base sm:text-lg leading-none'])
            <span class="leading-tight">Back to Home</span>
        </a>
        <a href="{{ $marketingHomeUrl }}" class="inline-flex items-center justify-end gap-2 hover:opacity-90 transition-opacity lg:hidden shrink-0 self-center h-10">
            @if($siteLogoUrl)
                <img src="{{ $siteLogoUrl }}" alt="{{ $siteName }}" class="h-9 w-auto max-h-10 object-contain sm:h-10">
            @else
                @resmenuIcon('restaurant_menu', ['size' => 28, 'class' => 'text-primary text-2xl sm:text-3xl leading-none'])
                <span class="text-sm font-bold font-poppins text-slate-900 sm:text-base leading-tight">{{ $siteName }}</span>
            @endif
        </a>
    </div>

    <div class="mb-5 max-xl:mb-4">
        <h2 class="text-2xl font-extrabold tracking-tight text-slate-900 font-poppins sm:text-3xl">Welcome Back</h2>
        <p class="mt-1.5 text-sm text-slate-500 sm:text-base">Log in to manage your digital menu.</p>
    </div>

    <form class="space-y-4 sm:space-y-5" method="post" action="{{ route('login.submit') }}">
        @csrf
        @if(!empty($plan))<input type="hidden" name="plan" value="{{ $plan }}">@endif
        @if(!empty($cycle))<input type="hidden" name="cycle" value="{{ $cycle }}">@endif
        @if(!empty($next))<input type="hidden" name="next" value="{{ $next }}">@endif

        <div>
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2" for="username">Email or Username</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    @resmenuIcon('person', ['size' => 20, 'class' => 'text-slate-400 text-xl'])
                </div>
                <input class="block w-full pl-10 sm:pl-11 pr-3 sm:pr-4 py-3 sm:py-3.5 text-base bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:ring-primary focus:border-primary transition-colors" id="username" name="username" placeholder="your email or username" type="text" value="{{ old('username') }}" required autofocus>
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2" for="password">Password</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    @resmenuIcon('lock', ['size' => 20, 'class' => 'text-slate-400 text-xl'])
                </div>
                <input class="block w-full pl-10 sm:pl-11 pr-11 sm:pr-12 py-3 sm:py-3.5 text-base bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:ring-primary focus:border-primary transition-colors" id="password" name="password" placeholder="********" type="password" required>
                <button id="togglePassword" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-primary transition-colors" type="button" aria-label="Toggle password visibility" data-password-toggle="password">
                    @resmenuPasswordToggle(20, 'text-xl')
                </button>
            </div>
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center cursor-pointer group">
                <input class="w-4 h-4 rounded border-slate-300 text-primary focus:ring-primary dark:bg-slate-800 dark:border-slate-700" type="checkbox" name="remember_me">
                <span class="ml-2 text-sm text-slate-600 dark:text-slate-400 group-hover:text-slate-900 dark:group-hover:text-slate-200 transition-colors">Remember me</span>
            </label>
            <a class="text-sm font-semibold text-primary hover:text-primary/80 transition-colors" href="{{ route('password.request') }}">Forgot Password?</a>
        </div>

        @if($errors->any())
            <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ $errors->first() }}
            </div>
        @endif
        @if(request()->query('timeout') === '1')
            <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                Your session expired due to inactivity. Please sign in again.
            </div>
        @endif

        <button class="w-full bg-primary hover:bg-primary/90 text-white font-bold py-3.5 text-base rounded-lg shadow-lg shadow-primary/20 transition-all sm:py-4 sm:text-lg max-xl:active:scale-[0.99]" type="submit">
            Login
        </button>
    </form>

    <p class="mt-6 text-center text-xs text-slate-600 sm:mt-8 sm:text-sm">
        Don't have an account?
        <a class="font-bold text-primary hover:underline" href="{{ $registerUrl }}">Sign Up</a>
    </p>
</div>
@endsection
