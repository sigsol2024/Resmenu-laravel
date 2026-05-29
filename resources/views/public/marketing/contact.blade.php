<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Contact — {{ $settings['site_name'] ?? 'Resmenu' }}</title>
  <link rel="stylesheet" href="{{ asset('assets/css/marketing.css') }}">
</head>
<body>
  <main style="max-width:720px;margin:40px auto;padding:24px">
    <h1>Contact us</h1>
    @if(!empty($settings['contact_support_email']))<p>Support: <a href="mailto:{{ $settings['contact_support_email'] }}">{{ $settings['contact_support_email'] }}</a></p>@endif
    @if(!empty($settings['contact_support_phone']))<p>Phone: {{ $settings['contact_support_phone'] }}</p>@endif
    @if(!empty($settings['contact_hq_address']))<p>{{ $settings['contact_hq_address'] }}</p>@endif
    <p><a href="{{ route('login') }}">← Back to login</a></p>
  </main>
</body>
</html>
