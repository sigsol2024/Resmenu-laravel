<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification link — {{ $siteName }}</title>
    <style>
        body{font-family:system-ui,sans-serif;background:#f8fafc;color:#0f172a;display:flex;min-height:100vh;align-items:center;justify-content:center;margin:0;padding:1rem}
        .card{background:#fff;border:1px solid #fecaca;border-radius:12px;padding:1.75rem;max-width:28rem;width:100%}
        h1{font-size:1.25rem;margin:0 0 .5rem;color:#991b1b}
        p{color:#64748b;font-size:.95rem;line-height:1.5}
        a{color:#f97415;font-weight:600}
    </style>
</head>
<body>
<div class="card">
    <h1>Link invalid or expired</h1>
    <p>{{ $message }}</p>
    <p><a href="{{ route('login') }}">Go to login</a></p>
</div>
</body>
</html>
