<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot password — Resmenu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @if(!empty($recaptchaSiteKey))
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4">
<div class="bg-white rounded-xl shadow-lg p-8 w-full max-w-md">
    <h1 class="text-xl font-bold mb-6">Reset your password</h1>
    @if($errors->any())
        <div class="text-red-600 text-sm mb-4">
            @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
        </div>
    @endif
    @if(session('success'))<p class="text-green-700 text-sm mb-4">{{ session('success') }}</p>@endif
    <form method="post" action="{{ route('password.email') }}" class="space-y-4">
        @csrf
        <div><label class="block text-sm font-medium">Email</label><input type="email" name="email" required class="w-full border rounded px-3 py-2"></div>
        @if(!empty($recaptchaSiteKey))
        <div class="g-recaptcha" data-sitekey="{{ $recaptchaSiteKey }}"></div>
        @endif
        <button type="submit" class="w-full bg-slate-900 text-white py-2 rounded font-semibold">Send reset link</button>
    </form>
    <p class="mt-4 text-sm text-center"><a href="{{ route('login') }}" class="underline">Back to sign in</a></p>
</div>
</body>
</html>
