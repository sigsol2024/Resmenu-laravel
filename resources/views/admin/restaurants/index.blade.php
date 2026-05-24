@extends('layouts.admin')
@section('title', 'Restaurants')
@section('content')
<h1 style="margin:0 0 20px;">Restaurants</h1>
<div class="card">
<table>
    <thead><tr><th>Name</th><th>Slug</th><th>Active</th><th>Template</th></tr></thead>
    <tbody>
    @forelse($restaurants as $r)
        <tr>
            <td><a href="{{ route('admin.restaurants.show', $r) }}">{{ $r->name }}</a></td>
            <td>{{ $r->slug }}</td>
            <td>{{ $r->is_active ? 'Yes' : 'No' }}</td>
            <td>{{ $r->template_id }}</td>
        </tr>
    @empty
        <tr><td colspan="4">No restaurants.</td></tr>
    @endforelse
    </tbody>
</table>
{{ $restaurants->links() }}
</div>
@endsection
