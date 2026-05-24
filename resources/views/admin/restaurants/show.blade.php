@extends('layouts.admin')
@section('title', $restaurant->name)
@section('content')
<h1 style="margin:0 0 8px;">{{ $restaurant->name }}</h1>
<p style="color:#64748b;margin:0 0 20px;">/{{ $restaurant->slug }} · Template {{ $restaurant->template_id }} · {{ $restaurant->is_active ? 'Active' : 'Inactive' }}</p>
<div class="card">
    <p><strong>Email:</strong> {{ $restaurant->email }}</p>
    <p><strong>Phone:</strong> {{ $restaurant->phone ?? '—' }}</p>
    <p><strong>Sections:</strong> {{ $restaurant->sections->count() }}</p>
    <p><strong>Categories:</strong> {{ $restaurant->categories->count() }}</p>
    <p><a href="{{ route('public.menu', $restaurant->slug) }}" target="_blank">View public menu</a></p>
</div>
<p style="margin-top:16px;"><a href="{{ route('admin.restaurants.index') }}">← Back to restaurants</a></p>
@endsection
