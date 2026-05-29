@extends('layouts.admin')
@section('title', $template ? 'Edit QR template' : 'New QR template')
@section('content')
@include('partials.admin.page-header', ['title' => $template ? 'Edit QR template' : 'New QR template'])

<div class="card" style="max-width:640px;">
  <form method="post" action="{{ $template ? route('admin.qr-templates.update', $template->id) : route('admin.qr-templates.store') }}">
    @csrf
    @if($template) @method('PUT') @endif
    <div class="filter-group"><label>Name</label><input name="name" value="{{ old('name', $template->name ?? '') }}" required style="width:100%"></div>
    <div class="filter-group" style="margin-top:12px"><label>Description</label><textarea name="description" rows="2" style="width:100%">{{ old('description', $template->description ?? '') }}</textarea></div>
    <div class="filter-group" style="margin-top:12px"><label>Pattern</label>
      <select name="pattern" style="width:100%">
        @foreach(['square','dots','rounded'] as $p)<option value="{{ $p }}" @selected(($config['pattern'] ?? '')===$p)>{{ $p }}</option>@endforeach
      </select>
    </div>
    <div class="filter-group" style="margin-top:12px"><label>Eyes</label>
      <select name="eyes" style="width:100%">
        @foreach(['square','rounded','circle'] as $e)<option value="{{ $e }}" @selected(($config['eyes'] ?? '')===$e)>{{ $e }}</option>@endforeach
      </select>
    </div>
    <div class="filter-group" style="margin-top:12px"><label>Foreground</label><input type="color" name="foreground_color" value="{{ old('foreground_color', $config['colors']['foreground'] ?? '#000000') }}"></div>
    <div class="filter-group" style="margin-top:12px"><label>Background</label><input type="color" name="background_color" value="{{ old('background_color', $config['colors']['background'] ?? '#FFFFFF') }}"></div>
    <div class="filter-group" style="margin-top:12px"><label>Frame type</label>
      <select name="frame_type" style="width:100%">
        @foreach(['none','banner','label'] as $f)<option value="{{ $f }}" @selected(($config['frame']['type'] ?? '')===$f)>{{ $f }}</option>@endforeach
      </select>
    </div>
    <div class="filter-group" style="margin-top:12px"><label>Frame text</label><input name="frame_text" value="{{ old('frame_text', $config['frame']['text'] ?? '') }}" style="width:100%"></div>
    <label style="display:block;margin-top:12px"><input type="checkbox" name="logo_enabled" value="1" @checked(old('logo_enabled', $config['logo']['enabled'] ?? false))> Logo enabled</label>
    @if($template)
      <label style="display:block;margin-top:8px"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $template->is_active ?? 1))> Active</label>
    @endif
    <div style="margin-top:16px">
      <button type="submit" class="btn-filter">Save</button>
      <a href="{{ route('admin.qr-templates.index') }}" class="btn-clear">Cancel</a>
    </div>
  </form>
</div>
@endsection
@push('head')<link rel="stylesheet" href="{{ asset('assets/css/admin-payments.css') }}">@endpush
