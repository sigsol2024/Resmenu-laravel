@extends('layouts.manager')
@section('title', 'Categories')
@section('content')
<div class="page-header">
    <h1 class="page-title">Categories</h1>
    <p class="page-subtitle">Organize your menu into categories</p>
</div>
<div class="card">
    <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;">
        <h2 class="card-title">All categories</h2>
        <a href="{{ route('manager.categories.create') }}" class="btn btn-primary">Add category</a>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Section</th>
                    <th>Items</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($categories as $cat)
                <tr>
                    <td>{{ $cat->name }}</td>
                    <td>{{ $cat->section->name ?? '—' }}</td>
                    <td>{{ $cat->menu_items_count }}</td>
                    <td>
                        <a href="{{ route('manager.categories.edit', $cat) }}" class="btn btn-secondary btn-small">Edit</a>
                        <form action="{{ route('manager.categories.destroy', $cat) }}" method="post" style="display:inline;" onsubmit="return confirm('Delete this category?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-small">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4">No categories yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
