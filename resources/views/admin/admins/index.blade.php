@extends('layouts.admin')

@section('title', 'Administrators')

@section('content')
<div class="page-header" style="display:flex;align-items:flex-start;justify-content:space-between;gap:16px;flex-wrap:wrap;">
    <div>
        <h1 class="page-title">Administrators</h1>
        <p class="page-subtitle">Manage Super Admins, Regular Admins, and module permissions</p>
    </div>
    <a href="{{ route('admin.admins.create') }}" class="btn btn-primary">Add Administrator</a>
</div>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">All administrators ({{ $admins->count() }})</h2>
    </div>
    <div class="card-body">
        <ul class="admin-list">
            @foreach($admins as $admin)
                <li class="admin-list-item">
                    <div>
                        <strong>{{ $admin->username }}</strong>
                        @if((int) $admin->id === (int) $primaryAdminId)
                            <span class="admin-badge admin-badge-primary">Primary</span>
                        @endif
                        @if((int) $admin->id === (int) $currentAdmin->id)
                            <span class="admin-badge admin-badge-you">You</span>
                        @endif
                        @if($admin->isSuperAdmin())
                            <span class="admin-badge" style="background:#eef2ff;color:#3730a3;">Super Admin</span>
                        @else
                            <span class="admin-badge" style="background:#f3f4f6;color:#374151;">Administrator</span>
                        @endif
                        @unless($admin->isActive())
                            <span class="admin-badge" style="background:#fef2f2;color:#991b1b;">Inactive</span>
                        @endunless
                        <div class="admin-list-meta">{{ $admin->email }}</div>
                        <div class="admin-list-meta">
                            @if($admin->isSuperAdmin())
                                Full access
                            @else
                                @php
                                    $granted = collect($permissionKeys)->filter(fn ($key) => $admin->hasPermission($key))->values();
                                @endphp
                                {{ $granted->isEmpty() ? 'No module permissions' : $granted->implode(', ') }}
                            @endif
                        </div>
                    </div>
                    <div class="admin-actions">
                        <a href="{{ route('admin.admins.edit', $admin) }}" class="btn btn-secondary btn-sm">Edit</a>
                        @if((int) $admin->id !== (int) $currentAdmin->id && (int) $admin->id !== (int) $primaryAdminId)
                            <form method="post" action="{{ route('admin.admins.destroy', $admin) }}" style="display:inline;" onsubmit="return confirm('Delete this administrator? This cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        @endif
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</div>
@endsection

@push('head')
<link rel="stylesheet" href="{{ asset('legacy/css/pages/admin-settings.css') }}">
@endpush
