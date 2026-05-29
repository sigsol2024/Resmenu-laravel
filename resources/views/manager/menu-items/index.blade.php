@extends('layouts.manager')
@section('title', 'Menu items')
@push('head')
<link rel="stylesheet" href="{{ asset('legacy/css/pages/manager-menu-items.css') }}">
@endpush
@section('content')
<div class="page-header">
    <h1 class="page-title">Menu Items</h1>
    <p class="page-subtitle">Manage your restaurant menu items, prices, and availability</p>
</div>
<div class="card">
    <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;">
        <h2 class="card-title">All menu items</h2>
        <a href="{{ route('manager.menu-items.create') }}" class="btn btn-primary">Add item</a>
    </div>
<table>
    <thead><tr><th>Name</th><th>Category</th><th>Price</th><th>Actions</th></tr></thead>
    <tbody>
    @forelse($items as $item)
        <tr>
            <td>{{ $item->name }}</td>
            <td>{{ $item->category->name ?? '—' }}</td>
            <td>{{ \App\Support\PriceFormatter::format($item->price) }}</td>
            <td>
                @include('partials.admin.actions-dropdown', [
                  'items' => [
                    ['label' => 'Edit', 'url' => route('manager.menu-items.edit', $item)],
                    [
                      'type' => 'form',
                      'label' => 'Delete',
                      'action' => route('manager.menu-items.destroy', $item),
                      'method' => 'DELETE',
                      'class' => 'danger',
                      'confirm' => 'Delete this menu item?',
                    ],
                  ],
                ])
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
