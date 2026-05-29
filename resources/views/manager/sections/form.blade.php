@extends('layouts.manager')
@section('title', $section->exists ? 'Edit section' : 'New section')
@push('head')
<link rel="stylesheet" href="{{ asset('legacy/css/pages/manager-sections.css') }}">
@endpush
@section('content')
<div class="page-header">
    <h1 class="page-title">{{ $section->exists ? 'Edit section' : 'New section' }}</h1>
    <p class="page-subtitle">Sections group categories on your menu. Reorder sections to change how they appear.</p>
</div>
<div class="card">
<form method="post" action="{{ $section->exists ? route('manager.sections.update', $section) : route('manager.sections.store') }}">
    @csrf
    @if($section->exists) @method('PUT') @endif
    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" name="name" id="name" value="{{ old('name', $section->name) }}" required>
    </div>
    <div class="form-group">
        <label for="display_order">Display order</label>
        <input type="number" name="display_order" id="display_order" value="{{ old('display_order', $section->display_order) }}">
    </div>
    <div class="form-group">
        <label><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $section->is_active))> Active</label>
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
    <a href="{{ route('manager.sections.index') }}" style="margin-left:12px;">Cancel</a>
</form>
</div>
@endsection
