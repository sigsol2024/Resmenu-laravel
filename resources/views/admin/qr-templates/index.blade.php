@extends('layouts.admin')
@section('title', 'QR templates')
@section('content')
@include('partials.admin.page-header', ['title' => 'QR templates', 'subtitle' => 'QR code style presets'])

<div style="margin-bottom:16px"><a href="{{ route('admin.qr-templates.create') }}" class="btn-filter" style="text-decoration:none;display:inline-block">+ New template</a></div>

<div class="card table-card">
<table class="data-table">
  <thead><tr><th>ID</th><th>Name</th><th>Has text</th><th>Active</th><th></th></tr></thead>
  <tbody>
  @forelse($templates as $t)
    <tr>
      <td>{{ $t->id }}</td>
      <td>{{ $t->name }}</td>
      <td>{{ $t->has_text ? 'Yes' : 'No' }}</td>
      <td>{{ $t->is_active ? 'Yes' : 'No' }}</td>
      <td>
        <a href="{{ route('admin.qr-templates.edit', $t->id) }}">Edit</a>
        <form method="post" action="{{ route('admin.qr-templates.destroy', $t->id) }}" style="display:inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button type="submit">Delete</button></form>
      </td>
    </tr>
  @empty
    <tr><td colspan="5">No QR templates yet.</td></tr>
  @endforelse
  </tbody>
</table>
</div>
@endsection
@push('head')<link rel="stylesheet" href="{{ asset('assets/css/admin-payments.css') }}">@endpush
