@extends('layouts.admin')
@section('title', 'Manage: ' . $restaurant->name)

@push('head')
<link rel="stylesheet" href="{{ asset('legacy/css/pages/admin-restaurant-view.css') }}">
@endpush

@php
    $sectionsList = $sections;
    $allMenuItems = $menuItems;
    $availableTemplates = $menuTemplates;
    $restaurantSlug = $restaurant->slug;
    $uploadUrl = rtrim(config('resmenu.upload_url'), '/');
    $message = session('success');
    $error = session('error') ?: ($errors->any() ? $errors->first() : null);

    $requestedTab = request()->query('tab', 'menu');
    $resolvedTab = $requestedTab === 'header' ? 'header-footer' : $requestedTab;
    if (($activeTab ?? 'menu') === 'header') {
        $resolvedTab = 'header-footer';
    } elseif (!empty($activeTab) && $activeTab !== 'header') {
        $resolvedTab = $activeTab;
    }
    $activeTab = $resolvedTab;

    $editMenuItem = null;
    $editCategory = null;
    $editCategorySecondarySectionIds = [];
    $editAction = request()->query('action');
    $editId = (int) request()->query('id');

    if ($editAction === 'edit_menu_item' && $editId > 0) {
        $editMenuItem = $allMenuItems->firstWhere('id', $editId);
    }

    if ($editAction === 'edit_category' && $editId > 0) {
        $editCategory = $categories->firstWhere('id', $editId);
        if ($editCategory) {
            $secondaryIds = data_get($editCategory, 'secondary_section_ids', []);
            if (is_string($secondaryIds)) {
                $decoded = json_decode($secondaryIds, true);
                $secondaryIds = is_array($decoded) ? $decoded : [];
            }
            if (is_array($secondaryIds)) {
                $editCategorySecondarySectionIds = array_map('intval', $secondaryIds);
            }
        }
    }

    $currentTemplateId = (int) ($restaurant->template_id ?? 1);
    $currentTemplate = collect($availableTemplates)->first(function ($template) use ($currentTemplateId) {
        return (int) data_get($template, 'id') === $currentTemplateId;
    });
@endphp

@section('content')
@if ($message)
    <div class="alert alert-success">
        {{ $message }}
    </div>
@endif

@if ($error)
    <div class="alert alert-error">
        {{ $error }}
    </div>
