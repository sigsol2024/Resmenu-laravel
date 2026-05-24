<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reserve a table — {{ $restaurant->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen p-4">
<div class="max-w-lg mx-auto bg-white rounded-xl shadow p-6">
    <h1 class="text-xl font-bold mb-6">Table reservation</h1>
    @if(session('success'))<p class="text-green-700 mb-4">{{ session('success') }}</p>@endif
    @if($errors->any())<div class="text-red-600 text-sm mb-4">@foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach</div>@endif
    <form method="post" action="{{ route('public.reservation', $restaurant->slug) }}" class="space-y-3">
        @csrf
        <div><label class="text-sm font-medium">Name</label><input name="guest_name" value="{{ old('guest_name') }}" required class="w-full border rounded px-3 py-2"></div>
        <div><label class="text-sm font-medium">Email</label><input type="email" name="guest_email" value="{{ old('guest_email') }}" required class="w-full border rounded px-3 py-2"></div>
        <div><label class="text-sm font-medium">Phone</label><input name="guest_phone" value="{{ old('guest_phone') }}" required class="w-full border rounded px-3 py-2"></div>
        <div><label class="text-sm font-medium">Date</label><input type="date" name="reservation_date" value="{{ old('reservation_date') }}" required class="w-full border rounded px-3 py-2"></div>
        <div><label class="text-sm font-medium">Time</label><input type="time" name="reservation_time" value="{{ old('reservation_time') }}" required class="w-full border rounded px-3 py-2"></div>
        <div><label class="text-sm font-medium">Party size</label><input type="number" name="party_size" min="1" value="{{ old('party_size', 2) }}" required class="w-full border rounded px-3 py-2"></div>
        <div><label class="text-sm font-medium">Notes</label><textarea name="notes" class="w-full border rounded px-3 py-2">{{ old('notes') }}</textarea></div>
        <button type="submit" class="w-full py-2 bg-slate-900 text-white rounded font-semibold">Request reservation</button>
    </form>
</div>
</body>
</html>
