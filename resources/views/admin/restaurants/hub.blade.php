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
  <table class="admin-table">
    <thead><tr><th>Name</th><th>Section</th><th>Items</th><th>Actions</th></tr></thead>
    <tbody>
      @forelse($categories as $category)
      <tr>
        <td>{{ $category->name }}</td>
        <td>{{ $category->section->name ?? '—' }}</td>
        <td>{{ $category->menu_items_count }}</td>
        <td>
          <form method="post" action="{{ route('admin.restaurants.hub', $restaurant) }}" style="display:inline" onsubmit="return confirm('Delete category?')">
            @csrf
            <input type="hidden" name="action" value="delete_category">
            <input type="hidden" name="id" value="{{ $category->id }}">
            <button type="submit" class="btn-danger-sm">Delete</button>
          </form>
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
  <table class="admin-table">
    <thead><tr><th>Name</th><th>Category</th><th>Price</th><th>Actions</th></tr></thead>
    <tbody>
      @forelse($menuItems as $item)
      <tr>
        <td>{{ $item->name }}</td>
        <td>{{ $item->category->name ?? '—' }}</td>
        <td>₦{{ number_format($item->price, 2) }}</td>
        <td>
          <form method="post" action="{{ route('admin.restaurants.hub', $restaurant) }}" style="display:inline" onsubmit="return confirm('Delete item?')">
            @csrf
            <input type="hidden" name="action" value="delete_menu_item">
            <input type="hidden" name="id" value="{{ $item->id }}">
            <button type="submit" class="btn-danger-sm">Delete</button>
          </form>
        </td>
      </tr>
      @empty
      <tr><td colspan="4">No menu items yet.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
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
@push('head')<link rel="stylesheet" href="{{ asset('assets/css/admin-dashboard.css') }}">@endpush
