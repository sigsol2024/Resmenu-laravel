@extends('layouts.manager')

@section('title', 'QR analytics')

@section('content')
<h1 class="text-2xl font-bold mb-6">QR analytics</h1>
<div class="grid grid-cols-2 md:grid-cols-4 gap-4">
    <div class="bg-white p-4 rounded-lg shadow"><p class="text-sm text-gray-500">Scans (7d)</p><p class="text-2xl font-bold">{{ $analytics['scans_7d'] ?? 0 }}</p></div>
    <div class="bg-white p-4 rounded-lg shadow"><p class="text-sm text-gray-500">Scans (30d)</p><p class="text-2xl font-bold">{{ $analytics['scans_30d'] ?? 0 }}</p></div>
    <div class="bg-white p-4 rounded-lg shadow"><p class="text-sm text-gray-500">Unique (7d)</p><p class="text-2xl font-bold">{{ $analytics['unique_7d'] ?? 0 }}</p></div>
    <div class="bg-white p-4 rounded-lg shadow"><p class="text-sm text-gray-500">Total</p><p class="text-2xl font-bold">{{ $analytics['total'] ?? 0 }}</p></div>
</div>
@endsection
