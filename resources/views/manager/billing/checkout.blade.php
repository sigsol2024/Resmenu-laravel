@extends('layouts.manager')

@section('title', 'Subscribe')

@section('content')
<h1 class="text-2xl font-bold mb-6">Choose a plan</h1>
<form method="post" action="{{ route('manager.billing.process-payment') }}" class="space-y-4">
    @csrf
    @foreach($plans as $plan)
        <label class="flex items-center gap-3 bg-white p-4 rounded-lg shadow cursor-pointer">
            <input type="radio" name="plan_id" value="{{ $plan['id'] }}" required>
            <span class="font-semibold">{{ $plan['name'] }}</span>
            <span class="text-gray-500">₦{{ number_format($plan['monthly_price'] ?? $plan['price_monthly'] ?? 0, 0) }}/mo</span>
        </label>
    @endforeach
    <div class="bg-white p-4 rounded-lg shadow space-y-2">
        <p class="font-medium">Billing cycle</p>
        <label><input type="radio" name="billing_cycle" value="monthly" checked> Monthly</label>
        <label><input type="radio" name="billing_cycle" value="annual"> Annual</label>
    </div>
    <div class="bg-white p-4 rounded-lg shadow space-y-2">
        <p class="font-medium">Payment gateway</p>
        <label><input type="radio" name="gateway" value="paystack" checked> Paystack</label>
        <label><input type="radio" name="gateway" value="flutterwave"> Flutterwave</label>
    </div>
    <button type="submit" class="px-4 py-2 bg-orange-600 text-white rounded font-semibold">Continue to payment</button>
</form>
@endsection
