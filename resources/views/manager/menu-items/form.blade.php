@extends('layouts.manager')
@section('title', $item->exists ? 'Edit item' : 'New item')
@section('content')
<h1 style="margin-bottom:20px;">{{ $item->exists ? 'Edit menu item' : 'New menu item' }}</h1>
<div class="card">
<form method="post" action="{{ $item->exists ? route('manager.menu-items.update', $item) : route('manager.menu-items.store') }}" enctype="multipart/form-data">
    @csrf
    @if($item->exists) @method('PUT') @endif
    <div class="form-group"><label for="name">Name</label><input type="text" name="name" id="name" value="{{ old('name', $item->name) }}" required></div>
    <div class="form-group">
        <label for="category_id">Category</label>
        <select name="category_id" id="category_id" required>
            @foreach($categories as $c)
                <option value="{{ $c->id }}" @selected(old('category_id', $item->category_id) == $c->id)>{{ $c->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group"><label for="description">Description</label><textarea name="description" id="description" rows="3">{{ old('description', $item->description) }}</textarea></div>
    <div class="form-group"><label for="price">Price</label><input type="number" step="0.01" name="price" id="price" value="{{ old('price', $item->price) }}" required></div>
    <div class="form-group"><label for="display_order">Display order</label><input type="number" name="display_order" id="display_order" value="{{ old('display_order', $item->display_order) }}"></div>
    <div class="form-group"><label><input type="checkbox" name="is_available" value="1" @checked(old('is_available', $item->is_available))> Available</label></div>
    <div class="form-group"><label for="image">Image</label><input type="file" name="image" id="image" accept="image/*"></div>
    <button type="submit" class="btn btn-primary">Save</button>
    <a href="{{ route('manager.menu-items.index') }}" style="margin-left:12px;">Cancel</a>
</form>
</div>
@endsection
