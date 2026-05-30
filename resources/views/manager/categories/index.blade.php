@extends('layouts.manager')
@section('title', 'Category Management')
@push('head')
<link rel="stylesheet" href="{{ resmenu_public_asset('css/pages/manager-categories.css') }}">
@endpush
@section('content')
@php
    $isEditing = (bool) $editCategory;
    $showModal = $isEditing || $openCreateModal || $errors->any();
    $editLink = fn (int $id) => route('manager.categories.index', ['edit' => $id]);
    $activeChecked = old('is_active') !== null ? (bool) old('is_active') : ($editCategory->is_active ?? true);
    $primarySectionId = (int) old('section_id', $editCategory->section_id ?? 0);
    $selectedSecondary = array_map('intval', old('secondary_section_ids', $secondarySectionIds ?? []));
@endphp

<div class="page-header">
    <h1 class="page-title">Category Management</h1>
    <p class="page-subtitle">Create and manage menu categories for your restaurant</p>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if($errors->has('limit'))
    <div class="alert alert-error">{{ $errors->first('limit') }}</div>
@endif

<div class="modal" id="categoryModal" style="display: {{ $showModal ? 'flex' : 'none' }};">
    <div class="modal-overlay" onclick="closeCategoryModal()"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">{{ $isEditing ? 'Edit Category' : 'Create New Category' }}</h2>
            <button type="button" class="modal-close" onclick="closeCategoryModal()" aria-label="Close">&times;</button>
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
                  action="{{ $isEditing ? route('manager.categories.update', $editCategory) : route('manager.categories.store') }}"
                  enctype="multipart/form-data">
                @csrf
                @if($isEditing)
                    @method('PUT')
                @endif

                <div class="form-group">
                    <label class="form-label" for="name">Category Name *</label>
                    <input type="text" id="name" name="name" class="form-input" required value="{{ old('name', $editCategory->name ?? '') }}">
                </div>

                <div class="form-group">
                    <label class="form-label" for="section_id">Section *</label>
                    <select id="section_id" name="section_id" class="form-select" required>
                        <option value="">— Select section —</option>
                        @foreach($sections as $sec)
                            <option value="{{ $sec->id }}" @selected($primarySectionId === (int) $sec->id)>{{ $sec->name }}</option>
                        @endforeach
                    </select>
                    <p style="margin-top: 6px; font-size: 12px; color: #6b7280;">Categories are grouped under sections on your menu. <a href="{{ route('manager.sections.index') }}">Manage sections</a></p>
                </div>

                @if($sections->count() > 0)
                    <div class="form-group">
                        <label class="form-label">Secondary Sections (optional)</label>
                        <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                            @foreach($sections as $sec)
                                @php
                                    $sid = (int) $sec->id;
                                    $isPrimary = $sid === $primarySectionId && $primarySectionId > 0;
                                    $isSecondary = in_array($sid, $selectedSecondary, true);
                                @endphp
                                <label style="display: {{ $isPrimary ? 'none' : 'flex' }}; align-items: center; gap: 8px; font-size: 13px; color: #6b7280;">
                                    <input type="checkbox" name="secondary_section_ids[]" value="{{ $sid }}" @checked($isSecondary && ! $isPrimary)>
                                    {{ $sec->name }}
                                </label>
                            @endforeach
                        </div>
                        <p style="margin-top: 6px; font-size: 12px; color: #6b7280;">This category will also appear on those section pages only (not on the main full menu page).</p>
                    </div>
                @endif

                <div class="form-group">
                    <label class="form-label" for="description">Description</label>
                    <textarea id="description" name="description" class="form-textarea" rows="3">{{ old('description', $editCategory->description ?? '') }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label" for="image">Category Image</label>
                    <input type="file" id="image" name="image" class="form-input" accept="image/*">
                    @if($isEditing && $editCategory->image)
                        <div style="margin-top: 10px;">
                            <p style="margin-bottom: 5px; color: #6b7280;">Current image:</p>
                            <img src="{{ $uploadUrl }}/categories/{{ rawurlencode($editCategory->image) }}" alt="Current image" style="max-width: 300px; max-height: 200px; border-radius: 8px; border: 2px solid #e5e7eb;">
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label class="form-label" for="display_order">Display Order</label>
                    <input type="number" id="display_order" name="display_order" class="form-input" value="{{ old('display_order', $editCategory->display_order ?? 0) }}">
                </div>

                <div class="form-group">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <input type="checkbox" id="is_active" name="is_active" value="1" style="width: 20px; height: 20px;" @checked($activeChecked)>
                        <label class="form-label" for="is_active" style="margin: 0;">Active</label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeCategoryModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        {{ $isEditing ? 'Update Category' : 'Create Category' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal" id="deleteModal" style="display: none;">
    <div class="modal-overlay" onclick="closeDeleteModal()"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Delete Category</h2>
            <button type="button" class="modal-close" onclick="closeDeleteModal()" aria-label="Close">&times;</button>
        </div>
        <div class="modal-body">
            <p id="deleteModalMessage" style="margin-bottom: 20px; font-size: 16px;">Are you sure you want to delete this category?</p>
            <p style="margin-bottom: 20px; color: #dc2626; font-weight: 600;">This action cannot be undone. This will delete:</p>
            <ul style="margin-left: 20px; margin-bottom: 20px; color: #6b7280;">
                <li>The category and all its information</li>
                <li>All menu items in this category</li>
                <li>The category image</li>
            </ul>
            <form method="POST" action="" id="deleteForm">
                @csrf
                @method('DELETE')
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Yes, Delete Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="settings-card">
    <div class="section-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
        <h2 class="section-title">All Categories</h2>
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <a href="{{ route('manager.sections.index') }}" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" /></svg>
                Manage Sections
            </a>
            @if(! $isEditing)
                <button type="button" class="btn btn-primary" onclick="openCategoryModal()">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    New Category
                </button>
            @endif
        </div>
    </div>

    <div class="table-wrapper categories-table-desktop">
        <table class="table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Section</th>
                    <th>Items</th>
                    <th>Order</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $cat)
                    <tr>
                        <td>
                            @if($cat->image)
                                <img src="{{ $uploadUrl }}/categories/{{ rawurlencode($cat->image) }}" alt="" class="menu-item-image">
                            @else
                                <div class="menu-item-image" style="background:#f0f0f0;display:flex;align-items:center;justify-content:center;color:#999;font-size:0.65rem;">No Image</div>
                            @endif
                        </td>
                        <td>{{ $cat->name }}</td>
                        <td>{{ $cat->section->name ?? '—' }}</td>
                        <td>
                            <a href="{{ route('manager.menu-items.index', ['category_id' => $cat->id]) }}" class="cat-item-count-link" title="View menu items in this category">
                                {{ $cat->menu_items_count }} {{ $cat->menu_items_count === 1 ? 'item' : 'items' }}
                            </a>
                        </td>
                        <td>{{ $cat->display_order }}</td>
                        <td>
                            <span style="padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;background:{{ $cat->is_active ? '#d1fae5' : '#fee2e2' }};color:{{ $cat->is_active ? '#065f46' : '#991b1b' }};">
                                {{ $cat->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="actions-cell">
                            <button class="actions-btn" type="button" title="Actions">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                </svg>
                            </button>
                            <div class="actions-dropdown">
                                <a href="{{ $editLink($cat->id) }}" class="actions-dropdown-item">Edit</a>
                                <a href="{{ route('manager.menu-items.index', ['category_id' => $cat->id]) }}" class="actions-dropdown-item">View Items</a>
                                <div class="actions-dropdown-divider"></div>
                                <button type="button" class="actions-dropdown-item danger" onclick="openDeleteModal({{ $cat->id }}, @json($cat->name), @json(route('manager.categories.destroy', $cat)))">Delete</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center;padding:40px;color:#6b7280;">No categories found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="categories-mobile" aria-label="Categories (mobile)">
        @forelse($categories as $cat)
            <details class="cat-card">
                <summary class="cat-summary">
                    <div class="cat-left">
                        @if($cat->image)
                            <img src="{{ $uploadUrl }}/categories/{{ rawurlencode($cat->image) }}" alt="" class="cat-thumb">
                        @else
                            <div class="cat-thumb cat-thumb-empty">No Image</div>
                        @endif
                        <div class="cat-main">
                            <div class="cat-name">{{ $cat->name }}</div>
                            <div class="cat-meta">
                                <span>{{ $cat->menu_items_count }} {{ $cat->menu_items_count === 1 ? 'item' : 'items' }}</span>
                                <span class="cat-dot">•</span>
                                <span>Order: {{ $cat->display_order }}</span>
                                <span class="cat-dot">•</span>
                                <span>{{ $cat->slug }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="cat-right">
                        <span class="cat-status" style="background:{{ $cat->is_active ? '#d1fae5' : '#fee2e2' }};color:{{ $cat->is_active ? '#065f46' : '#991b1b' }};">
                            {{ $cat->is_active ? 'Active' : 'Inactive' }}
                        </span>
                        <span class="cat-chevron" aria-hidden="true">▾</span>
                    </div>
                </summary>
                <div class="cat-body">
                    @if($cat->description)
                        <p style="margin:0 0 12px;color:#6b7280;font-size:0.875rem;">{{ $cat->description }}</p>
                    @endif
                    <div class="cat-grid">
                        <div class="cat-kv"><span class="cat-k">Section</span><span class="cat-v">{{ $cat->section->name ?? '—' }}</span></div>
                    </div>
                    <div class="cat-actions">
                        <a class="btn btn-secondary" href="{{ $editLink($cat->id) }}">Edit</a>
                        <a class="btn btn-secondary" href="{{ route('manager.menu-items.index', ['category_id' => $cat->id]) }}">View Items</a>
                        <button type="button" class="btn btn-danger" onclick="openDeleteModal({{ $cat->id }}, @json($cat->name), @json(route('manager.categories.destroy', $cat)))">Delete</button>
                    </div>
                </div>
            </details>
        @empty
            <p style="text-align:center;padding:18px;color:#6b7280;">No categories found.</p>
        @endforelse
    </div>
</div>

@push('scripts')
<script>
    const categoriesIndexUrl = @json(route('manager.categories.index'));

    function openCategoryModal() {
        document.getElementById('categoryModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeCategoryModal() {
        document.getElementById('categoryModal').style.display = 'none';
        document.body.style.overflow = '';
        const params = new URLSearchParams(window.location.search);
        if (params.has('edit') || params.has('open')) {
            params.delete('edit');
            params.delete('open');
            const query = params.toString();
            window.location.href = categoriesIndexUrl + (query ? '?' + query : '');
        }
    }

    function openDeleteModal(categoryId, categoryName, deleteUrl) {
        document.getElementById('deleteForm').action = deleteUrl;
        document.getElementById('deleteModalMessage').innerHTML = 'Are you sure you want to delete <strong>"' + categoryName.replace(/</g, '&lt;') + '"</strong>?';
        document.getElementById('deleteModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
        document.body.style.overflow = '';
    }

    @if($showModal)
    document.addEventListener('DOMContentLoaded', function() {
        openCategoryModal();
    });
    @endif
</script>
@endpush
@endsection
