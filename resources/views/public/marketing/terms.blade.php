@extends('layouts.marketing')

@section('title', 'Terms')

@section('content')
<section class="section" style="max-width: 720px;">
    <h1 class="section-title">Terms of service</h1>
    <p>By using {{ $settings['site_name'] ?? 'Resmenu' }}, you agree to use the platform responsibly and in compliance with applicable laws. Restaurant owners are responsible for menu accuracy, pricing, and fulfillment of orders and reservations.</p>
    <p>Subscriptions renew according to your selected billing cycle unless cancelled. Trial periods convert to paid plans when you subscribe.</p>
    <p>We may update these terms from time to time. Continued use of the service constitutes acceptance of updated terms.</p>
    <p>Questions? <a href="{{ route('public.contact') }}">Contact us</a>.</p>
    <p style="margin-top: 32px;"><a href="{{ route('login') }}">← Back to login</a></p>
</section>
@endsection
