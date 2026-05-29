@extends('layouts.admin')
@section('title', 'Subscription plans')
@section('content')
@include('partials.admin.page-header', ['title' => 'Subscription plans', 'subtitle' => 'Platform pricing tiers'])

<div class="card" style="margin-bottom:16px;">
  <a href="{{ route('admin.subscription-plans.create') }}" class="btn-filter" style="display:inline-block;text-decoration:none;">+ New plan</a>
</div>

<div class="card table-card">
<table class="data-table">
  <thead><tr><th>Name</th><th>Slug</th><th>Monthly</th><th>Annual</th><th>Limits</th><th>Active</th><th>Order</th><th></th></tr></thead>
  <tbody>
  @foreach($plans as $p)
  <tr>
    <td><strong>{{ $p->name }}</strong></td>
    <td>{{ $p->slug }}</td>
    <td>₦{{ number_format($p->monthly_price ?? 0, 0) }}</td>
    <td>₦{{ number_format($p->annual_price ?? 0, 0) }}</td>
    <td>{{ $p->max_categories }}/{{ $p->max_menu_items }} items</td>
    <td>{{ $p->is_active ? 'Yes' : 'No' }}</td>
    <td>{{ $p->display_order }}</td>
    <td>
      <a href="{{ route('admin.subscription-plans.edit', $p) }}">Edit</a>
      <form method="post" action="{{ route('admin.subscription-plans.toggle', $p) }}" style="display:inline">@csrf<button type="submit">Toggle</button></form>
      <form method="post" action="{{ route('admin.subscription-plans.destroy', $p) }}" style="display:inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button type="submit">Delete</button></form>
    </td>
  </tr>
  @endforeach
  </tbody>
</table>
</div>
@endsection
@push('head')
<link rel="stylesheet" href="{{ asset('assets/css/admin-payments.css') }}">
@endpush
