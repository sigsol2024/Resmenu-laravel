<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>FAQ — {{ $settings['site_name'] ?? 'Resmenu' }}</title>
  <link rel="stylesheet" href="{{ asset('assets/css/marketing.css') }}">
</head>
<body>
  <main style="max-width:720px;margin:40px auto;padding:24px">
    <h1>Frequently asked questions</h1>
    <section style="margin:24px 0">
      <h2>What is Resmenu?</h2>
      <p>Resmenu is a digital menu platform for restaurants. Create a beautiful online menu, share it via QR code, and optionally accept orders and table reservations.</p>
    </section>
    <section style="margin:24px 0">
      <h2>How do I get started?</h2>
      <p><a href="{{ route('register') }}">Register</a> for a free trial, add your menu items, pick a template, and share your menu link or QR code with guests.</p>
    </section>
    <section style="margin:24px 0">
      <h2>Which plans include ordering and reservations?</h2>
      <p>Food ordering is available on Professional and Enterprise plans. Table reservations are included on Enterprise.</p>
    </section>
    <section style="margin:24px 0">
      <h2>Need help?</h2>
      <p>Visit our <a href="{{ route('public.contact') }}">contact page</a> or email {{ $settings['contact_support_email'] ?? 'support@resmenu.net' }}.</p>
    </section>
    <p><a href="{{ route('login') }}">← Back to login</a></p>
  </main>
</body>
</html>
