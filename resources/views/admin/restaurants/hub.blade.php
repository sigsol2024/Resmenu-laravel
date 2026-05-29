@extends('layouts.admin')
@section('title', 'Manage '.$restaurant->name)
@section('content')
@include('partials.admin.page-header', ['title' => 'Manage: '.$restaurant->name, 'subtitle' => $restaurant->slug])

@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@if($errors->any())<div class="alert alert-error">@foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach</div>@endif

<nav style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:20px;">
  @foreach(['menu'=>'Menu','customization'=>'Customization','header'=>'Header & Footer'] as $key => $label)
    <a href="{{ route('admin.restaurants.hub', [$restaurant, 'tab' => $key]) }}"
       class="btn-view" style="{{ $activeTab === $key ? 'background:#111827;color:#fff;' : '' }}">{{ $label }}</a>
  @endforeach
  <a href="{{ route('admin.restaurants.show', $restaurant) }}" class="btn-view">Overview</a>
  <a href="{{ route('public.menu', $restaurant->slug) }}" target="_blank" class="btn-view">View menu</a>
</nav>

@if($activeTab === 'menu')
<div class="card" style="margin-bottom:24px;">
  <h3 style="margin:0 0 16px;">Categories</h3>
  <form method="post" action="{{ route('admin.restaurants.hub', $restaurant) }}" enctype="multipart/form-data" style="display:grid;gap:12px;margin-bottom:20px;padding:16px;background:#f9fafb;border-radius:8px;">
    @csrf
    <input type="hidden" name="action" value="create_category">
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px;">
      <input name="name" placeholder="Category name" required class="form-input">
      <select name="section_id" required class="form-input">
        @foreach($sections as $section)
          <option value="{{ $section->id }}">{{ $section->name }}</option>
        @endforeach
      </select>
      <input name="display_order" type="number" value="0" class="form-input" placeholder="Order">
      <input name="image" type="file" accept="image/*" class="form-input">
    </div>
    <textarea name="description" placeholder="Description" rows="2" class="form-input"></textarea>
    <button type="submit" class="btn-manage">Add category</button>
  </form>
  <table class="data-table">
    <thead><tr><th>Name</th><th>Section</th><th>Items</th><th>Actions</th></tr></thead>
    <tbody>
      @forelse($categories as $category)
      <tr>
        <td>{{ $category->name }}</td>
        <td>{{ $category->section->name ?? '—' }}</td>
        <td>{{ $category->menu_items_count }}</td>
        <td>
          @include('partials.admin.actions-dropdown', [
            'items' => [
              ['type' => 'button', 'label' => 'Edit', 'onclick' => 'openCategoryModal('.e(json_encode(['id' => $category->id, 'name' => $category->name, 'section_id' => $category->section_id, 'description' => $category->description, 'display_order' => $category->display_order])).')'],
              ['type' => 'divider'],
              [
                'type' => 'form',
                'label' => 'Delete',
                'action' => route('admin.restaurants.hub', $restaurant),
                'class' => 'danger',
                'confirm' => 'Delete category and its items?',
                'hidden' => ['action' => 'delete_category', 'id' => $category->id],
              ],
            ],
          ])
        </td>
      </tr>
      @empty
      <tr><td colspan="4">No categories yet.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

