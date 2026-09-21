@extends('layouts.admin')

@section('title', 'Edit Administrator')

@section('content')
<div class="page-header">
    <h1 class="page-title">Edit Administrator</h1>
    <p class="page-subtitle">Update account, role, and permissions for {{ $admin->username }}</p>
</div>

<div class="card">
    <div class="card-body">
        <form method="post" action="{{ route('admin.admins.update', $admin) }}" id="admin-form">
            @csrf
            @method('PUT')
            @include('admin.admins._form', [
                'admin' => $admin,
                'isPrimary' => $isPrimary,
                'permissionKeys' => $permissionKeys,
                'permissionLabels' => $permissionLabels,
            ])
            <div style="display:flex;gap:10px;margin-top:16px;">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('head')
<link rel="stylesheet" href="{{ asset('legacy/css/pages/admin-settings.css') }}">
@endpush

@push('scripts')
@include('admin.admins._form-scripts')
@endpush
