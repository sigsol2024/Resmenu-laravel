<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Payment failed</title>
  <link rel="stylesheet" href="{{ asset('assets/css/marketing.css') }}">
</head>
<body>
  <main style="max-width:560px;margin:40px auto;padding:24px;text-align:center">
    <h1>Payment failed</h1>
    <p>{{ $message }}</p>
    <p><a href="{{ route('login') }}">Return home</a></p>
  </main>
</body>
</html>
