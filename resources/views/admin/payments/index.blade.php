@extends('layouts.admin')
@section('title', 'Payments')
@section('content')
<div class="card"><h1>Subscription payments</h1>
<table class="mt-4"><thead><tr><th>Restaurant</th><th>Amount</th><th>Status</th><th>Date</th></tr></thead>
<tbody>@foreach($transactions as $tx)<tr><td>{{ $tx->restaurant_id }}</td><td>₦{{ number_format($tx->amount ?? 0, 0) }}</td><td>{{ $tx->status }}</td><td>{{ $tx->created_at }}</td></tr>@endforeach</tbody></table></div>
@endsection
