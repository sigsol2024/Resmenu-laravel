@extends('layouts.admin')
@section('title', 'Payment settings')
@section('content')
@include('partials.admin.page-header', ['title' => 'Payment settings', 'subtitle' => 'Platform Paystack and Flutterwave'])

@foreach(['paystack' => $paystack, 'flutterwave' => $flutterwave] as $name => $settings)
<div class="card" style="margin-bottom:24px;max-width:640px;">
  <h2>{{ ucfirst($name) }}</h2>
  <form method="post" action="{{ route('admin.payment-settings.update') }}">
    @csrf
    <input type="hidden" name="gateway" value="{{ $name }}">
    <div style="margin-bottom:12px">
      <label><input type="checkbox" name="is_active" value="1" @checked($settings->is_active ?? false)> Active</label>
      <label style="margin-left:16px"><input type="checkbox" name="test_mode" value="1" @checked($settings->test_mode ?? true)> Test mode</label>
    </div>
    <div class="filter-group"><label>Public key (live)</label><input name="public_key_live" value="{{ $settings->public_key_live ?? '' }}" style="width:100%"></div>
    <div class="filter-group" style="margin-top:12px"><label>Secret key (live)</label><input name="secret_key_live" type="password" placeholder="Leave blank to keep" style="width:100%"></div>
    <div class="filter-group" style="margin-top:12px"><label>Public key (test)</label><input name="public_key_test" value="{{ $settings->public_key_test ?? '' }}" style="width:100%"></div>
    <div class="filter-group" style="margin-top:12px"><label>Secret key (test)</label><input name="secret_key_test" type="password" placeholder="Leave blank to keep" style="width:100%"></div>
    <button type="submit" class="btn-filter" style="margin-top:16px">Save {{ ucfirst($name) }}</button>
  </form>
</div>
@endforeach
@endsection
@push('head')<link rel="stylesheet" href="{{ asset('assets/css/admin-payments.css') }}">@endpush
