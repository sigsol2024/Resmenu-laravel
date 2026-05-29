@extends('layouts.admin')
@section('title', 'QR templates')
@section('content')
<div class="admin-page-header-row">
  @include('partials.admin.page-header', ['title' => 'QR Code Templates', 'subtitle' => 'QR code style presets'])
  <div style="display:flex;gap:10px;flex-wrap:wrap">
    <form method="post" action="{{ route('admin.qr-templates.regenerate-previews') }}" style="display:inline" onsubmit="return confirm('Generate preview images for all templates?')">
      @csrf
      <button type="submit" class="btn-clear">Regenerate all previews</button>
    </form>
    <a href="{{ route('admin.qr-templates.create') }}" class="btn-primary">Create New Template</a>
  </div>
</div>

<div class="admin-list-card">
  <div class="table-card">
    <table class="data-table">
      <thead><tr><th>ID</th><th>Name</th><th>Description</th><th>Has Text</th><th>Status</th><th>Usage</th><th>Actions</th></tr></thead>
      <tbody>
      @forelse($templates as $t)
        <tr>
          <td>{{ $t->id }}</td>
          <td>{{ $t->name }}</td>
          <td>{{ $t->description ?? '' }}</td>
          <td>{{ $t->has_text ? 'Yes' : 'No' }}</td>
          <td>
            <span class="status-badge {{ $t->is_active ? 'active' : 'inactive' }}">{{ $t->is_active ? 'Active' : 'Inactive' }}</span>
          </td>
          <td>{{ $t->usage_count ?? 0 }} restaurant(s)</td>
          <td>
            @include('partials.admin.actions-dropdown', [
              'items' => [
                ['label' => 'Edit', 'url' => route('admin.qr-templates.edit', $t->id)],
                ['type' => 'divider'],
                [
                  'type' => 'form',
                  'label' => 'Delete',
                  'action' => route('admin.qr-templates.destroy', $t->id),
                  'method' => 'DELETE',
                  'class' => 'danger',
                  'confirm' => ($t->usage_count ?? 0) > 0
                    ? 'Delete this template? This will clear the template selection for '.$t->usage_count.' restaurant(s).'
                    : 'Delete this QR template?',
                ],
              ],
            ])
          </td>
        </tr>
      @empty
        <tr><td colspan="7" style="text-align:center;padding:40px;color:#6b7280">No templates found.</td></tr>
      @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
@push('head')
<link rel="stylesheet" href="{{ asset('assets/css/admin-payments.css') }}">
@endpush