<div class="card">
  <h3 style="margin:0 0 16px;">Menu items</h3>
  <form method="post" action="{{ route('admin.restaurants.hub', $restaurant) }}" enctype="multipart/form-data" style="display:grid;gap:12px;margin-bottom:20px;padding:16px;background:#f9fafb;border-radius:8px;">
    @csrf
    <input type="hidden" name="action" value="create_menu_item">
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:12px;">
      <input name="name" placeholder="Item name" required class="form-input">
      <select name="category_id" required class="form-input">
        @foreach($categories as $category)
          <option value="{{ $category->id }}">{{ $category->name }}</option>
        @endforeach
      </select>
      <input name="price" type="number" step="0.01" min="0" required class="form-input" placeholder="Price">
      <input name="display_order" type="number" value="0" class="form-input">
      <input name="image" type="file" accept="image/*" class="form-input">
    </div>
    <textarea name="description" placeholder="Description" rows="2" class="form-input"></textarea>
    <button type="submit" class="btn-manage">Add menu item</button>
  </form>
  <table class="data-table">
    <thead><tr><th>Name</th><th>Category</th><th>Price</th><th>Actions</th></tr></thead>
    <tbody>
      @forelse($menuItems as $item)
      <tr>
        <td>{{ $item->name }}</td>
        <td>{{ $item->category->name ?? '—' }}</td>
        <td>₦{{ number_format($item->price, 2) }}</td>
        <td>
          @include('partials.admin.actions-dropdown', [
            'items' => [
              ['type' => 'button', 'label' => 'Edit', 'onclick' => 'openMenuItemModal('.e(json_encode(['id' => $item->id, 'name' => $item->name, 'category_id' => $item->category_id, 'description' => $item->description, 'price' => $item->price, 'display_order' => $item->display_order])).')'],
              ['type' => 'divider'],
              [
                'type' => 'form',
                'label' => 'Delete',
                'action' => route('admin.restaurants.hub', $restaurant),
                'class' => 'danger',
                'confirm' => 'Delete this menu item?',
                'hidden' => ['action' => 'delete_menu_item', 'id' => $item->id],
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
</div>

@include('partials.admin.hub-edit-modals', ['restaurant' => $restaurant, 'sections' => $sections, 'categories' => $categories])
@endif

@if($activeTab === 'customization')
<div class="card" style="margin-bottom:24px;">
  <h3>Menu template</h3>
  <form method="post" action="{{ route('admin.restaurants.hub', $restaurant) }}">
    @csrf
    <input type="hidden" name="action" value="save_template">
    <select name="template_id" class="form-input" style="max-width:320px;">
      @foreach($menuTemplates as $tpl)
        <option value="{{ $tpl->id }}" @selected($restaurant->template_id == $tpl->id)>{{ $tpl->name ?? 'Template '.$tpl->id }}</option>
      @endforeach
    </select>
    <button type="submit" class="btn-manage" style="margin-top:12px;">Save template</button>
  </form>
</div>
<div class="card">
  <h3>Colors & typography</h3>
  <form method="post" action="{{ route('admin.restaurants.hub', $restaurant) }}" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:12px;">
    @csrf
    <input type="hidden" name="action" value="save_customization">
    @foreach(['primary_color','secondary_color','background_color','header_background_color','menu_title_color','price_color','description_color','category_title_color'] as $field)
      <label>{{ str_replace('_',' ', $field) }}
        <input type="color" name="{{ $field }}" value="{{ $customization[$field] ?? '#111111' }}" class="form-input">
      </label>
    @endforeach
    <div style="grid-column:1/-1;"><button type="submit" class="btn-manage">Save customization</button></div>
  </form>
</div>
@endif

@if($activeTab === 'header')
<div class="card">
  <form method="post" action="{{ route('admin.restaurants.hub', $restaurant) }}">
    @csrf
    <input type="hidden" name="action" value="save_header_footer">
    <label class="block mb-2">Footer content</label>
    <textarea name="footer_content" rows="4" class="form-input w-full">{{ $restaurant->footer_content }}</textarea>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:12px;margin-top:12px;">
      <input name="instagram_url" value="{{ $restaurant->instagram_url }}" placeholder="Instagram URL" class="form-input">
      <input name="facebook_url" value="{{ $restaurant->facebook_url }}" placeholder="Facebook URL" class="form-input">
      <input name="twitter_url" value="{{ $restaurant->twitter_url }}" placeholder="Twitter URL" class="form-input">
      <input name="whatsapp_link" value="{{ $restaurant->whatsapp_link }}" placeholder="WhatsApp link" class="form-input">
    </div>
    <button type="submit" class="btn-manage" style="margin-top:16px;">Save header & footer</button>
  </form>
</div>
@endif

<p style="margin-top:24px"><a href="{{ route('admin.restaurants.index') }}">← All restaurants</a></p>
@endsection
@push('head')
<link rel="stylesheet" href="{{ asset('assets/css/admin-dashboard.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/admin-payments.css') }}">
@endpush
@push('scripts')
<script>
function openCategoryModal(data) {
  document.getElementById('categoryModal').style.display = 'flex';
  document.getElementById('category-form-action').value = 'update_category';
  document.getElementById('category-id').value = data.id;
  document.getElementById('category-name').value = data.name || '';
  document.getElementById('category-section').value = data.section_id || '';
  document.getElementById('category-description').value = data.description || '';
  document.getElementById('category-order').value = data.display_order || 0;
}
function closeCategoryModal() { document.getElementById('categoryModal').style.display = 'none'; }
function openMenuItemModal(data) {
  document.getElementById('menuItemModal').style.display = 'flex';
  document.getElementById('menu-item-form-action').value = 'update_menu_item';
  document.getElementById('menu-item-id').value = data.id;
  document.getElementById('menu-item-name').value = data.name || '';
  document.getElementById('menu-item-category').value = data.category_id || '';
  document.getElementById('menu-item-description').value = data.description || '';
  document.getElementById('menu-item-price').value = data.price || 0;
  document.getElementById('menu-item-order').value = data.display_order || 0;
}
function closeMenuItemModal() { document.getElementById('menuItemModal').style.display = 'none'; }
</script>
@endpush
