<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription — {{ $siteName }}</title>
    <style>
        body{font-family:system-ui,sans-serif;background:#f8fafc;color:#0f172a;display:flex;min-height:100vh;align-items:center;justify-content:center;margin:0;padding:1rem}
        .card{background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:1.75rem;max-width:28rem;width:100%;box-shadow:0 10px 30px rgba(15,23,42,.06)}
        h1{font-size:1.25rem;margin:0 0 .5rem}
        p{color:#64748b;font-size:.95rem;line-height:1.5}
        a{color:#f97415}
        .ok{color:#15803d}
        .warn{color:#b45309}
        .err{color:#b91c1c}
    </style>
</head>
<body>
<div class="card">
    @if($status === 'success')
        <h1 class="ok">You're subscribed</h1>
    @elseif($status === 'already')
        <h1 class="warn">Already subscribed</h1>
    @else
        <h1 class="err">Subscription unsuccessful</h1>
    @endif
    <p>{{ $message }}</p>
    <p><a href="{{ route('login') }}">Back to login</a></p>
</div>
</body>
</html>
