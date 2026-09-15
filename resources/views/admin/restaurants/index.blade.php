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
        <div class="modal" id="restaurantModal" style="display: {{ ($showCreateModal || ($editRestaurant && $editRestaurant->exists)) ? 'flex' : 'none' }};">
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
                    @php
                        $defaultPlanId = old('plan_id', optional($plans->firstWhere('slug', 'professional') ?? $plans->first())->id);
                        $includeTrialChecked = filter_var(old('include_trial', true), FILTER_VALIDATE_BOOLEAN);
                    @endphp
                    <div class="form-group">
                        <label class="form-label" for="plan_id">Subscription Plan *</label>
                        <select id="plan_id" name="plan_id" class="form-select" required>
                            @foreach($plans as $p)
                                <option value="{{ $p->id }}" @selected((string) $defaultPlanId === (string) $p->id)>{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <input type="hidden" name="include_trial" value="0">
                            <input type="checkbox" id="include_trial" name="include_trial" value="1" style="width: 20px; height: 20px;" @checked($includeTrialChecked)>
                            <label class="form-label" for="include_trial" style="margin: 0;">Include 7-day free trial</label>
                        </div>
                    </div>
                @endif 
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

                        <input type="hidden" name="manager_username" value="{{ old('manager_username', $editManager->username) }}">
                        <input type="hidden" name="manager_email" value="{{ old('manager_email', $editManager->email) }}">

                        @if ($errors->any())
                            <div style="margin-bottom: 16px; padding: 12px 14px; border-radius: 8px; background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; font-size: 0.875rem;">
                                <ul style="margin: 0; padding-left: 1.1rem;">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
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
        
        <!-- Suspend Confirmation Modal -->
        <div class="modal" id="suspendModal" style="display: none;">
            <div class="modal-overlay" onclick="closeSuspendModal()"></div>
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">Suspend Restaurant</h2>
                    <button class="modal-close" onclick="closeSuspendModal()" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                    <p style="margin-bottom: 20px; font-size: 16px;" id="suspendModalText">Suspend this restaurant?</p>
                    <p style="margin-bottom: 12px; color: var(--muted);">The public menu and manager login will be blocked. You can restore later from the Suspended tab. Permanent deletion is only available after suspension.</p>
                    <form method="POST" action="" id="suspendForm">
                        @csrf
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" onclick="closeSuspendModal()">Cancel</button>
                            <button type="submit" class="btn btn-danger">Yes, Suspend</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Permanent Delete Confirmation Modal -->
        <div class="modal" id="deleteModal" style="display: none;">
            <div class="modal-overlay" onclick="closeDeleteModal()"></div>
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">Permanently Delete Restaurant</h2>
                    <button class="modal-close" onclick="closeDeleteModal()" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                    <p style="margin-bottom: 20px; font-size: 16px;">Are you sure you want to permanently delete this restaurant?</p>
                    <p style="margin-bottom: 20px; color: var(--danger); font-weight: 600;">This action cannot be undone. This will delete:</p>
                    <ul style="margin-left: 20px; margin-bottom: 20px; color: var(--muted);">
                        <li>The restaurant and all its information</li>
                        <li>All categories and menu items</li>
                        <li>The manager account</li>
                        <li>Orders, reservations, payments, and related records</li>
                        <li>Uploaded images owned by this restaurant</li>
                    </ul>
                    <form method="POST" action="" id="deleteForm">
                        @csrf
                        @method('DELETE')
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">Cancel</button>
                            <button type="submit" class="btn btn-danger">Yes, Permanently Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header" style="flex-wrap: wrap; gap: 12px;">
                <div>
                    <h2 class="card-title">Restaurants</h2>
                    <div style="display:flex; gap:8px; margin-top:8px;">
                        <a href="{{ route('admin.restaurants.index', array_filter(['q' => $q ?: null, 'tab' => 'active'])) }}" class="btn {{ ($tab ?? 'active') === 'active' ? 'btn-primary' : 'btn-secondary' }}" style="padding:6px 12px;">Active</a>
                        <a href="{{ route('admin.restaurants.index', array_filter(['q' => $q ?: null, 'tab' => 'suspended'])) }}" class="btn {{ ($tab ?? '') === 'suspended' ? 'btn-primary' : 'btn-secondary' }}" style="padding:6px 12px;">Suspended</a>
                    </div>
                </div>
                @if(($tab ?? 'active') === 'active' && (!$editRestaurant || !$editRestaurant->exists))
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
                        <th>Manager</th>
                        @if(($tab ?? 'active') === 'suspended')
                            <th>Suspended</th>
                            <th>Reason</th>
                            <th>Last activity</th>
                            <th>Purge date</th>
                        @else
                            <th>Slug</th>
                            <th>Status</th>
                        @endif
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if($restaurants->isEmpty())
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 40px; color: var(--muted);">
                                No restaurants found.
                            </td>
                        </tr>
                    @else
                        @foreach($restaurants as $restaurant)
                            @php
                                $mgr = ($managersByRestaurant ?? collect())->get($restaurant->id);
                                $purgeAt = $restaurant->suspended_at
                                    ? \Carbon\Carbon::parse($restaurant->suspended_at)->addDays((int) ($purgeDays ?? 7))
                                    : null;
                                $daysLeft = $purgeAt ? max(0, now()->diffInDays($purgeAt, false)) : null;
                            @endphp
                            <tr>
                                <td>{{ $restaurant->id }}</td>
                                <td>{{ $restaurant->name }}</td>
                                <td>{{ $mgr->email ?? $mgr->username ?? '—' }}</td>
                                @if(($tab ?? 'active') === 'suspended')
                                    <td>{{ $restaurant->suspended_at?->format('Y-m-d H:i') ?? '—' }}</td>
                                    <td>{{ $restaurant->suspension_reason === 'inactivity' ? 'Inactivity' : 'Administrator' }}</td>
                                    <td>{{ $restaurant->last_activity_at?->format('Y-m-d H:i') ?? '—' }}</td>
                                    <td>{{ $purgeAt ? $purgeAt->format('Y-m-d').' ('.$daysLeft.'d left)' : '—' }}</td>
                                @else
                                    <td><code style="background: #f9fafb; padding: 4px 8px; border-radius: 4px; font-size: 12px;">{{ $restaurant->slug }}</code></td>
                                    <td><span class="status-badge {{ $restaurant->is_active ? 'active' : 'inactive' }}">{{ $restaurant->is_active ? 'Active' : 'Inactive' }}</span></td>
                                @endif
                                <td class="actions-cell">
                                    <button class="actions-btn" type="button" title="Actions">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                        </svg>
                                    </button>
                                    <div class="actions-dropdown">
                                        @if(($tab ?? 'active') === 'suspended')
                                            <form method="POST" action="{{ route('admin.restaurants.restore', $restaurant) }}" style="margin:0;">
                                                @csrf
                                                <button type="submit" class="actions-dropdown-item">Restore</button>
                                            </form>
                                            <div class="actions-dropdown-divider"></div>
                                            <button type="button" onclick="openDeleteModal({{ $restaurant->id }}, '{{ addslashes($restaurant->name) }}')" class="actions-dropdown-item danger">Permanent Delete</button>
                                        @else
                                            @if(($managersByRestaurant ?? collect())->get($restaurant->id))
                                            <form method="POST" action="{{ route('admin.restaurants.impersonate', $restaurant) }}" style="margin:0;">
                                                @csrf
                                                <button type="submit" class="actions-dropdown-item">Login as Manager</button>
                                            </form>
                                            @endif
                                            <a href="{{ route('admin.restaurants.index', ['edit' => $restaurant->id]) }}" class="actions-dropdown-item">Edit</a>
                                            <a href="{{ route('public.menu', $restaurant->slug) }}" target="_blank" class="actions-dropdown-item">View Menu</a>
                                            <div class="actions-dropdown-divider"></div>
                                            <button type="button" onclick="openSuspendModal({{ $restaurant->id }}, '{{ addslashes($restaurant->name) }}')" class="actions-dropdown-item danger">Suspend</button>
                                        @endif
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

        function openSuspendModal(restaurantId, restaurantName) {
            document.getElementById('suspendForm').action = @json(url('/admin/restaurants')) + '/' + restaurantId + '/suspend';
            var esc = document.createElement('div');
            esc.textContent = restaurantName;
            document.getElementById('suspendModalText').innerHTML = 'Suspend <strong>"' + esc.innerHTML + '"</strong>?';
            document.getElementById('suspendModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeSuspendModal() {
            document.getElementById('suspendModal').style.display = 'none';
            document.body.style.overflow = '';
        }
        
        function openDeleteModal(restaurantId, restaurantName) {
            document.getElementById('deleteForm').action = @json(url('/admin/restaurants')) + '/' + restaurantId;
            const modalBody = document.querySelector('#deleteModal .modal-body');
            const nameParagraph = modalBody.querySelector('p:first-child');
            if (nameParagraph) {
                var esc = document.createElement('div');
                esc.textContent = restaurantName;
                nameParagraph.innerHTML = 'Permanently delete <strong>"' + esc.innerHTML + '"</strong>? This cannot be undone.';
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
