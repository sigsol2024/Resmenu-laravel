@extends('layouts.admin')
@section('title', 'Payment settings')
@section('content')
<h1>Platform payment gateways</h1>
@if(session('success'))<p style="color:green;">{{ session('success') }}</p>@endif

@foreach(['paystack' => $paystack, 'flutterwave' => $flutterwave] as $name => $settings)
<div class="card" style="margin-top:16px;">
    <h2>{{ ucfirst($name) }}</h2>
    <form method="post" action="{{ route('admin.payment-settings.update') }}" class="space-y-2">
        @csrf
        <input type="hidden" name="gateway" value="{{ $name }}">
        <label><input type="checkbox" name="is_active" value="1" @checked($settings->is_active ?? false)> Active</label>
        <label><input type="checkbox" name="test_mode" value="1" @checked($settings->test_mode ?? true)> Test mode</label>
        <div><label>Public key (live)</label><input name="public_key_live" value="{{ $settings->public_key_live ?? '' }}" class="w-full border p-2"></div>
        <div><label>Secret key (live)</label><input name="secret_key_live" placeholder="Leave blank to keep" class="w-full border p-2"></div>
        <div><label>Public key (test)</label><input name="public_key_test" value="{{ $settings->public_key_test ?? '' }}" class="w-full border p-2"></div>
        <div><label>Secret key (test)</label><input name="secret_key_test" placeholder="Leave blank to keep" class="w-full border p-2"></div>
        <button type="submit" class="px-4 py-2 bg-slate-900 text-white rounded">Save {{ $name }}</button>
    </form>
</div>
@endforeach
@endsection
