<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Set new password — Resmenu</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4">
<div class="bg-white rounded-xl shadow-lg p-8 w-full max-w-md">
    <h1 class="text-xl font-bold mb-6">Choose a new password</h1>
    @if($errors->any())<div class="text-red-600 text-sm mb-4">@foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach</div>@endif
    <form method="post" action="{{ route('password.update') }}" class="space-y-4">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div><label class="block text-sm font-medium">New password</label><input type="password" name="password" required class="w-full border rounded px-3 py-2"></div>
        <div><label class="block text-sm font-medium">Confirm password</label><input type="password" name="password_confirmation" required class="w-full border rounded px-3 py-2"></div>
        <button type="submit" class="w-full bg-slate-900 text-white py-2 rounded font-semibold">Update password</button>
    </form>
</div>
</body>
</html>
