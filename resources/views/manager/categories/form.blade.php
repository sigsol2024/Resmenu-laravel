@extends('layouts.manager')
@section('title', $category->exists ? 'Edit category' : 'New category')
@section('content')
<h1 style="margin-bottom:20px;">{{ $category->exists ? 'Edit category' : 'New category' }}</h1>
<div class="card">
<form method="post" action="{{ $category->exists ? route('manager.categories.update', $category) : route('manager.categories.store') }}" enctype="multipart/form-data">
    @csrf
    @if($category->exists) @method('PUT') @endif
    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required>
    </div>
    <div class="form-group">
        <label for="section_id">Primary section</label>
        <select name="section_id" id="section_id" required>
            @foreach($sections as $s)
                <option value="{{ $s->id }}" @selected(old('section_id', $category->section_id) == $s->id)>{{ $s->name }}</option>
            @endforeach
        </select>
    </div>
    @if($sections->count() > 1)
    <div class="form-group">
        <label>Also show in sections (secondary)</label>
        <p style="font-size:0.8rem;color:#6b7280;margin:0 0 8px;">Category appears on its primary section and any checked sections below.</p>
        @foreach($sections as $s)
            <label style="display:block;margin-bottom:4px;">
                <input type="checkbox" name="secondary_section_ids[]" value="{{ $s->id }}"
                    @checked(in_array($s->id, old('secondary_section_ids', $secondarySectionIds ?? [])))>
                {{ $s->name }}
            </label>
        @endforeach
    </div>
    @endif
    <div class="form-group">
        <label for="description">Description</label>
        <textarea name="description" id="description" rows="3">{{ old('description', $category->description) }}</textarea>
    </div>
    <div class="form-group">
        <label for="display_order">Display order</label>
        <input type="number" name="display_order" id="display_order" value="{{ old('display_order', $category->display_order) }}">
    </div>
    <div class="form-group">
        <label><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $category->is_active))> Active</label>
    </div>
    <div class="form-group">
        <label for="image">Image</label>
        <input type="file" name="image" id="image" accept="image/*">
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
    <a href="{{ route('manager.categories.index') }}" style="margin-left:12px;">Cancel</a>
</form>
</div>
@endsection
