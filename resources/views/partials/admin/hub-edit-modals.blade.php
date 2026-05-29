<div class="modal" id="categoryModal" style="display:none;">
  <div class="modal-overlay" onclick="closeCategoryModal()"></div>
  <div class="modal-content">
    <div class="modal-header">
      <h2 class="modal-title">Edit category</h2>
      <button class="modal-close" type="button" onclick="closeCategoryModal()" aria-label="Close">&times;</button>
    </div>
    <div class="modal-body">
      <form method="post" action="{{ route('admin.restaurants.hub', $restaurant) }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="action" id="category-form-action" value="update_category">
        <input type="hidden" name="id" id="category-id">
        <div class="filter-group"><label>Name</label><input name="name" id="category-name" required class="form-input" style="width:100%"></div>
        <div class="filter-group" style="margin-top:12px"><label>Section</label>
          <select name="section_id" id="category-section" required class="form-input" style="width:100%">
            @foreach($sections as $section)
              <option value="{{ $section->id }}">{{ $section->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="filter-group" style="margin-top:12px"><label>Display order</label><input type="number" name="display_order" id="category-order" class="form-input" style="width:100%"></div>
        <div class="filter-group" style="margin-top:12px"><label>Description</label><textarea name="description" id="category-description" rows="2" class="form-input" style="width:100%"></textarea></div>
        <div class="filter-group" style="margin-top:12px"><label>Replace image</label><input type="file" name="image" accept="image/*" class="form-input"></div>
        <div class="modal-footer" style="margin-top:20px;display:flex;gap:8px;justify-content:flex-end">
          <button type="button" class="btn-clear" onclick="closeCategoryModal()">Cancel</button>
          <button type="submit" class="btn-filter">Save category</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal" id="menuItemModal" style="display:none;">
  <div class="modal-overlay" onclick="closeMenuItemModal()"></div>
  <div class="modal-content">
    <div class="modal-header">
      <h2 class="modal-title">Edit menu item</h2>
      <button class="modal-close" type="button" onclick="closeMenuItemModal()" aria-label="Close">&times;</button>
    </div>
    <div class="modal-body">
      <form method="post" action="{{ route('admin.restaurants.hub', $restaurant) }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="action" id="menu-item-form-action" value="update_menu_item">
        <input type="hidden" name="id" id="menu-item-id">
        <div class="filter-group"><label>Name</label><input name="name" id="menu-item-name" required class="form-input" style="width:100%"></div>
        <div class="filter-group" style="margin-top:12px"><label>Category</label>
          <select name="category_id" id="menu-item-category" required class="form-input" style="width:100%">
            @foreach($categories as $category)
              <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="filter-group" style="margin-top:12px"><label>Price</label><input type="number" step="0.01" min="0" name="price" id="menu-item-price" required class="form-input" style="width:100%"></div>
        <div class="filter-group" style="margin-top:12px"><label>Display order</label><input type="number" name="display_order" id="menu-item-order" class="form-input" style="width:100%"></div>
        <div class="filter-group" style="margin-top:12px"><label>Description</label><textarea name="description" id="menu-item-description" rows="2" class="form-input" style="width:100%"></textarea></div>
        <div class="filter-group" style="margin-top:12px"><label>Replace image</label><input type="file" name="image" accept="image/*" class="form-input"></div>
        <div class="modal-footer" style="margin-top:20px;display:flex;gap:8px;justify-content:flex-end">
          <button type="button" class="btn-clear" onclick="closeMenuItemModal()">Cancel</button>
          <button type="submit" class="btn-filter">Save menu item</button>
        </div>
      </form>
    </div>
  </div>
</div>
