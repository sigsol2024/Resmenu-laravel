@extends('layouts.marketing')

@section('title', 'Contact')

@section('content')
<section class="section" style="max-width: 720px;">
    <h1 class="section-title">Contact us</h1>
    @if(!empty($settings['contact_support_email']))
        <p>Support: <a href="mailto:{{ $settings['contact_support_email'] }}">{{ $settings['contact_support_email'] }}</a></p>
    @endif
    @if(!empty($settings['contact_support_phone']))
        <p>Phone: {{ $settings['contact_support_phone'] }}</p>
    @endif
    @if(!empty($settings['contact_hq_address']))
        <p>{{ $settings['contact_hq_address'] }}</p>
    @endif
    <p style="margin-top: 32px;"><a href="{{ route('login') }}">← Back to login</a></p>
</section>
@endsection
