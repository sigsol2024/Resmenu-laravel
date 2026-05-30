@extends('layouts.manager')
@section('title', 'Menu Items')
@push('head')
<link rel="stylesheet" href="{{ asset('legacy/css/pages/manager-menu-items.css') }}">
@endpush
@section('content')
<div class="page-header">
    <h1 class="page-title">Menu Items</h1>
    <p class="page-subtitle">Manage your restaurant menu items, prices, and availability</p>
</div>

<div class="settings-card">
    <div style="margin-bottom: 20px;">
        <form method="GET" action="{{ route('manager.menu-items.index') }}" style="display: flex; gap: 15px; align-items: end; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 220px;">
                <label class="form-label" for="category_filter">Filter by Category</label>
                <select id="category_filter" name="category_id" class="form-select">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected($selectedCategoryId == $cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="min-width: 110px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                Filter
            </button>
            @if($selectedCategoryId)
                <a href="{{ route('manager.menu-items.index') }}" class="btn btn-secondary" style="min-width: 140px;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Clear Filter
                </a>
            @endif
        </form>
    </div>
</div>

<div class="settings-card">
    <div class="section-header">
        <h2 class="section-title">All Menu Items</h2>
        <a href="{{ route('manager.menu-items.create') }}" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            New Item
        </a>
    </div>

    <div class="table-wrapper menu-items-table-desktop">
        <table class="table restaurants-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Order</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                    <tr>
                        <td>
                            @if($item->image)
                                <img src="{{ $uploadUrl }}/menu-items/{{ rawurlencode($item->image) }}" alt="" class="menu-item-image">
                            @else
                                <div class="menu-item-image" style="background:#f0f0f0;display:flex;align-items:center;justify-content:center;color:#999;font-size:0.65rem;">No Image</div>
                            @endif
                        </td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->category->name ?? '—' }}</td>
                        <td>{{ \App\Support\PriceFormatter::format($item->price) }}</td>
                        <td>{{ $item->display_order }}</td>
                        <td>
                            <span style="padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;background:{{ $item->is_available ? '#d1fae5' : '#fee2e2' }};color:{{ $item->is_available ? '#065f46' : '#991b1b' }};">
                                {{ $item->is_available ? 'Available' : 'Unavailable' }}
                            </span>
                        </td>
                        <td class="actions-cell">
                            <button class="actions-btn" type="button" title="Actions">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                </svg>
                            </button>
                            <div class="actions-dropdown">
                                <a href="{{ route('manager.menu-items.edit', $item) }}" class="actions-dropdown-item">Edit</a>
                                <div class="actions-dropdown-divider"></div>
                                <form method="post" action="{{ route('manager.menu-items.destroy', $item) }}" style="display:contents;" onsubmit="return confirm('Delete this menu item?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="actions-dropdown-item danger">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center;padding:40px;color:#6b7280;">No menu items found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="menu-items-mobile" aria-label="Menu items (mobile)">
        @forelse($items as $item)
            <details class="mi-card">
                <summary class="mi-summary">
                    <div class="mi-left">
                        @if($item->image)
                            <img src="{{ $uploadUrl }}/menu-items/{{ rawurlencode($item->image) }}" alt="" class="mi-thumb">
                        @else
                            <div class="mi-thumb mi-thumb-empty">No Image</div>
                        @endif
                        <div class="mi-main">
                            <div class="mi-name">{{ $item->name }}</div>
                            <div class="mi-meta">
                                <span>{{ $item->category->name ?? '—' }}</span>
                                <span class="mi-dot">•</span>
                                <span>{{ \App\Support\PriceFormatter::format($item->price) }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="mi-right">
                        <span class="mi-status" style="background:{{ $item->is_available ? '#d1fae5' : '#fee2e2' }};color:{{ $item->is_available ? '#065f46' : '#991b1b' }};">
                            {{ $item->is_available ? 'Available' : 'Unavailable' }}
                        </span>
                        <span class="mi-chevron" aria-hidden="true">▾</span>
                    </div>
                </summary>
                <div class="mi-body">
                    <div class="mi-grid">
                        <div class="mi-kv"><span class="mi-k">Order</span><span class="mi-v">{{ $item->display_order }}</span></div>
                        <div class="mi-kv"><span class="mi-k">Slug</span><span class="mi-v">{{ $item->slug }}</span></div>
                    </div>
                    <div class="mi-actions">
                        <a class="btn btn-secondary" href="{{ route('manager.menu-items.edit', $item) }}">Edit</a>
                        <form method="post" action="{{ route('manager.menu-items.destroy', $item) }}" style="flex:1;display:flex;" onsubmit="return confirm('Delete this menu item?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="flex:1;justify-content:center;">Delete</button>
                        </form>
                    </div>
                </div>
            </details>
        @empty
            <p style="text-align:center;padding:18px;color:#6b7280;">No menu items found.</p>
        @endforelse
    </div>

    @if($items->hasPages())
        <div style="padding:16px 0 0;">{{ $items->links() }}</div>
    @endif
</div>
@endsection
