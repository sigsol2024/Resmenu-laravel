<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register — Resmenu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @if(!empty($recaptchaSiteKey))
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4">
<div class="bg-white rounded-xl shadow-lg p-8 w-full max-w-md">
    <h1 class="text-xl font-bold mb-6">Create your restaurant account</h1>
    @if($errors->any())
        <div class="text-red-600 text-sm mb-4">
            @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
        </div>
    @endif
    @if(session('success'))<p class="text-green-700 text-sm mb-4">{{ session('success') }}</p>@endif
    <form method="post" action="{{ route('register.submit') }}" id="register-form" class="space-y-4">
        @csrf
        <div><label class="block text-sm font-medium">Restaurant name</label><input name="restaurant_name" value="{{ old('restaurant_name') }}" required class="w-full border rounded px-3 py-2"></div>
        <div><label class="block text-sm font-medium">Username</label><input name="username" value="{{ old('username') }}" required class="w-full border rounded px-3 py-2"></div>
        <div><label class="block text-sm font-medium">Email</label><input type="email" name="email" id="email" value="{{ old('email') }}" required class="w-full border rounded px-3 py-2"></div>
        <button type="button" onclick="sendOtp()" class="text-sm text-orange-600 underline">Send verification code</button>
        <div><label class="block text-sm font-medium">Password</label><input type="password" name="password" required class="w-full border rounded px-3 py-2"></div>
        <div><label class="block text-sm font-medium">Verification code</label><input name="otp" required class="w-full border rounded px-3 py-2" placeholder="6-digit code"></div>
        @if(!empty($recaptchaSiteKey))
        <div class="g-recaptcha" data-sitekey="{{ $recaptchaSiteKey }}"></div>
        @endif
        <button type="submit" class="w-full bg-slate-900 text-white py-2 rounded font-semibold">Create account</button>
    </form>
    <p class="mt-4 text-sm text-center"><a href="{{ route('login') }}" class="underline">Sign in</a></p>
</div>
<script>
function captchaResponse() {
  return (typeof grecaptcha !== 'undefined') ? grecaptcha.getResponse() : '';
}
async function sendOtp() {
  const email = document.getElementById('email').value;
  const r = await fetch(@json(route('register.otp')), {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': @json(csrf_token()),
      'Accept': 'application/json',
    },
    body: JSON.stringify({email, 'g-recaptcha-response': captchaResponse()})
  });
  const data = await r.json().catch(() => ({}));
  alert(r.ok ? (data.message || 'Code sent') : (data.message || 'Could not send code'));
}
</script>
</body>
</html>
