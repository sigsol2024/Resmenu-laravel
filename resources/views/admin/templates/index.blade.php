@extends('layouts.admin')
@section('title', 'Templates')
@section('content')
<div class="card"><h1>Menu templates</h1>
<table class="mt-4"><thead><tr><th>ID</th><th>Name</th><th>Active</th></tr></thead>
<tbody>
@foreach($templates as $t)
<tr>
    <td>{{ $t->id }}</td>
    <td>{{ $t->name ?? 'Template '.$t->id }}</td>
    <td>{{ ($t->is_active ?? 1) ? 'Active' : 'Inactive' }}</td>
    <td>
        <form method="post" action="{{ route('admin.templates.toggle', $t->id) }}">@csrf
            <button type="submit">Toggle</button>
        </form>
    </td>
</tr>
@endforeach
</tbody></table></div>
@endsection
