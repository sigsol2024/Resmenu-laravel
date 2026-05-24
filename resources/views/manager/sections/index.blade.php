@extends('layouts.manager')
@section('title', 'Sections')
@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
    <h1 style="margin:0;">Sections</h1>
    <a href="{{ route('manager.sections.create') }}" class="btn btn-primary">Add section</a>
</div>
<div class="card">
<table>
    <thead><tr><th>Name</th><th>Slug</th><th>Categories</th><th>Order</th><th></th></tr></thead>
    <tbody>
    @forelse($sections as $section)
        <tr>
            <td>{{ $section->name }}</td>
            <td>{{ $section->slug }}</td>
            <td>{{ $section->categories_count }}</td>
            <td>{{ $section->display_order }}</td>
            <td>
                <a href="{{ route('manager.sections.edit', $section) }}">Edit</a>
                <form action="{{ route('manager.sections.destroy', $section) }}" method="post" style="display:inline;" onsubmit="return confirm('Delete this section?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger" style="padding:4px 8px;margin-left:8px;">Delete</button>
                </form>
            </td>
        </tr>
    @empty
        <tr><td colspan="5">No sections yet.</td></tr>
    @endforelse
    </tbody>
</table>
</div>
@endsection
