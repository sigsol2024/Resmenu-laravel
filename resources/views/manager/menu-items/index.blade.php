@extends('layouts.manager')
@section('title', 'Menu items')
@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
    <h1 style="margin:0;">Menu items</h1>
    <a href="{{ route('manager.menu-items.create') }}" class="btn btn-primary">Add item</a>
</div>
<div class="card">
<table>
    <thead><tr><th>Name</th><th>Category</th><th>Price</th><th></th></tr></thead>
    <tbody>
    @forelse($items as $item)
        <tr>
            <td>{{ $item->name }}</td>
            <td>{{ $item->category->name ?? '—' }}</td>
            <td>{{ \App\Support\PriceFormatter::format($item->price) }}</td>
            <td>
                <a href="{{ route('manager.menu-items.edit', $item) }}">Edit</a>
                <form action="{{ route('manager.menu-items.destroy', $item) }}" method="post" style="display:inline;" onsubmit="return confirm('Delete?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger" style="padding:4px 8px;margin-left:8px;">Delete</button>
                </form>
            </td>
        </tr>
    @empty
        <tr><td colspan="4">No menu items yet.</td></tr>
    @endforelse
    </tbody>
</table>
{{ $items->links() }}
</div>
@endsection
