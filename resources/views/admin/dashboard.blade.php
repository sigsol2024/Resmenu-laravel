@extends('layouts.admin')
@section('title', 'Dashboard')
@section('content')
<h1 style="margin:0 0 20px;">Dashboard</h1>
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:16px;">
    <div class="card"><strong>{{ $restaurantCount }}</strong><br><span style="color:#64748b;font-size:0.875rem;">Restaurants</span></div>
    <div class="card"><strong>{{ $subscriptionCount }}</strong><br><span style="color:#64748b;font-size:0.875rem;">Subscriptions</span></div>
</div>
<p style="margin-top:24px;color:#64748b;font-size:0.875rem;">Full admin CRUD pages are ported in phase 5. List views below are read-only stubs.</p>
@endsection
