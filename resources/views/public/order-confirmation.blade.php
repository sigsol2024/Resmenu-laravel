<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order confirmed</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen p-4">
<div class="max-w-lg mx-auto bg-white rounded-xl shadow p-6 text-center">
    <h1 class="text-2xl font-bold mb-2">Thank you!</h1>
    <p class="text-gray-600 mb-4">Order {{ $order->displayNumber() }} at {{ $restaurant->name }}</p>
    <p class="text-lg font-semibold mb-6">Total: ₦{{ number_format((float) $order->total, 0) }}</p>
    <ul class="text-left text-sm space-y-2 mb-6">
        @foreach($items as $item)
            <li class="flex justify-between"><span>{{ $item->name }} × {{ $item->quantity }}</span><span>₦{{ number_format($item->price * $item->quantity, 0) }}</span></li>
        @endforeach
    </ul>
    <a href="{{ route('public.menu', $restaurant->slug) }}" class="inline-block px-4 py-2 bg-slate-900 text-white rounded">Back to menu</a>
</div>
</body>
</html>
