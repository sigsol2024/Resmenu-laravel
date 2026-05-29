@extends('layouts.admin')
@section('title', $restaurant->exists ? 'Edit restaurant' : 'New restaurant')
@section('content')
@include('partials.admin.page-header', ['title' => $restaurant->exists ? 'Edit restaurant' : 'New restaurant'])

<div class="card" style="max-width:720px;">
  <form method="post" action="{{ $restaurant->exists ? route('admin.restaurants.update', $restaurant) : route('admin.restaurants.store') }}" enctype="multipart/form-data">
    @csrf
    @if($restaurant->exists) @method('PUT') @endif
    <div class="filter-group"><label>Name</label><input name="name" value="{{ old('name', $restaurant->name) }}" required style="width:100%"></div>
    <div class="filter-group" style="margin-top:12px"><label>Slug</label><input name="slug" value="{{ old('slug', $restaurant->slug) }}" style="width:100%"></div>
    <div class="filter-group" style="margin-top:12px"><label>Phone</label><input name="phone" value="{{ old('phone', $restaurant->phone) }}" style="width:100%"></div>
    <div class="filter-group" style="margin-top:12px"><label>Address</label><textarea name="address" rows="2" style="width:100%">{{ old('address', $restaurant->address) }}</textarea></div>
    <div class="filter-group" style="margin-top:12px"><label>Description</label><textarea name="description" rows="3" style="width:100%">{{ old('description', $restaurant->description) }}</textarea></div>
    <div class="filter-group" style="margin-top:12px"><label>Template ID</label><input type="number" name="template_id" value="{{ old('template_id', $restaurant->template_id ?? 4) }}" min="1" style="width:100%"></div>
    @if(!$restaurant->exists)
      <div class="filter-group" style="margin-top:12px"><label>Plan</label>
        <select name="plan_id" style="width:100%"><option value="">â€”</option>@foreach($plans as $p)<option value="{{ $p->id }}">{{ $p->name }}</option>@endforeach</select>
      </div>
    @endif
    <label style="display:block;margin-top:12px"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $restaurant->is_active ?? true))> Active</label>
    <div class="filter-group" style="margin-top:12px"><label>Logo</label><input type="file" name="logo" accept="image/*"></div>
    <div class="filter-group" style="margin-top:12px"><label>Hero image</label><input type="file" name="hero_image" accept="image/*"></div>
    <hr style="margin:20px 0">
    <h3>Manager account</h3>
    <div class="filter-group"><label>Username</label><input name="manager_username" value="{{ old('manager_username', $manager->username ?? '') }}" required style="width:100%"></div>
    <div class="filter-group" style="margin-top:12px"><label>Email</label><input type="email" name="manager_email" value="{{ old('manager_email', $manager->email ?? $restaurant->email) }}" required style="width:100%"></div>
    <div class="filter-group" style="margin-top:12px"><label>Password{{ $restaurant->exists ? ' (leave blank to keep)' : '' }}</label><input type="password" name="manager_password" {{ $restaurant->exists ? '' : 'required' }} style="width:100%"></div>
    <button type="submit" class="btn-filter" style="margin-top:16px">Save</button>
    <a href="{{ route('admin.restaurants.index') }}" class="btn-clear">Cancel</a>
  </form>
</div>
@endsection
@push('head')<link rel="stylesheet" href="{{ asset('legacy/css/pages/admin-payments.css') }}">@endpush
