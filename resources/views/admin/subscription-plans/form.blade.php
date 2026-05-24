@extends('layouts.admin')
@section('title', $plan->exists ? 'Edit plan' : 'New plan')
@section('content')
<div class="card">
    <h1>{{ $plan->exists ? 'Edit plan' : 'New plan' }}</h1>
    <form method="post" action="{{ $plan->exists ? route('admin.subscription-plans.update', $plan) : route('admin.subscription-plans.store') }}" class="mt-4 space-y-3">
        @csrf
        @if($plan->exists) @method('PUT') @endif
        <div><label>Name</label><input name="name" value="{{ old('name', $plan->name) }}" required class="w-full border p-2"></div>
        <div><label>Monthly price (₦)</label><input type="number" step="0.01" name="monthly_price" value="{{ old('monthly_price', $plan->monthly_price) }}" required class="w-full border p-2"></div>
        <div><label>Yearly discount %</label><input type="number" name="yearly_discount_percent" value="{{ old('yearly_discount_percent', $plan->yearly_discount_percent ?? 20) }}" class="w-full border p-2"></div>
        <label><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $plan->is_active))> Active</label>
        <label><input type="checkbox" name="feature_food_ordering" value="1" @checked(data_get($plan->features, 'food_ordering'))> Food ordering</label>
        <label><input type="checkbox" name="feature_table_reservations" value="1" @checked(data_get($plan->features, 'table_reservations'))> Table reservations</label>
        <button type="submit" class="px-4 py-2 bg-slate-900 text-white rounded">Save</button>
    </form>
</div>
@endsection
