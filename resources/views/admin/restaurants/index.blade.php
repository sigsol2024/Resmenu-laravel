@extends('layouts.admin')
@section('title', 'Restaurants')
@section('content')
@include('partials.admin.page-header', ['title' => 'Restaurants', 'subtitle' => 'All platform restaurants'])

<div style="margin-bottom:16px;display:flex;gap:12px;flex-wrap:wrap;align-items:center">
  <a href="{{ route('admin.restaurants.create') }}" class="btn-filter" style="text-decoration:none">+ New restaurant</a>
  <form method="get" style="display:flex;gap:8px">
    <input type="text" name="q" value="{{ $q }}" placeholder="Search name or slug" style="padding:10px;border:1px solid #d1d5db;border-radius:6px">
    <button type="submit" class="btn-filter">Search</button>
  </form>
</div>

<div class="card table-card">
<table class="data-table">
  <thead><tr><th>Name</th><th>Slug</th><th>Active</th><th>Template</th><th></th></tr></thead>
  <tbody>
  @forelse($restaurants as $r)
    <tr>
      <td><a href="{{ route('admin.restaurants.show', $r) }}">{{ $r->name }}</a></td>
      <td>{{ $r->slug }}</td>
      <td>{{ $r->is_active ? 'Yes' : 'No' }}</td>
      <td>{{ $r->template_id }}</td>
      <td>
        <a href="{{ route('admin.restaurants.edit', $r) }}">Edit</a>
        <form method="post" action="{{ route('admin.restaurants.destroy', $r) }}" style="display:inline" onsubmit="return confirm('Delete restaurant and all data?')">@csrf @method('DELETE')<button type="submit">Delete</button></form>
      </td>
    </tr>
  @empty
    <tr><td colspan="5">No restaurants.</td></tr>
  @endforelse
  </tbody>
</table>
{{ $restaurants->links() }}
</div>
@endsection
@push('head')<link rel="stylesheet" href="{{ asset('assets/css/admin-payments.css') }}">@endpush
