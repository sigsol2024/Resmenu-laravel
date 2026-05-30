@extends('layouts.manager')
@section('title', 'Menu Items')
@push('head')
<link rel="stylesheet" href="{{ resmenu_public_asset('css/pages/manager-menu-items.css') }}">
@endpush
@section('content')
@php
    $isEditing = (bool) $editItem;
    $showModal = $isEditing || $openCreateModal || $errors->any();
    $indexParams = array_filter(['category_id' => $selectedCategoryId]);
    $editLink = fn (int $id) => route('manager.menu-items.index', array_merge($indexParams, ['edit' => $id]));
    $availableChecked = old('is_available') !== null ? (bool) old('is_available') : ($editItem->is_available ?? true);
@endphp

<div class="page-header">
    <h1 class="page-title">Menu Items</h1>
    <p class="page-subtitle">Manage your restaurant menu items, prices, and availability</p>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if($errors->has('limit'))
    <div class="alert alert-error">{{ $errors->first('limit') }}</div>
@endif

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

{{-- Create/Edit Menu Item Modal --}}
<div class="modal" id="menuItemModal" style="display: {{ $showModal ? 'flex' : 'none' }};">
    <div class="modal-overlay" onclick="closeMenuItemModal()"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">{{ $isEditing ? 'Edit Menu Item' : 'Create New Menu Item' }}</h2>
            <button class="modal-close" type="button" onclick="closeMenuItemModal()" aria-label="Close">&times;</button>
        </div>
        <div class="modal-body">
            @if($errors->any() && ! $errors->has('limit'))
                <div class="alert alert-error" style="margin-bottom: 16px;">
                    <ul style="margin: 0; padding-left: 18px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="POST"
                  action="{{ $isEditing ? route('manager.menu-items.update', $editItem) : route('manager.menu-items.store') }}"
                  enctype="multipart/form-data">
                @csrf
                @if($isEditing)
                    @method('PUT')
                @endif
                @if($selectedCategoryId)
                    <input type="hidden" name="_return_category_id" value="{{ $selectedCategoryId }}">
                @endif

                <div class="form-group">
                    <label class="form-label" for="category_id">Category *</label>
                    <select id="category_id" name="category_id" class="form-select" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" @selected((string) old('category_id', $editItem->category_id ?? '') === (string) $cat->id)>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="name">Item Name *</label>
                    <input type="text" id="name" name="name" class="form-input" required value="{{ old('name', $editItem->name ?? '') }}">
                </div>

                <div class="form-group">
                    <label class="form-label" for="description">Description</label>
                    <textarea id="description" name="description" class="form-textarea" rows="3">{{ old('description', $editItem->description ?? '') }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label" for="image">Item Image</label>
                    <input type="file" id="image" name="image" class="form-input" accept="image/*">
                    @if($isEditing && $editItem->image)
                        <div style="margin-top: 10px;">
                            <p style="margin-bottom: 5px; color: #6b7280;">Current image:</p>
                            <img src="{{ $uploadUrl }}/menu-items/{{ rawurlencode($editItem->image) }}" alt="Current image" style="max-width: 200px; max-height: 200px; border-radius: 8px; border: 2px solid #e5e7eb;">
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label class="form-label" for="price">Price *</label>
                    <input type="number" id="price" name="price" class="form-input" step="0.01" min="0" required value="{{ old('price', $editItem->price ?? '') }}">
                </div>

                <div class="form-group">
                    <label class="form-label" for="display_order">Display Order</label>
                    <input type="number" id="display_order" name="display_order" class="form-input" value="{{ old('display_order', $editItem->display_order ?? 0) }}">
                </div>

                <div class="form-group">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <input type="checkbox" id="is_available" name="is_available" value="1" style="width: 20px; height: 20px;" @checked($availableChecked)>
                        <label class="form-label" for="is_available" style="margin: 0;">Available</label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeMenuItemModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        {{ $isEditing ? 'Update Menu Item' : 'Create Menu Item' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div class="modal" id="deleteModal" style="display: none;">
    <div class="modal-overlay" onclick="closeDeleteModal()"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Delete Menu Item</h2>
            <button class="modal-close" type="button" onclick="closeDeleteModal()" aria-label="Close">&times;</button>
        </div>
        <div class="modal-body">
            <p id="deleteModalMessage" style="margin-bottom: 20px; font-size: 16px;">Are you sure you want to delete this menu item?</p>
            <p style="margin-bottom: 20px; color: #dc2626; font-weight: 600;">This action cannot be undone. This will delete:</p>
            <ul style="margin-left: 20px; margin-bottom: 20px; color: #6b7280;">
                <li>The menu item and all its information</li>
                <li>The menu item image</li>
            </ul>
            <form method="POST" action="" id="deleteForm">
                @csrf
                @method('DELETE')
                @if($selectedCategoryId)
                    <input type="hidden" name="_return_category_id" value="{{ $selectedCategoryId }}">
                @endif
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Yes, Delete Menu Item
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="settings-card">
    <div class="section-header">
        <h2 class="section-title">All Menu Items</h2>
        @if(! $isEditing)
            <button type="button" class="btn btn-primary" onclick="openMenuItemModal()">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                New Item
            </button>
        @endif
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
                                <a href="{{ $editLink($item->id) }}" class="actions-dropdown-item">Edit</a>
                                <div class="actions-dropdown-divider"></div>
                                <button type="button" class="actions-dropdown-item danger" onclick="openDeleteModal({{ $item->id }}, @json($item->name), @json(route('manager.menu-items.destroy', $item)))">Delete</button>
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
                        <a class="btn btn-secondary" href="{{ $editLink($item->id) }}">Edit</a>
                        <button type="button" class="btn btn-danger" onclick="openDeleteModal({{ $item->id }}, @json($item->name), @json(route('manager.menu-items.destroy', $item)))">Delete</button>
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

@push('scripts')
<script>
    const menuItemsIndexUrl = @json(route('manager.menu-items.index', $indexParams));

    function openMenuItemModal() {
        document.getElementById('menuItemModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeMenuItemModal() {
        document.getElementById('menuItemModal').style.display = 'none';
        document.body.style.overflow = '';
        const params = new URLSearchParams(window.location.search);
        if (params.has('edit') || params.has('open')) {
            params.delete('edit');
            params.delete('open');
            const query = params.toString();
            window.location.href = menuItemsIndexUrl + (query ? '?' + query : '');
        }
    }

    function openDeleteModal(menuItemId, menuItemName, deleteUrl) {
        document.getElementById('deleteForm').action = deleteUrl;
        document.getElementById('deleteModalMessage').innerHTML = 'Are you sure you want to delete <strong>"' + menuItemName.replace(/</g, '&lt;') + '"</strong>?';
        document.getElementById('deleteModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
        document.body.style.overflow = '';
    }

    @if($showModal)
    document.addEventListener('DOMContentLoaded', function() {
        openMenuItemModal();
    });
    @endif
</script>
@endpush
@endsection
