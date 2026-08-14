@extends('layouts.manager')
@section('title', 'Sections')
@push('head')
<link rel="stylesheet" href="{{ resmenu_public_asset('css/pages/manager-sections.css') }}">
@endpush
@section('content')
@php
    $isEditing = (bool) $editSection;
    $showModal = $isEditing || $openCreateModal || $errors->any();
    $editLink = fn (int $id) => route('manager.sections.index', ['edit' => $id]);
    $activeChecked = old('is_active') !== null ? (bool) old('is_active') : ($editSection?->is_active ?? true);
@endphp

<div class="page-header">
    <h1 class="page-title">Manage Sections</h1>
    <p class="page-subtitle">Sections group categories on your menu. Reorder sections to change how they appear.</p>
</div>

<div class="modal" id="sectionModal" style="display: {{ $showModal ? 'flex' : 'none' }};">
    <div class="modal-overlay" onclick="closeSectionModal()"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">{{ $isEditing ? 'Edit Section' : 'Create New Section' }}</h2>
            <button type="button" class="modal-close" onclick="closeSectionModal()" aria-label="Close">&times;</button>
        </div>
        <div class="modal-body">
            @if($errors->any())
                <div class="alert alert-error" style="margin-bottom: 16px;">
                    <ul style="margin: 0; padding-left: 18px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="POST"
                  action="{{ $isEditing ? route('manager.sections.update', $editSection) : route('manager.sections.store') }}"
                  id="sectionForm"
                  enctype="multipart/form-data">
                @csrf
                @if($isEditing)
                    @method('PUT')
                @endif

                <div class="form-group">
                    <label class="form-label" for="name">Section Name *</label>
                    <input type="text" id="name" name="name" class="form-input" required value="{{ old('name', $editSection->name ?? '') }}">
                </div>

                <div class="form-group">
                    <label class="form-label" for="image">Section Image</label>
                    <input type="file" id="image" name="image" class="form-input" accept="image/*">
                    <p style="margin: 6px 0 0; font-size: 0.8rem; color: #6b7280;">Optional hero/cover image for this section on your menu (JPG, PNG, WebP, max 5MB).</p>
                    @if($isEditing && $editSection->image)
                        <div class="sec-img-current">
                            <p class="sec-img-current-label">Current image:</p>
                            <div class="sec-img-current-box">
                                <img src="{{ $uploadUrl }}/sections/{{ rawurlencode($editSection->image) }}" alt="Section image">
                            </div>
                            <label class="sec-img-remove">
                                <input type="checkbox" name="remove_image" value="1" @checked(old('remove_image'))>
                                Remove current image
                            </label>
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label class="form-label" for="display_order">Display Order</label>
                    <input type="number" id="display_order" name="display_order" class="form-input" value="{{ old('display_order', $editSection->display_order ?? $nextDisplayOrder) }}" min="0">
                    <p style="margin: 6px 0 0; font-size: 0.8rem; color: #6b7280;">Lower numbers appear first on your menu. New sections are given the next free slot automatically.</p>
                </div>

                <div class="form-group">
                    <label class="form-label" style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="checkbox" name="is_active" value="1" @checked($activeChecked)>
                        Active (visible on public menu)
                    </label>
                </div>

                <div class="modal-footer" style="padding: 0; border: none; margin-top: 8px;">
                    <button type="button" class="btn btn-secondary" onclick="closeSectionModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">{{ $isEditing ? 'Update Section' : 'Create Section' }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal" id="deleteModal" style="display: none;">
    <div class="modal-overlay" onclick="closeDeleteModal()"></div>
    <div class="modal-content" style="max-width: 420px;">
        <div class="modal-header">
            <h2 class="modal-title">Delete Section</h2>
            <button type="button" class="modal-close" onclick="closeDeleteModal()" aria-label="Close">&times;</button>
        </div>
        <div class="modal-body">
            <p id="deleteModalMessage" style="margin: 0 0 20px; color: #374151;"></p>
            <form method="POST" id="deleteForm">
                @csrf
                @method('DELETE')
                <div class="modal-footer" style="padding: 0; border: none;">
                    <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">Cancel</button>
                    <button type="submit" class="btn btn-danger">Yes, Delete Section</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="settings-card">
    <div class="section-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
        <h2 class="section-title">All Sections</h2>
        @if(! $isEditing)
            <button type="button" class="btn btn-primary" onclick="openSectionModal()">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                New Section
            </button>
        @endif
    </div>

    <div class="table-wrapper sections-table-desktop">
        <table class="table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Categories</th>
                    <th>Order</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sections as $section)
                    <tr>
                        <td class="sec-img-cell">
                            @if($section->image)
                                <img src="{{ $uploadUrl }}/sections/{{ rawurlencode($section->image) }}" alt="" width="48" height="48">
                            @else
                                <span class="section-no-image">No image</span>
                            @endif
                        </td>
                        <td><strong>{{ $section->name }}</strong></td>
                        <td><code style="font-size: 0.8rem; background: #f3f4f6; padding: 2px 6px; border-radius: 4px;">{{ $section->slug }}</code></td>
                        <td>
                            <a href="{{ route('manager.categories.index', ['section_id' => $section->id]) }}" class="section-cat-link">
                                {{ $section->categories_count }} {{ $section->categories_count === 1 ? 'category' : 'categories' }}
                            </a>
                        </td>
                        <td>{{ $section->display_order }}</td>
                        <td>
                            <span class="status-badge" style="background:{{ $section->is_active ? '#d1fae5' : '#fee2e2' }};color:{{ $section->is_active ? '#065f46' : '#991b1b' }};">
                                {{ $section->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="actions-cell">
                            <button class="actions-btn" type="button" title="Actions">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                </svg>
                            </button>
                            <div class="actions-dropdown">
                                <a href="{{ $editLink($section->id) }}" class="actions-dropdown-item">Edit</a>
                                <a href="{{ route('manager.categories.index') }}" class="actions-dropdown-item">Manage Categories</a>
                                <div class="actions-dropdown-divider"></div>
                                <button type="button" class="actions-dropdown-item danger" onclick="openDeleteModal({{ $section->id }}, @json($section->name), @json(route('manager.sections.destroy', $section)))">Delete</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center;padding:40px;color:#6b7280;">
                            No sections yet.
                            <button type="button" class="btn btn-primary btn-small" style="margin-top:12px;" onclick="openSectionModal()">Create your first section</button>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="sections-mobile" aria-label="Sections (mobile)">
        @forelse($sections as $section)
            <details class="sec-card">
                <summary class="sec-summary">
                    <div class="sec-main">
                        <div class="sec-name">{{ $section->name }}</div>
                        <div class="sec-meta">
                            <span>{{ $section->categories_count }} {{ $section->categories_count === 1 ? 'category' : 'categories' }}</span>
                            <span class="sec-dot">•</span>
                            <span>Order: {{ $section->display_order }}</span>
                        </div>
                    </div>
                    <div class="sec-right">
                        <span class="status-badge" style="background:{{ $section->is_active ? '#d1fae5' : '#fee2e2' }};color:{{ $section->is_active ? '#065f46' : '#991b1b' }};">
                            {{ $section->is_active ? 'Active' : 'Inactive' }}
                        </span>
                        <span class="sec-chevron" aria-hidden="true">▾</span>
                    </div>
                </summary>
                <div class="sec-body">
                    <div class="sec-kv"><span class="sec-k">Image</span><span class="sec-v">
                        @if($section->image)
                            <span class="sec-img-cell sec-img-cell--inline">
                                <img src="{{ $uploadUrl }}/sections/{{ rawurlencode($section->image) }}" alt="" width="48" height="48">
                            </span>
                        @else
                            <span class="section-no-image">No image</span>
                        @endif
                    </span></div>
                    <div class="sec-kv"><span class="sec-k">Slug</span><span class="sec-v">{{ $section->slug }}</span></div>
                    <div class="sec-actions">
                        <a class="btn btn-secondary" href="{{ $editLink($section->id) }}">Edit</a>
                        <button type="button" class="btn btn-danger" onclick="openDeleteModal({{ $section->id }}, @json($section->name), @json(route('manager.sections.destroy', $section)))">Delete</button>
                    </div>
                </div>
            </details>
        @empty
            <p style="text-align:center;padding:18px;color:#6b7280;">No sections yet.</p>
        @endforelse
    </div>
</div>

@push('scripts')
<script>
    const sectionsIndexUrl = @json(route('manager.sections.index'));

    function openSectionModal() {
        document.getElementById('sectionModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeSectionModal() {
        document.getElementById('sectionModal').style.display = 'none';
        document.body.style.overflow = '';
        const params = new URLSearchParams(window.location.search);
        if (params.has('edit') || params.has('open')) {
            params.delete('edit');
            params.delete('open');
            const query = params.toString();
            window.location.href = sectionsIndexUrl + (query ? '?' + query : '');
        }
    }

    function openDeleteModal(sectionId, sectionName, deleteUrl) {
        document.getElementById('deleteForm').action = deleteUrl;
        document.getElementById('deleteModalMessage').innerHTML = 'Are you sure you want to delete <strong>"' + sectionName.replace(/</g, '&lt;') + '"</strong>? Categories in this section may need to be reassigned.';
        document.getElementById('deleteModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
        document.body.style.overflow = '';
    }

    @if($showModal)
    document.addEventListener('DOMContentLoaded', function() {
        openSectionModal();
    });
    @endif
</script>
@endpush
@endsection
