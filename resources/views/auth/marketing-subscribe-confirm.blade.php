<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscribe — {{ $siteName }}</title>
    <style>
        body{font-family:system-ui,sans-serif;background:#f8fafc;color:#0f172a;display:flex;min-height:100vh;align-items:center;justify-content:center;margin:0;padding:1rem}
        .card{background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:1.75rem;max-width:28rem;width:100%;box-shadow:0 10px 30px rgba(15,23,42,.06)}
        h1{font-size:1.25rem;margin:0 0 .5rem}
        p{color:#64748b;font-size:.95rem;line-height:1.5}
        button{margin-top:1rem;width:100%;background:#f97415;color:#fff;border:0;border-radius:10px;padding:.85rem;font-weight:700;cursor:pointer}
        a{color:#f97415}
    </style>
</head>
<body>
<div class="card">
    <h1>Subscribe to Resmenu updates</h1>
    @if($alreadySubscribed)
        <p>You're already subscribed to Resmenu updates. You can <a href="{{ route('login') }}">log in</a> anytime.</p>
    @else
        <p>Confirm that you want to receive product updates, news, offers and other marketing emails from Resmenu.</p>
        <form method="post" action="{{ route('manager.marketing.subscribe.confirm') }}">
            @csrf
            <input type="hidden" name="id" value="{{ $id }}">
            <input type="hidden" name="hash" value="{{ $hash }}">
            <button type="submit">Yes, subscribe me</button>
        </form>
    @endif
</div>
</body>
</html>
