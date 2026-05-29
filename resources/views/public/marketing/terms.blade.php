<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Terms — {{ $settings['site_name'] ?? 'Resmenu' }}</title>
  <link rel="stylesheet" href="{{ asset('assets/css/marketing.css') }}">
</head>
<body>
  <main style="max-width:720px;margin:40px auto;padding:24px">
    <h1>Terms of service</h1>
    <p>By using {{ $settings['site_name'] ?? 'Resmenu' }}, you agree to use the platform responsibly and in compliance with applicable laws. Restaurant owners are responsible for menu accuracy, pricing, and fulfillment of orders and reservations.</p>
    <p>Subscriptions renew according to your selected billing cycle unless cancelled. Trial periods convert to paid plans when you subscribe.</p>
    <p>We may update these terms from time to time. Continued use of the service constitutes acceptance of updated terms.</p>
    <p>Questions? <a href="{{ route('public.contact') }}">Contact us</a>.</p>
    <p><a href="{{ route('login') }}">← Back to login</a></p>
  </main>
</body>
</html>
