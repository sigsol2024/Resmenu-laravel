@extends('layouts.manager')

@section('title', 'QR code')

@section('content')
<div class="max-w-xl">
    <h1 class="text-2xl font-bold mb-4">QR code</h1>
    <p class="text-sm text-gray-600 mb-4">Customers scan this link to open your menu.</p>
    <div class="bg-white p-6 rounded-lg shadow">
        <p class="font-mono text-sm break-all mb-4">{{ $qrUrl }}</p>
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=240x240&data={{ urlencode($qrUrl) }}" alt="QR code" width="240" height="240">
        <p class="mt-4"><a href="{{ $menuUrl }}" target="_blank" class="text-orange-600 underline">Open menu</a></p>
    </div>
</div>
@endsection
