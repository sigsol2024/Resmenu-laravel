@extends('layouts.admin')
@section('title', 'Subscription plans')
@section('content')
<div class="card"><h1>Subscription plans</h1>
<p><a href="{{ route('admin.subscription-plans.create') }}">+ New plan</a></p>
@if(session('success'))<p style="color:green;">{{ session('success') }}</p>@endif
@if(session('error'))<p style="color:red;">{{ session('error') }}</p>@endif
<table class="mt-4"><thead><tr><th>Name</th><th>Monthly</th><th>Active</th><th></th></tr></thead>
<tbody>
@foreach($plans as $p)
<tr>
    <td>{{ $p->name }}</td>
    <td>₦{{ number_format($p->monthly_price ?? 0, 0) }}</td>
    <td>{{ $p->is_active ? 'Yes' : 'No' }}</td>
    <td>
        <a href="{{ route('admin.subscription-plans.edit', $p) }}">Edit</a>
        <form method="post" action="{{ route('admin.subscription-plans.toggle', $p) }}" style="display:inline">@csrf<button type="submit">Toggle</button></form>
        <form method="post" action="{{ route('admin.subscription-plans.destroy', $p) }}" style="display:inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button type="submit">Delete</button></form>
    </td>
</tr>
@endforeach
</tbody></table></div>
@endsection
