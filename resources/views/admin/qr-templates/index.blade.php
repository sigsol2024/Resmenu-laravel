@extends('layouts.admin')
@section('title', 'QR templates')
@section('content')
<div class="card"><h1>QR templates</h1>
<table class="mt-4"><thead><tr><th>ID</th><th>Name</th></tr></thead>
<tbody>@foreach($templates as $t)<tr><td>{{ $t->id }}</td><td>{{ $t->name ?? '-' }}</td></tr>@endforeach</tbody></table></div>
@endsection
