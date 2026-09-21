@extends('layouts.admin')

@section('title', 'Add Administrator')

@section('content')
<div class="page-header">
    <h1 class="page-title">Add Administrator</h1>
    <p class="page-subtitle">Create a Super Admin or Regular Admin with module permissions</p>
</div>

<div class="card">
    <div class="card-body">
        <form method="post" action="{{ route('admin.admins.store') }}" id="admin-form">
            @csrf
            @include('admin.admins._form', [
                'admin' => null,
                'isPrimary' => false,
                'permissionKeys' => $permissionKeys,
                'permissionLabels' => $permissionLabels,
            ])
            <div style="display:flex;gap:10px;margin-top:16px;">
                <button type="submit" class="btn btn-primary">Create Administrator</button>
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
