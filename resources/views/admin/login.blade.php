<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin login — Resmenu</title>
    <style>
        body { font-family: system-ui, sans-serif; background: #0f172a; min-height: 100vh; display: flex; align-items: center; justify-content: center; margin: 0; }
        .card { background: #fff; padding: 32px; border-radius: 12px; width: 100%; max-width: 380px; }
        label { display: block; margin-bottom: 6px; font-size: 0.875rem; }
        input { width: 100%; padding: 10px; margin-bottom: 14px; border: 1px solid #d1d5db; border-radius: 6px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background: #0f172a; color: #fff; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; }
        .error { color: #dc2626; font-size: 0.875rem; margin-bottom: 12px; }
    </style>
</head>
<body>
<div class="card">
    <h1 style="margin:0 0 20px;font-size:1.25rem;">Super admin</h1>
    @if($errors->any())<div class="error">{{ $errors->first() }}</div>@endif
    <form method="post" action="{{ route('admin.login.submit') }}">
        @csrf
        <label for="username">Username or email</label>
        <input type="text" id="username" name="username" value="{{ old('username') }}" required autofocus>
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">Sign in</button>
    </form>
</div>
</body>
</html>
