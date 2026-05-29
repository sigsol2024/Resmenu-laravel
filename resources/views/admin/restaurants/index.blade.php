@extends('layouts.admin')
@section('title', 'Restaurant Management')
@push('head')
<link rel="stylesheet" href="{{ asset('legacy/css/pages/admin-restaurants.css') }}">
@endpush
@section('content')
@php
    $uploadUrl = rtrim(config('resmenu.upload_url'), '/');
    $message = session('success');
    $error = session('error') ?? ($errors->any() ? $errors->first() : null);
@endphp

@if($message)
    <div class="alert alert-success">
        {{ $message }}
    </div>
@endif

@if($error)
    <div class="alert alert-error">
        {{ $error }}
    </div>
@endif

<!-- Page Header -->
<div class="page-header">
    <h1 class="page-title">Restaurant Management</h1>
    <p class="page-subtitle">Create and manage restaurants on the platform</p>
</div>

<!-- Create/Edit Restaurant Modal -->
        <div class="modal" id="restaurantModal" style="display: {{ $editRestaurant && $editRestaurant->exists ? 'flex' : 'none' }};">
            <div class="modal-overlay" onclick="closeRestaurantModal()"></div>
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">
                        {{ $editRestaurant && $editRestaurant->exists ? 'Edit Restaurant' : 'Create New Restaurant' }}
                    </h2>
                    <button class="modal-close" onclick="closeRestaurantModal()" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ $editRestaurant && $editRestaurant->exists ? route('admin.restaurants.update', $editRestaurant) : route('admin.restaurants.store') }}" enctype="multipart/form-data">
                @csrf
                @if($editRestaurant && $editRestaurant->exists)
                    @method('PUT')
                @endif
                
                <div class="form-group">
                    <label class="form-label" for="name">Restaurant Name *</label>
                    <input type="text" id="name" name="name" class="form-input" required value="{{ old('name', $editRestaurant->name ?? '') }}">
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="slug">Slug * (URL-friendly name)</label>
                    <input type="text" id="slug" name="slug" class="form-input" required value="{{ old('slug', $editRestaurant->slug ?? '') }}">
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="description">Description</label>
                    <textarea id="description" name="description" class="form-textarea" rows="3">{{ old('description', $editRestaurant->description ?? '') }}</textarea>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="phone">Phone</label>
                    <input type="tel" id="phone" name="phone" class="form-input" value="{{ old('phone', $editRestaurant->phone ?? '') }}">
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="address">Address</label>
                    <textarea id="address" name="address" class="form-textarea" rows="2">{{ old('address', $editRestaurant->address ?? '') }}</textarea>
                </div>
                
                <div class="form-group-row">
                    <div class="form-group">
                        <label class="form-label" for="whatsapp_link">WhatsApp Link</label>
                        <input type="url" id="whatsapp_link" name="whatsapp_link" class="form-input" value="{{ old('whatsapp_link', $editRestaurant->whatsapp_link ?? '') }}" placeholder="https://wa.me/...">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="instagram_url">Instagram URL</label>
                        <input type="url" id="instagram_url" name="instagram_url" class="form-input" value="{{ old('instagram_url', $editRestaurant->instagram_url ?? '') }}" placeholder="https://instagram.com/...">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="facebook_url">Facebook URL</label>
                        <input type="url" id="facebook_url" name="facebook_url" class="form-input" value="{{ old('facebook_url', $editRestaurant->facebook_url ?? '') }}" placeholder="https://facebook.com/...">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="twitter_url">Twitter URL</label>
                        <input type="url" id="twitter_url" name="twitter_url" class="form-input" value="{{ old('twitter_url', $editRestaurant->twitter_url ?? '') }}" placeholder="https://twitter.com/...">
                    </div>
                </div>
                
                <div class="form-group-row">
                    <div class="form-group">
                        <label class="form-label" for="rating_source">Rating Source</label>
                        <select id="rating_source" name="rating_source" class="form-select">
                            <option value="Google" {{ old('rating_source', $editRestaurant->rating_source ?? 'Google') === 'Google' ? 'selected' : '' }}>Google</option>
                            <option value="Yelp" {{ old('rating_source', $editRestaurant->rating_source ?? 'Google') === 'Yelp' ? 'selected' : '' }}>Yelp</option>
                            <option value="TripAdvisor" {{ old('rating_source', $editRestaurant->rating_source ?? 'Google') === 'TripAdvisor' ? 'selected' : '' }}>TripAdvisor</option>
                            <option value="Facebook" {{ old('rating_source', $editRestaurant->rating_source ?? 'Google') === 'Facebook' ? 'selected' : '' }}>Facebook</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="google_rating">Rating (0-5)</label>
                        <input type="number" id="google_rating" name="google_rating" class="form-input" step="0.1" min="0" max="5" value="{{ old('google_rating', $editRestaurant->google_rating ?? '4.5') }}">
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="logo">Logo</label>
                    <input type="file" id="logo" name="logo" class="form-input" accept="image/*">
                    @if($editRestaurant && $editRestaurant->exists && $editRestaurant->logo)
                        <div style="margin-top: 10px;">
                            <p style="margin-bottom: 5px; color: var(--muted);">Current logo:</p>
                            <img src="{{ $uploadUrl }}/logos/{{ $editRestaurant->logo }}" alt="Current logo" style="max-width: 200px; max-height: 200px; border-radius: 8px; border: 2px solid #e5e7eb;">
                        </div>
                    @endif
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="hero_image">Hero Image</label>
                    <input type="file" id="hero_image" name="hero_image" class="form-input" accept="image/*">
                    <small style="color: var(--muted); display: block; margin-top: 5px;">Large image displayed on the right side of the hero section</small>
                    @if($editRestaurant && $editRestaurant->exists && $editRestaurant->hero_image)
                        <div style="margin-top: 10px;">
                            <p style="margin-bottom: 5px; color: var(--muted);">Current hero image:</p>
                            <img src="{{ $uploadUrl }}/heroes/{{ $editRestaurant->hero_image }}" alt="Current hero image" style="max-width: 300px; max-height: 200px; border-radius: 8px; border: 2px solid #e5e7eb;">
                        </div>
                    @endif
                </div>
                
                <div class="form-group">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <input type="checkbox" id="is_active" name="is_active" value="1" style="width: 20px; height: 20px;" {{ old('is_active', $editRestaurant->is_active ?? 1) ? 'checked' : '' }}>
                        <label class="form-label" for="is_active" style="margin: 0;">Active</label>
                    </div>
                </div>
                
                @if(!$editRestaurant || !$editRestaurant->exists)
                    <hr style="margin: 30px 0; border: none; border-top: 2px solid #e5e7eb;">
                    <h3 style="margin-bottom: 20px; font-weight: 600;">Manager Account</h3>
                    
                    <div class="form-group">
                        <label class="form-label" for="manager_email">Manager Email *</label>
                        <input type="email" id="manager_email" name="manager_email" class="form-input" required placeholder="manager@restaurant.com">
                        <small style="color: var(--muted); display: block; margin-top: 5px;">This email will be used for the manager login</small>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="manager_username">Manager Username *</label>
                        <input type="text" id="manager_username" name="manager_username" class="form-input" required placeholder="manager" autocomplete="off">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="manager_password">Manager Password *</label>
                        <input type="password" id="manager_password" name="manager_password" class="form-input" required minlength="8" placeholder="Enter password" autocomplete="new-password">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="manager_password_confirm">Confirm Manager Password *</label>
                        <input type="password" id="manager_password_confirm" name="manager_password_confirm" class="form-input" required minlength="8" placeholder="Confirm password" autocomplete="new-password">
                    </div>
                @else
                    @if($editManager)
                        <hr style="margin: 30px 0; border: none; border-top: 2px solid #e5e7eb;">
                        <h3 style="margin-bottom: 20px; font-weight: 600;">Manager Account</h3>
                        
                        <div class="form-group">
                            <label class="form-label">Manager Email</label>
                            <input type="text" class="form-input" value="{{ $editManager->email }}" readonly style="background-color: #f9fafb;">
                            <small style="color: var(--muted); display: block; margin-top: 5px;">Manager email cannot be changed. Use this email to login.</small>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label" for="manager_password">Update Manager Password</label>
                            <input type="password" id="manager_password" name="manager_password" class="form-input" minlength="8" placeholder="Leave blank to keep current password" autocomplete="new-password">
                            <small style="color: var(--muted); display: block; margin-top: 5px;">Only fill this if you want to change the manager's password</small>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label" for="manager_password_confirm">Confirm New Password</label>
                            <input type="password" id="manager_password_confirm" name="manager_password_confirm" class="form-input" minlength="8" placeholder="Confirm new password" autocomplete="new-password">
                        </div>
                    @endif
                @endif
                
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" onclick="closeRestaurantModal()">Cancel</button>
                            <button type="submit" class="btn btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                {{ $editRestaurant && $editRestaurant->exists ? 'Update Restaurant' : 'Create Restaurant' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Delete Confirmation Modal -->
        <div class="modal" id="deleteModal" style="display: none;">
            <div class="modal-overlay" onclick="closeDeleteModal()"></div>
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">
                        Delete Restaurant
                    </h2>
                    <button class="modal-close" onclick="closeDeleteModal()" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                    <p style="margin-bottom: 20px; font-size: 16px;">Are you sure you want to delete this restaurant?</p>
                    <p style="margin-bottom: 20px; color: var(--danger); font-weight: 600;">This action cannot be undone. This will delete:</p>
                    <ul style="margin-left: 20px; margin-bottom: 20px; color: var(--muted);">
                        <li>The restaurant and all its information</li>
                        <li>All categories</li>
                        <li>All menu items</li>
                        <li>The manager account</li>
                        <li>All uploaded images (logo, hero image, category images, menu item images)</li>
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
                                Yes, Delete Restaurant
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">All Restaurants</h2>
                @if(!$editRestaurant || !$editRestaurant->exists)
                    <button class="btn btn-primary" onclick="openRestaurantModal()">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        New Restaurant
                    </button>
                @endif
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if($restaurants->isEmpty())
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 40px; color: var(--muted);">
                                No restaurants found.
                            </td>
                        </tr>
                    @else
                        @foreach($restaurants as $restaurant)
                            <tr>
                                <td>{{ $restaurant->id }}</td>
                                <td>{{ $restaurant->name }}</td>
                                <td><code style="background: #f9fafb; padding: 4px 8px; border-radius: 4px; font-size: 12px;">{{ $restaurant->slug }}</code></td>
                                <td><span class="status-badge {{ $restaurant->is_active ? 'active' : 'inactive' }}">{{ $restaurant->is_active ? 'Active' : 'Inactive' }}</span></td>
                                <td class="actions-cell">
                                    <button class="actions-btn" type="button" title="Actions">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                        </svg>
                                    </button>
                                    <div class="actions-dropdown">
                                        <a href="{{ route('admin.restaurants.hub', $restaurant) }}" class="actions-dropdown-item">Manage</a>
                                        <a href="{{ route('admin.restaurants.index', ['edit' => $restaurant->id]) }}" class="actions-dropdown-item">Edit</a>
                                        <a href="{{ route('public.menu', $restaurant->slug) }}" target="_blank" class="actions-dropdown-item">View Menu</a>
                                        <div class="actions-dropdown-divider"></div>
                                        <button type="button" onclick="openDeleteModal({{ $restaurant->id }}, '{{ addslashes($restaurant->name) }}')" class="actions-dropdown-item danger">Delete</button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
            @if($restaurants->hasPages())
                {{ $restaurants->links() }}
            @endif
        </div>
    
    <script>
        document.getElementById('name')?.addEventListener('input', function() {
            const slugInput = document.getElementById('slug');
            if (slugInput && !slugInput.value) {
                slugInput.value = this.value.toLowerCase()
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/^-+|-+$/g, '');
            }
        });
        
        function openRestaurantModal() {
            document.getElementById('restaurantModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
        
        function closeRestaurantModal() {
            document.getElementById('restaurantModal').style.display = 'none';
            document.body.style.overflow = '';
            if (window.location.search.includes('edit=')) {
                window.location.href = @json(route('admin.restaurants.index'));
            }
        }
        
        function openDeleteModal(restaurantId, restaurantName) {
            document.getElementById('deleteForm').action = @json(url('/admin/restaurants')) + '/' + restaurantId;
            const modalBody = document.querySelector('#deleteModal .modal-body');
            const nameParagraph = modalBody.querySelector('p:first-child');
            if (nameParagraph) {
                var esc = document.createElement('div');
                esc.textContent = restaurantName;
                nameParagraph.innerHTML = 'Are you sure you want to delete <strong>"' + esc.innerHTML + '"</strong>?';
            }
            document.getElementById('deleteModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
        
        function closeDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
            document.body.style.overflow = '';
        }
        
        @if($editRestaurant && $editRestaurant->exists)
        document.addEventListener('DOMContentLoaded', function() {
            openRestaurantModal();
        });
        @endif

        @if($showCreateModal ?? false)
        document.addEventListener('DOMContentLoaded', function() {
            openRestaurantModal();
        });
        @endif
    </script>
@endsection
