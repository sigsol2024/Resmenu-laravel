@extends('layouts.admin')
@section('title', 'Subscriptions')
@section('content')
<h1 style="margin:0 0 20px;">Subscriptions</h1>
@if(session('success'))<p style="color:green;">{{ session('success') }}</p>@endif
<div class="card">
<table>
    <thead><tr><th>Restaurant</th><th>Plan</th><th>Status</th><th>Cycle</th><th>Period end</th><th>Actions</th></tr></thead>
    <tbody>
    @foreach($subscriptions as $s)
        <tr>
            <td>{{ $s->restaurant->name ?? $s->restaurant_id }}</td>
            <td>{{ $s->plan->name ?? $s->plan_id }}</td>
            <td>{{ $s->status }}</td>
            <td>{{ $s->billing_cycle }}</td>
            <td>{{ $s->current_period_end ?? $s->trial_ends_at }}</td>
            <td>
                <form method="post" action="{{ route('admin.subscriptions.update', $s) }}" class="flex gap-2 flex-wrap">
                    @csrf @method('PATCH')
                    <select name="status">
                        @foreach(['trial','active','expired','cancelled','pending'] as $st)
                            <option value="{{ $st }}" @selected($s->status===$st)>{{ $st }}</option>
                        @endforeach
                    </select>
                    <select name="plan_id">
                        @foreach($plans as $p)
                            <option value="{{ $p->id }}" @selected($s->plan_id==$p->id)>{{ $p->name }}</option>
                        @endforeach
                    </select>
                    <select name="billing_cycle">
                        <option value="monthly" @selected($s->billing_cycle==='monthly')>monthly</option>
                        <option value="annual" @selected($s->billing_cycle==='annual')>annual</option>
                    </select>
                    <button type="submit" class="px-2 py-1 bg-slate-800 text-white text-xs rounded">Update</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
{{ $subscriptions->links() }}
</div>
@endsection
