@php
  $isEdit = $editRestaurant && $editRestaurant->exists;
  $modalRestaurant = $editRestaurant ?? new \App\Models\Restaurant(['is_active' => true, 'template_id' => 4]);
@endphp

<div class="modal" id="restaurantModal" style="display: {{ ($showCreateModal || $isEdit) ? 'flex' : 'none' }};">
  <div class="modal-overlay" onclick="closeRestaurantModal()"></div>
  <div class="modal-content">
    <div class="modal-header">
      <h2 class="modal-title">{{ $isEdit ? 'Edit Restaurant' : 'Create New Restaurant' }}</h2>
      <button class="modal-close" type="button" onclick="closeRestaurantModal()" aria-label="Close">&times;</button>
    </div>
    <div class="modal-body">
      <form method="post" action="{{ $isEdit ? route('admin.restaurants.update', $editRestaurant) : route('admin.restaurants.store') }}" enctype="multipart/form-data">
        @csrf
        @if($isEdit) @method('PUT') @endif
        <div class="filter-group"><label>Restaurant Name *</label><input name="name" class="form-input" required value="{{ old('name', $modalRestaurant->name ?? '') }}" style="width:100%"></div>
        <div class="filter-group" style="margin-top:12px"><label>Slug *</label><input name="slug" class="form-input" required value="{{ old('slug', $modalRestaurant->slug ?? '') }}" style="width:100%"></div>
        <div class="filter-group" style="margin-top:12px"><label>Description</label><textarea name="description" rows="3" class="form-input" style="width:100%">{{ old('description', $modalRestaurant->description ?? '') }}</textarea></div>
        <div class="filter-group" style="margin-top:12px"><label>Phone</label><input name="phone" class="form-input" value="{{ old('phone', $modalRestaurant->phone ?? '') }}" style="width:100%"></div>
        <div class="filter-group" style="margin-top:12px"><label>Address</label><textarea name="address" rows="2" class="form-input" style="width:100%">{{ old('address', $modalRestaurant->address ?? '') }}</textarea></div>
        <div class="filter-group" style="margin-top:12px"><label>Template ID</label><input type="number" name="template_id" min="1" class="form-input" value="{{ old('template_id', $modalRestaurant->template_id ?? 4) }}" style="width:100%"></div>
        @if(!$isEdit)
          <div class="filter-group" style="margin-top:12px"><label>Plan</label>
            <select name="plan_id" class="form-input" style="width:100%"><option value="">—</option>@foreach($plans as $p)<option value="{{ $p->id }}">{{ $p->name }}</option>@endforeach</select>
          </div>
        @endif
        <label style="display:flex;align-items:center;gap:8px;margin-top:12px"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $editRestaurant->is_active ?? true))> Active</label>
        <div class="filter-group" style="margin-top:12px"><label>Logo</label><input type="file" name="logo" accept="image/*" class="form-input"></div>
        <div class="filter-group" style="margin-top:12px"><label>Hero image</label><input type="file" name="hero_image" accept="image/*" class="form-input"></div>
        <hr style="margin:24px 0;border:none;border-top:1px solid #e5e7eb">
        <h3 style="margin:0 0 16px;font-weight:600">Manager Account</h3>
        <div class="filter-group"><label>Username *</label><input name="manager_username" class="form-input" required value="{{ old('manager_username', $editManager->username ?? '') }}" style="width:100%"></div>
        <div class="filter-group" style="margin-top:12px"><label>Email *</label><input type="email" name="manager_email" required class="form-input" value="{{ old('manager_email', $editManager->email ?? $modalRestaurant->email ?? '') }}" style="width:100%"></div>
        <div class="filter-group" style="margin-top:12px"><label>Password{{ $isEdit ? ' (leave blank to keep)' : ' *' }}</label><input type="password" name="manager_password" {{ $isEdit ? '' : 'required' }} class="form-input" style="width:100%"></div>
        <div class="modal-footer" style="margin-top:20px;display:flex;gap:8px;justify-content:flex-end">
          <button type="button" class="btn-clear" onclick="closeRestaurantModal()">Cancel</button>
          <button type="submit" class="btn-filter">{{ $isEdit ? 'Update Restaurant' : 'Create Restaurant' }}</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal" id="deleteModal" style="display:none;">
  <div class="modal-overlay" onclick="closeDeleteModal()"></div>
  <div class="modal-content">
    <div class="modal-header">
      <h2 class="modal-title">Delete Restaurant</h2>
      <button class="modal-close" type="button" onclick="closeDeleteModal()" aria-label="Close">&times;</button>
    </div>
    <div class="modal-body">
      <p style="margin-bottom:16px">Are you sure you want to delete <strong id="delete-restaurant-name"></strong>?</p>
      <p style="color:#991b1b;font-weight:600;margin-bottom:12px">This action cannot be undone.</p>
      <form method="post" id="deleteForm">
        @csrf @method('DELETE')
        <div class="modal-footer" style="display:flex;gap:8px;justify-content:flex-end">
          <button type="button" class="btn-clear" onclick="closeDeleteModal()">Cancel</button>
          <button type="submit" class="btn-filter" style="background:#991b1b">Delete Restaurant</button>
        </div>
      </form>
    </div>
  </div>
</div>
