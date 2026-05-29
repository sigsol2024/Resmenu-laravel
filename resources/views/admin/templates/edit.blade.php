@extends('layouts.admin')
@section('title', 'Edit template')
@section('content')
@include('partials.admin.page-header', ['title' => 'Edit template #'.$template->id, 'subtitle' => $template->slug ?? ''])

<div class="card" style="max-width:720px;">
  <form method="post" action="{{ route('admin.templates.update', $template->id) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="filter-group"><label>Name</label><input name="name" value="{{ old('name', $template->name) }}" required style="width:100%"></div>
    <div class="filter-group" style="margin-top:12px"><label>Description</label><textarea name="description" rows="3" style="width:100%">{{ old('description', $template->description ?? '') }}</textarea></div>
    <div class="filter-group" style="margin-top:12px"><label>Preview image</label><input type="file" name="preview_image" accept="image/*"></div>
    <div class="filter-group" style="margin-top:12px"><label>Listing image</label><input type="file" name="listing_image" accept="image/*"></div>
    <label style="display:block;margin-top:12px"><input type="checkbox" name="is_private" value="1" @checked(old('is_private', $template->is_private ?? 0))> Private template</label>
    <fieldset style="margin-top:16px;border:1px solid #e5e7eb;padding:12px;border-radius:8px">
      <legend>Available on plans</legend>
      @foreach($plans as $plan)
        <label style="display:block"><input type="checkbox" name="plan_ids[]" value="{{ $plan->id }}" @checked(in_array($plan->id, old('plan_ids', $assignedPlanIds)))> {{ $plan->name }}</label>
      @endforeach
    </fieldset>
    <fieldset style="margin-top:16px;border:1px solid #e5e7eb;padding:12px;border-radius:8px">
      <legend>Assigned restaurants (private override)</legend>
      <select name="restaurant_ids[]" multiple size="8" style="width:100%">
        @foreach($restaurants as $r)
          <option value="{{ $r->id }}" @selected(in_array($r->id, old('restaurant_ids', $assignedRestaurantIds)))>{{ $r->name }}</option>
        @endforeach
      </select>
    </fieldset>
    <div style="margin-top:16px">
      <button type="submit" class="btn-filter">Save</button>
      <a href="{{ route('admin.templates.index') }}" class="btn-clear">Back</a>
      <a href="{{ route('public.template.preview', $template->id) }}" target="_blank" class="btn-clear">Preview</a>
    </div>
  </form>
</div>
@endsection
@push('head')<link rel="stylesheet" href="{{ asset('legacy/css/pages/admin-payments.css') }}">@endpush
