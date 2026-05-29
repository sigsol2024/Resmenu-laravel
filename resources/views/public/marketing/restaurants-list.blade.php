@extends('layouts.marketing')

@section('title', 'Restaurants')

@section('content')
<section style="padding: 60px 24px; max-width: 960px; margin: 0 auto;">
    <h1 style="margin-bottom: 24px;">Restaurant menus</h1>
    <p>Browse restaurants on our platform or <a href="{{ route('register') }}">create your own menu</a>.</p>
    <p style="margin-top: 24px;"><a href="{{ route('login') }}">Manager login</a></p>
</section>
@endsection
