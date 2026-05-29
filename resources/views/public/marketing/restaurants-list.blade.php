@extends('layouts.marketing')

@section('title', 'Restaurants')

@section('content')
<section class="section" style="max-width: 960px;">
    <h1 class="section-title">Restaurant menus</h1>
    <p>Browse restaurants on our platform or <a href="{{ route('register') }}">create your own menu</a>.</p>
    <p style="margin-top: 24px;"><a href="{{ route('login') }}">Manager login</a></p>
</section>
@endsection