@endif

        <div class="card">
            <div class="tabs">
                <button class="tab {{ $activeTab === 'menu' ? 'active' : '' }}" onclick="showTab('menu')">Menu Items</button>
                <button class="tab {{ $activeTab === 'categories' ? 'active' : '' }}" onclick="showTab('categories')">Categories</button>
                <button class="tab {{ $activeTab === 'customization' ? 'active' : '' }}" onclick="showTab('customization')">Customization</button>
                <button class="tab {{ $activeTab === 'header-footer' ? 'active' : '' }}" onclick="showTab('header-footer')">Header & Footer</button>
            </div>

            <!-- Menu Items Tab -->
            <div id="tab-menu" class="tab-content {{ $activeTab === 'menu' ? 'active' : '' }}">
                <div class="search-filter-bar">
                    <input type="text" id="menuSearch" class="search-input" placeholder="Search menu items...">
                    <select id="categoryFilter" class="category-filter">
                        <option value="all">All Categories</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    <button onclick="openMenuItemModal()" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Menu Item
                    </button>
                </div>

                <table class="table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="menuItemsTableBody">
                    @foreach ($allMenuItems as $item)
                        <tr data-category-id="{{ $item->category_id }}" data-item-name="{{ strtolower($item->name ?? '') }}">
                            <td>
                                @if ($item->image)
                                    <img src="{{ $uploadUrl . '/menu-items/' . $item->image }}" alt="" class="menu-item-image">
                                @else
                                    <div class="menu-item-image" style="background: #f0f0f0; display: flex; align-items: center; justify-content: center; color: #999;">No Image</div>
                                @endif
                            </td>
                            <td>{{ $item->name }}</td>
                            <td>{{ data_get($item, 'category.name', 'Uncategorized') }}</td>
                            <td>₦{{ number_format((float) $item->price, 2) }}</td>
                            <td class="actions-cell">
                                <button class="actions-btn" type="button" title="Actions">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                    </svg>
                                </button>
                                <div class="actions-dropdown">
                                    <button type="button" onclick="openMenuItemModal({{ $item->id }})" class="actions-dropdown-item">Edit</button>
                                    <div class="actions-dropdown-divider"></div>
                                    <button type="button" onclick="openDeleteMenuItemModal({{ $item->id }}, '{{ addslashes($item->name ?? '') }}')" class="actions-dropdown-item danger">Delete</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

            <!-- Categories Tab -->
            <div id="tab-categories" class="tab-content {{ $activeTab === 'categories' ? 'active' : '' }}">
                <button onclick="openCategoryModal()" class="btn btn-primary" style="margin-bottom: 20px;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Category
                </button>

                <table class="table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Order</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $cat)
                        <tr>
                            <td>
                                @if ($cat->image)
                                    <img src="{{ $uploadUrl . '/categories/' . $cat->image }}" alt="" class="menu-item-image">
                                @else
                                    <div class="menu-item-image" style="background: #f0f0f0; display: flex; align-items: center; justify-content: center; color: #999;">No Image</div>
                                @endif
                            </td>
                            <td>{{ $cat->name }}</td>
                            <td>{{ $cat->display_order }}</td>
                            <td>{{ $cat->is_active ? 'Active' : 'Inactive' }}</td>
                            <td class="actions-cell">
                                <button class="actions-btn" type="button" title="Actions">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                    </svg>
                                </button>
                                <div class="actions-dropdown">
                                    <button type="button" onclick="openCategoryModal({{ $cat->id }})" class="actions-dropdown-item">Edit</button>
                                    <div class="actions-dropdown-divider"></div>
                                    <button type="button" onclick="openDeleteCategoryModal({{ $cat->id }}, '{{ addslashes($cat->name ?? '') }}')" class="actions-dropdown-item danger">Delete</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>

            <!-- Customization Tab -->
            <div id="tab-customization" class="tab-content {{ $activeTab === 'customization' ? 'active' : '' }}">

                <!-- Template Selection -->
                <div class="card" style="margin-bottom: 24px;">
                    <div class="card-header">
                        <h2 class="card-title">Template Selection</h2>
                    </div>
                    <p style="margin-bottom: 20px; color: var(--muted);">Choose a design template for this restaurant's menu page.</p>
                    <form method="POST" action="{{ route('admin.restaurants.hub', $restaurant) }}" style="margin-bottom: 20px;">
                        @csrf
                        <input type="hidden" name="action" value="save_template">
                        <div class="form-group">
                            <label class="form-label">Select Template</label>
                            <select name="template_id" class="form-select">
                                @foreach ($availableTemplates as $template)
                                    @php
                                        $templateId = (int) data_get($template, 'id');
                                    @endphp
                                    <option value="{{ $templateId }}" {{ $currentTemplateId === $templateId ? 'selected' : '' }}>
                                        {{ data_get($template, 'name') }}
                                        @if (data_get($template, 'description'))
                                            - {{ data_get($template, 'description') }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary" style="margin-top: 15px;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Save Template
                        </button>
                    </form>
                    <p style="color: var(--muted); font-size: 14px;">
                        <strong>Current Template:</strong> Template {{ $currentTemplateId }}
                        @if ($currentTemplate)
                            - {{ data_get($currentTemplate, 'name') }}
                        @endif
                    </p>
                </div>

                <form method="POST" action="{{ route('admin.restaurants.hub', $restaurant) }}" style="margin-top: 24px;">
                    @csrf
                    <input type="hidden" name="action" value="save_customization">
                    <input type="hidden" name="template_id" value="{{ (int) ($restaurant->template_id ?? 1) }}">

                    <div class="card" style="margin-bottom: 24px;">
                        <div class="card-header">
                            <h2 class="card-title">Menu Title</h2>
                        </div>
                        <div class="color-input-group">
                            <div class="form-group">
                                <label class="form-label">Color</label>
                                <input type="color" name="menu_title_color" value="{{ data_get($customization, 'menu_title_color', '#000000') }}" style="width: 60px; height: 40px; border: 2px solid #e5e7eb; border-radius: 8px; cursor: pointer;">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Size (px)</label>
                                <input type="number" name="menu_title_size" class="form-input" value="{{ data_get($customization, 'menu_title_size', 24) }}" min="12" max="72">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Font</label>
                                <select name="menu_title_font" class="form-select">
                                    <option value="Inter" {{ data_get($customization, 'menu_title_font', 'Inter') === 'Inter' ? 'selected' : '' }}>Inter</option>
                                    <option value="Poppins" {{ data_get($customization, 'menu_title_font', 'Inter') === 'Poppins' ? 'selected' : '' }}>Poppins</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="card" style="margin-bottom: 24px;">
                        <div class="card-header">
                            <h2 class="card-title">Price</h2>
                        </div>
                        <div class="color-input-group">
                            <div class="form-group">
                                <label class="form-label">Color</label>
                                <input type="color" name="price_color" value="{{ data_get($customization, 'price_color', '#000000') }}" style="width: 60px; height: 40px; border: 2px solid #e5e7eb; border-radius: 8px; cursor: pointer;">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Size (px)</label>
                                <input type="number" name="price_size" class="form-input" value="{{ data_get($customization, 'price_size', 18) }}" min="12" max="48">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Font</label>
                                <select name="price_font" class="form-select">
                                    <option value="Inter" {{ data_get($customization, 'price_font', 'Inter') === 'Inter' ? 'selected' : '' }}>Inter</option>
                                    <option value="Poppins" {{ data_get($customization, 'price_font', 'Inter') === 'Poppins' ? 'selected' : '' }}>Poppins</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="card" style="margin-bottom: 24px;">
                        <div class="card-header">
                            <h2 class="card-title">Description</h2>
                        </div>
                        <div class="color-input-group">
                            <div class="form-group">
                                <label class="form-label">Color</label>
                                <input type="color" name="description_color" value="{{ data_get($customization, 'description_color', '#666666') }}" style="width: 60px; height: 40px; border: 2px solid #e5e7eb; border-radius: 8px; cursor: pointer;">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Size (px)</label>
                                <input type="number" name="description_size" class="form-input" value="{{ data_get($customization, 'description_size', 14) }}" min="10" max="24">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Font</label>
                                <select name="description_font" class="form-select">
                                    <option value="Inter" {{ data_get($customization, 'description_font', 'Inter') === 'Inter' ? 'selected' : '' }}>Inter</option>
                                    <option value="Poppins" {{ data_get($customization, 'description_font', 'Inter') === 'Poppins' ? 'selected' : '' }}>Poppins</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="card" style="margin-bottom: 24px;">
                        <div class="card-header">
                            <h2 class="card-title">Category Title</h2>
                        </div>
                        <div class="color-input-group">
                            <div class="form-group">
                                <label class="form-label">Color</label>
                                <input type="color" name="category_title_color" value="{{ data_get($customization, 'category_title_color', '#000000') }}" style="width: 60px; height: 40px; border: 2px solid #e5e7eb; border-radius: 8px; cursor: pointer;">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Size (px)</label>
                                <input type="number" name="category_title_size" class="form-input" value="{{ data_get($customization, 'category_title_size', 20) }}" min="12" max="48">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Font</label>
                                <select name="category_title_font" class="form-select">
                                    <option value="Inter" {{ data_get($customization, 'category_title_font', 'Inter') === 'Inter' ? 'selected' : '' }}>Inter</option>
                                    <option value="Poppins" {{ data_get($customization, 'category_title_font', 'Inter') === 'Poppins' ? 'selected' : '' }}>Poppins</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="card" style="margin-bottom: 24px;">
                        <div class="card-header">
                            <h2 class="card-title">Background Colors</h2>
                        </div>
                        <div class="color-input-group">
                            <div class="form-group">
                                <label class="form-label">Page Background</label>
                                <input type="color" name="background_color" value="{{ data_get($customization, 'background_color', '#FFFFFF') }}" style="width: 60px; height: 40px; border: 2px solid #e5e7eb; border-radius: 8px; cursor: pointer;">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Header Background</label>
                                <input type="color" name="header_background_color" value="{{ data_get($customization, 'header_background_color', '#FFFFFF') }}" style="width: 60px; height: 40px; border: 2px solid #e5e7eb; border-radius: 8px; cursor: pointer;">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Primary Color</label>
                                <input type="color" name="primary_color" value="{{ data_get($customization, 'primary_color', '#111111') }}" style="width: 60px; height: 40px; border: 2px solid #e5e7eb; border-radius: 8px; cursor: pointer;">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Secondary Color</label>
                                <input type="color" name="secondary_color" value="{{ data_get($customization, 'secondary_color', '#FFFFFF') }}" style="width: 60px; height: 40px; border: 2px solid #e5e7eb; border-radius: 8px; cursor: pointer;">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Save Customization
                    </button>
                </form>
            </div>

            <!-- Header & Footer Tab -->
            <div id="tab-header-footer" class="tab-content {{ $activeTab === 'header-footer' ? 'active' : '' }}">
                <div class="card" style="margin-bottom: 24px; background: #f0f9ff; border-left: 4px solid #2563eb;">
                    <p style="margin: 0; color: #1e40af; font-size: 14px;">
                        <strong>Note:</strong> The Header Menu Items and Footer Content fields are available for future template customization.
                        Currently, templates use category-based navigation. These fields will be used when templates are updated to support custom header/footer content.
                    </p>
                </div>

                <form method="POST" action="{{ route('admin.restaurants.hub', $restaurant) }}">
                    @csrf
                    <input type="hidden" name="action" value="save_header_footer">

                    <div class="card" style="margin-bottom: 24px;">
                        <div class="card-header">
                            <h2 class="card-title">Header Menu Items</h2>
                        </div>
                        <p style="margin-bottom: 10px; color: var(--muted);">Enter JSON array format: [{"label": "Home", "url": "/"}, {"label": "About", "url": "/about"}]</p>
                        <p style="margin-bottom: 10px; color: var(--muted); font-size: 13px;"><em>Note: This feature is reserved for future template updates.</em></p>
                        <textarea name="header_menu_items" class="json-editor">{{ $restaurant->header_menu_items ?? '[]' }}</textarea>
                    </div>

                    <div class="card" style="margin-bottom: 24px;">
                        <div class="card-header">
                            <h2 class="card-title">Map Coordinates</h2>
                        </div>
                        <div class="form-group-row">
                            <div class="form-group">
                                <label class="form-label">Latitude</label>
                                <input type="number" step="any" name="map_latitude" class="form-input" value="{{ $restaurant->map_latitude ?? '' }}" placeholder="e.g., 40.7128">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Longitude</label>
                                <input type="number" step="any" name="map_longitude" class="form-input" value="{{ $restaurant->map_longitude ?? '' }}" placeholder="e.g., -74.0060">
                            </div>
                        </div>
                    </div>

                    <div class="card" style="margin-bottom: 24px;">
                        <div class="card-header">
                            <h2 class="card-title">Social Media Links</h2>
                        </div>
                        <p style="margin-bottom: 12px; color: var(--muted); font-size: 13px;">Only links with values will appear as icons in the menu footer.</p>
                        <div class="form-group-row">
                            <div class="form-group">
                                <label class="form-label">WhatsApp Link</label>
                                <input type="url" name="whatsapp_link" class="form-input" value="{{ $restaurant->whatsapp_link ?? '' }}" placeholder="https://wa.me/...">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Instagram URL</label>
                                <input type="url" name="instagram_url" class="form-input" value="{{ $restaurant->instagram_url ?? '' }}" placeholder="https://instagram.com/...">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Facebook URL</label>
                                <input type="url" name="facebook_url" class="form-input" value="{{ $restaurant->facebook_url ?? '' }}" placeholder="https://facebook.com/...">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Twitter URL</label>
                                <input type="url" name="twitter_url" class="form-input" value="{{ $restaurant->twitter_url ?? '' }}" placeholder="https://twitter.com/...">
                            </div>
                        </div>
                    </div>

                    <div class="card" style="margin-bottom: 24px;">
                        <div class="card-header">
                            <h2 class="card-title">Footer Content</h2>
                        </div>
                        <p style="margin-bottom: 10px; color: var(--muted);">HTML content to display in footer</p>
                        <p style="margin-bottom: 10px; color: var(--muted); font-size: 13px;"><em>Note: This feature is reserved for future template updates.</em></p>
                        <textarea name="footer_content" class="json-editor" style="min-height: 150px;">{{ $restaurant->footer_content ?? '' }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Save Header & Footer
                    </button>
                </form>
            </div>
        </div>

        <!-- Menu Item Modal -->
        <div class="modal" id="menuItemModal" style="display: {{ $editMenuItem ? 'flex' : 'none' }};">
            <div class="modal-overlay" onclick="closeMenuItemModal()"></div>
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">{{ $editMenuItem ? 'Edit Menu Item' : 'Create New Menu Item' }}</h2>
                    <button class="modal-close" onclick="closeMenuItemModal()" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('admin.restaurants.hub', $restaurant) }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="action" value="{{ $editMenuItem ? 'update_menu_item' : 'create_menu_item' }}">
                        @if ($editMenuItem)
                            <input type="hidden" name="id" value="{{ $editMenuItem->id }}">
                        @endif

                        <div class="form-group">
                            <label class="form-label" for="category_id">Category *</label>
                            <select id="category_id" name="category_id" class="form-select" required>
                                <option value="">Select Category</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ ($editMenuItem && (int) $editMenuItem->category_id === (int) $cat->id) ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="name">Item Name *</label>
                            <input type="text" id="name" name="name" class="form-input" required value="{{ $editMenuItem->name ?? '' }}">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="slug">Slug *</label>
                            <input type="text" id="slug" name="slug" class="form-input" required value="{{ $editMenuItem->slug ?? '' }}">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="description">Description</label>
                            <textarea id="description" name="description" class="form-textarea" rows="3">{{ $editMenuItem->description ?? '' }}</textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="image">Item Image</label>
                            <input type="file" id="image" name="image" class="form-input" accept="image/*">
                            @if ($editMenuItem && $editMenuItem->image)
                                <div style="margin-top: 10px;">
                                    <p style="margin-bottom: 5px; color: var(--muted);">Current image:</p>
                                    <img src="{{ $uploadUrl . '/menu-items/' . $editMenuItem->image }}" alt="Current image" style="max-width: 200px; max-height: 200px; border-radius: 8px; border: 2px solid #e5e7eb;">
                                </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="price">Price *</label>
                            <input type="number" id="price" name="price" class="form-input" step="0.01" min="0" required value="{{ $editMenuItem->price ?? '' }}">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="display_order">Display Order</label>
                            <input type="number" id="display_order" name="display_order" class="form-input" value="{{ $editMenuItem->display_order ?? 0 }}">
                        </div>

                        <div class="form-group">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <input type="checkbox" id="is_available" name="is_available" style="width: 20px; height: 20px;" {{ (data_get($editMenuItem, 'is_available', 1)) ? 'checked' : '' }}>
                                <label class="form-label" for="is_available" style="margin: 0;">Available</label>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" onclick="closeMenuItemModal()">Cancel</button>
                            <button type="submit" class="btn btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                {{ $editMenuItem ? 'Update Menu Item' : 'Create Menu Item' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Menu Item Modal -->
        <div class="modal" id="deleteMenuItemModal" style="display: none;">
            <div class="modal-overlay" onclick="closeDeleteMenuItemModal()"></div>
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">Delete Menu Item</h2>
                    <button class="modal-close" onclick="closeDeleteMenuItemModal()" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                    <p style="margin-bottom: 20px; font-size: 16px;" id="deleteMenuItemText">Are you sure you want to delete this menu item?</p>
                    <p style="margin-bottom: 20px; color: var(--danger); font-weight: 600;">This action cannot be undone.</p>
                    <form method="POST" action="{{ route('admin.restaurants.hub', $restaurant) }}" id="deleteMenuItemForm">
                        @csrf
                        <input type="hidden" name="action" value="delete_menu_item">
                        <input type="hidden" name="id" id="deleteMenuItemId" value="">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" onclick="closeDeleteMenuItemModal()">Cancel</button>
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

        <!-- Category Modal -->
        <div class="modal" id="categoryModal" style="display: {{ $editCategory ? 'flex' : 'none' }};">
            <div class="modal-overlay" onclick="closeCategoryModal()"></div>
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">{{ $editCategory ? 'Edit Category' : 'Create New Category' }}</h2>
                    <button class="modal-close" onclick="closeCategoryModal()" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('admin.restaurants.hub', $restaurant) }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="action" value="{{ $editCategory ? 'update_category' : 'create_category' }}">
                        @if ($editCategory)
                            <input type="hidden" name="id" value="{{ $editCategory->id }}">
                        @endif

                        <div class="form-group">
                            <label class="form-label" for="cat_name">Category Name *</label>
                            <input type="text" id="cat_name" name="name" class="form-input" required value="{{ $editCategory->name ?? '' }}">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="cat_slug">Slug *</label>
                            <input type="text" id="cat_slug" name="slug" class="form-input" required value="{{ $editCategory->slug ?? '' }}">
                        </div>

                        @php
                        $primarySectionId = (int) ($editCategory->section_id ?? 0);
                        $selectedSecondary = is_array($editCategorySecondarySectionIds ?? null) ? $editCategorySecondarySectionIds : [];
                        @endphp

                        <div class="form-group">
                            <label class="form-label" for="cat_section_id">Primary Section *</label>
                            <select id="cat_section_id" name="section_id" class="form-input" required>
                                <option value="">-- Select section --</option>
                                @foreach ($sectionsList as $sec)
                                    @php $sid = (int) ($sec->id ?? 0); @endphp
                                    <option value="{{ $sid }}" {{ ($sid > 0 && $primarySectionId === $sid) ? 'selected' : '' }}>
                                        {{ $sec->name ?? '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Secondary Sections (optional)</label>
                            <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                                @foreach ($sectionsList as $sec)
                                    @php
                                        $sid = (int) ($sec->id ?? 0);
                                        $isSecondary = in_array($sid, $selectedSecondary, true);
                                        $isPrimary = ($sid === $primarySectionId && $primarySectionId > 0);
                                    @endphp
                                    <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--muted);{{ $isPrimary ? 'display:none;' : '' }}">
                                        <input type="checkbox"
                                               id="cat_secondary_{{ $sid }}"
                                               name="secondary_section_ids[]"
                                               value="{{ $sid }}"
                                               data-secondary="{{ $isSecondary ? '1' : '0' }}"
                                               {{ ($isSecondary && !$isPrimary) ? 'checked' : '' }}
                                               {{ $isPrimary ? 'disabled' : '' }}>
                                        {{ $sec->name ?? '' }}
                                    </label>
                                @endforeach
                            </div>
                            <p style="margin-top: 6px; font-size: 12px; color: var(--muted);">
                                Category will also appear on those section pages only (not on the main full menu page).
                            </p>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="cat_description">Description</label>
                            <textarea id="cat_description" name="description" class="form-textarea" rows="3">{{ $editCategory->description ?? '' }}</textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="cat_image">Category Image</label>
                            <input type="file" id="cat_image" name="image" class="form-input" accept="image/*">
                            @if ($editCategory && $editCategory->image)
                                <div style="margin-top: 10px;">
                                    <p style="margin-bottom: 5px; color: var(--muted);">Current image:</p>
                                    <img src="{{ $uploadUrl . '/categories/' . $editCategory->image }}" alt="Current image" style="max-width: 200px; max-height: 200px; border-radius: 8px; border: 2px solid #e5e7eb;">
                                </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="cat_display_order">Display Order</label>
                            <input type="number" id="cat_display_order" name="display_order" class="form-input" value="{{ $editCategory->display_order ?? 0 }}">
                        </div>

                        <div class="form-group">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <input type="checkbox" id="cat_is_active" name="is_active" style="width: 20px; height: 20px;" {{ data_get($editCategory, 'is_active', 1) ? 'checked' : '' }}>
                                <label class="form-label" for="cat_is_active" style="margin: 0;">Active</label>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" onclick="closeCategoryModal()">Cancel</button>
                            <button type="submit" class="btn btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                {{ $editCategory ? 'Update Category' : 'Create Category' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Category Modal -->
        <div class="modal" id="deleteCategoryModal" style="display: none;">
            <div class="modal-overlay" onclick="closeDeleteCategoryModal()"></div>
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">Delete Category</h2>
                    <button class="modal-close" onclick="closeDeleteCategoryModal()" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                    <p style="margin-bottom: 20px; font-size: 16px;" id="deleteCategoryText">Are you sure you want to delete this category?</p>
                    <p style="margin-bottom: 20px; color: var(--danger); font-weight: 600;">This action cannot be undone. This will delete:</p>
                    <ul style="margin-left: 20px; margin-bottom: 20px; color: var(--muted);">
                        <li>The category and all its information</li>
                        <li>All menu items in this category</li>
                        <li>The category image</li>
                    </ul>
                    <form method="POST" action="{{ route('admin.restaurants.hub', $restaurant) }}" id="deleteCategoryForm">
                        @csrf
                        <input type="hidden" name="action" value="delete_category">
                        <input type="hidden" name="id" id="deleteCategoryId" value="">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" onclick="closeDeleteCategoryModal()">Cancel</button>
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
@endsection

@push('scripts')
    <script>
        const hubUrl = @json(route('admin.restaurants.hub', $restaurant));

        function showTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            document.querySelectorAll('.tab').forEach(btn => {
                btn.classList.remove('active');
            });

            // Show selected tab
            document.getElementById('tab-' + tabName).classList.add('active');
            event.target.classList.add('active');

            // Update URL without reload
            const url = new URL(window.location);
            url.searchParams.set('tab', tabName);
            window.history.pushState({}, '', url);
        }

        // Menu items search and filter
        const menuSearch = document.getElementById('menuSearch');
        const categoryFilter = document.getElementById('categoryFilter');
        const menuItemsTableBody = document.getElementById('menuItemsTableBody');

        function filterMenuItems() {
            const searchTerm = menuSearch.value.toLowerCase();
            const categoryId = categoryFilter.value;
            const rows = menuItemsTableBody.querySelectorAll('tr');

            rows.forEach(row => {
                const itemName = row.getAttribute('data-item-name') || '';
                const rowCategoryId = row.getAttribute('data-category-id');

                const matchesSearch = !searchTerm || itemName.includes(searchTerm);
                const matchesCategory = categoryId === 'all' || rowCategoryId === categoryId;

                row.style.display = (matchesSearch && matchesCategory) ? '' : 'none';
            });
        }

        if (menuSearch) menuSearch.addEventListener('input', filterMenuItems);
        if (categoryFilter) categoryFilter.addEventListener('change', filterMenuItems);

        // Menu Item Modal Functions
        function openMenuItemModal(itemId = null) {
            if (itemId) {
                window.location.href = hubUrl + '?tab=menu&action=edit_menu_item&id=' + itemId;
            } else {
                document.getElementById('menuItemModal').style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }
        }

        function closeMenuItemModal() {
            document.getElementById('menuItemModal').style.display = 'none';
            document.body.style.overflow = '';
            // Redirect to clear edit mode
            if (window.location.search.includes('action=edit_menu_item')) {
                window.location.href = hubUrl + '?tab=menu';
            }
        }

        // Delete Menu Item Modal Functions
        function openDeleteMenuItemModal(menuItemId, menuItemName) {
            document.getElementById('deleteMenuItemId').value = menuItemId;
            const deleteText = document.getElementById('deleteMenuItemText');
            if (deleteText) {
                deleteText.innerHTML = 'Are you sure you want to delete <strong>"' + menuItemName + '"</strong>?';
            }
            document.getElementById('deleteMenuItemModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteMenuItemModal() {
            document.getElementById('deleteMenuItemModal').style.display = 'none';
            document.body.style.overflow = '';
        }

        // Auto-generate slug from name
        document.getElementById('name')?.addEventListener('input', function() {
            const slugInput = document.getElementById('slug');
            if (slugInput && !slugInput.value) {
                slugInput.value = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
            }
        });

        // Open modal if editing menu item
        @if ($editMenuItem)
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('menuItemModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        });
        @endif

        // Category Modal Functions
        function openCategoryModal(categoryId = null) {
            if (categoryId) {
                window.location.href = hubUrl + '?tab=categories&action=edit_category&id=' + categoryId;
            } else {
                document.getElementById('categoryModal').style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }
        }

        function closeCategoryModal() {
            document.getElementById('categoryModal').style.display = 'none';
            document.body.style.overflow = '';
            // Redirect to clear edit mode
            if (window.location.search.includes('action=edit_category')) {
                window.location.href = hubUrl + '?tab=categories';
            }
        }

        // Delete Category Modal Functions
        function openDeleteCategoryModal(categoryId, categoryName) {
            document.getElementById('deleteCategoryId').value = categoryId;
            const deleteText = document.getElementById('deleteCategoryText');
            if (deleteText) {
                deleteText.innerHTML = 'Are you sure you want to delete <strong>"' + categoryName + '"</strong>?';
            }
            document.getElementById('deleteCategoryModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteCategoryModal() {
            document.getElementById('deleteCategoryModal').style.display = 'none';
            document.body.style.overflow = '';
        }

        // Auto-generate slug from category name
        document.getElementById('cat_name')?.addEventListener('input', function() {
            const slugInput = document.getElementById('cat_slug');
            if (slugInput && !slugInput.value) {
                slugInput.value = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
            }
        });

        // Keep Secondary Sections UI in sync with the Primary Section dropdown (real-time).
        function updateAdminSecondarySectionsUI() {
            const categoryModal = document.getElementById('categoryModal');
            const primarySelect = document.getElementById('cat_section_id');
            if (!categoryModal || !primarySelect) return;

            const primaryId = parseInt(primarySelect.value || '0', 10) || 0;
            const checkboxes = categoryModal.querySelectorAll('input[name="secondary_section_ids[]"]');

            checkboxes.forEach(function(cb) {
                const sid = parseInt(cb.value || '0', 10) || 0;
                const shouldBeSecondary = (cb.dataset.secondary || '0') === '1';
                const label = cb.closest('label');

                if (primaryId && sid === primaryId) {
                    cb.checked = false;
                    cb.disabled = true;
                    if (label) label.style.display = 'none';
                } else {
                    cb.disabled = false;
                    cb.checked = shouldBeSecondary;
                    if (label) label.style.display = 'flex';
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            updateAdminSecondarySectionsUI();
            document.getElementById('cat_section_id')?.addEventListener('change', updateAdminSecondarySectionsUI);
        });

        // Open modal if editing category
        @if ($editCategory)
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('categoryModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        });
        @endif
    </script>
@endpush
