@extends('layouts.admin')
@section('title', 'Templates')
@section('content')
@include('partials.admin.page-header', ['title' => 'Menu templates', 'subtitle' => 'Manage template availability and assignments'])

<div class="card table-card">
<table class="data-table">
  <thead><tr><th>ID</th><th>Name</th><th>Active</th><th>Private</th><th>Plans</th><th></th></tr></thead>
  <tbody>
  @foreach($templates as $t)
  <tr>
    <td>{{ $t->id }}</td>
    <td>{{ $t->name ?? 'Template '.$t->id }}</td>
    <td>{{ ($t->is_active ?? 1) ? 'Yes' : 'No' }}</td>
    <td>{{ ($t->is_private ?? 0) ? 'Yes' : 'No' }}</td>
    <td>{{ count($planIdsByTemplate[$t->id] ?? []) }} plan(s)</td>
    <td>
      <a href="{{ route('admin.templates.edit', $t->id) }}">Edit</a>
      <a href="{{ route('public.template.preview', $t->id) }}" target="_blank">Preview</a>
      <form method="post" action="{{ route('admin.templates.toggle', $t->id) }}" style="display:inline">@csrf<button type="submit">Toggle active</button></form>
    </td>
  </tr>
  @endforeach
  </tbody>
</table>
</div>
@endsection
@push('head')<link rel="stylesheet" href="{{ asset('assets/css/admin-payments.css') }}">@endpush
