@php
    $reason = $access['lockout_reason'] ?? 'subscription_required';
    $message = $access['message'] ?? 'Access is restricted.';
    $titles = [
        'trial_expired' => 'Trial Ended',
        'subscription_expired' => 'Subscription Expired',
        'no_subscription' => 'Subscription Required',
    ];
    $title = $titles[$reason] ?? 'Access Restricted';
    $logoUrl = $restaurant->logo ? $uploads->publicUrl('logos', $restaurant->logo) : null;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} — {{ $restaurant->name }}</title>
    <style>
        body { font-family: system-ui, sans-serif; background: #111827; color: #f9fafb; min-height: 100vh; display: flex; align-items: center; justify-content: center; margin: 0; padding: 24px; }
        .card { background: #1f2937; border-radius: 12px; padding: 32px; max-width: 420px; text-align: center; }
        h1 { margin: 0 0 12px; font-size: 1.5rem; }
        p { color: #d1d5db; line-height: 1.5; }
        a { display: inline-block; margin-top: 20px; padding: 10px 20px; background: #f20d0d; color: #fff; text-decoration: none; border-radius: 8px; font-weight: 600; }
        img { max-height: 48px; margin-bottom: 16px; }
    </style>
</head>
<body>
<div class="card">
    @if($logoUrl)<img src="{{ $logoUrl }}" alt="{{ $restaurant->name }}">@endif
    <h1>{{ $title }}</h1>
    <p>{{ $message }}</p>
    <a href="{{ url('/?next='.urlencode('/manager/billing.php')) }}">Sign in to renew</a>
</div>
</body>
</html>
