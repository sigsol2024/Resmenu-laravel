@extends('layouts.admin')

@section('title', 'Administrators')

@section('content')
<div class="page-header" style="display:flex;align-items:flex-start;justify-content:space-between;gap:16px;flex-wrap:wrap;">
    <div>
        <h1 class="page-title">Administrators</h1>
        <p class="page-subtitle">Manage Super Admins, Regular Admins, and module permissions</p>
    </div>
    <button type="button" class="btn btn-primary" id="openAddAdminModal">Add Administrator</button>
</div>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">All administrators ({{ $admins->count() }})</h2>
    </div>
    <div class="card-body">
        <ul class="admin-list">
            @foreach($admins as $admin)
                @php
                    $perms = [];
                    foreach ($permissionKeys as $key) {
                        $perms[$key] = (bool) $admin->getAttribute(\App\Models\Admin::PERMISSION_MAP[$key]);
                    }
                @endphp
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
                        <button type="button"
                            class="btn btn-secondary btn-sm btn-edit-admin"
                            data-id="{{ $admin->id }}"
                            data-username="{{ $admin->username }}"
                            data-email="{{ $admin->email }}"
                            data-role="{{ $admin->isSuperAdmin() ? 'super' : 'regular' }}"
                            data-active="{{ $admin->isActive() ? '1' : '0' }}"
                            data-primary="{{ (int) $admin->id === (int) $primaryAdminId ? '1' : '0' }}"
                            data-update-url="{{ route('admin.admins.update', $admin) }}"
                            data-permissions="{{ e(json_encode($perms)) }}">Edit</button>
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

<div class="modal-overlay" id="addAdminModal" aria-hidden="true">
    <div class="modal-box" role="dialog" aria-labelledby="addAdminModalTitle" style="max-height:90vh;overflow:auto;">
        <div class="modal-header">
            <h3 class="modal-title" id="addAdminModalTitle">Add Administrator</h3>
            <button type="button" class="modal-close" id="closeAddAdminModal" aria-label="Close">&times;</button>
        </div>
        <form method="post" action="{{ route('admin.admins.store') }}" id="addAdminForm">
            @csrf
            @include('admin.admins._form', [
                'admin' => null,
                'isPrimary' => false,
                'idPrefix' => 'add_',
                'permissionKeys' => $permissionKeys,
                'permissionLabels' => $permissionLabels,
            ])
            <div style="display:flex;gap:10px;margin-top:16px;">
                <button type="submit" class="btn btn-primary">Create Administrator</button>
                <button type="button" class="btn btn-secondary" id="cancelAddAdminModal">Cancel</button>
            </div>
        </form>
    </div>
</div>

<div class="modal-overlay" id="editAdminModal" aria-hidden="true">
    <div class="modal-box" role="dialog" aria-labelledby="editAdminModalTitle" style="max-height:90vh;overflow:auto;">
        <div class="modal-header">
            <h3 class="modal-title" id="editAdminModalTitle">Edit Administrator</h3>
            <button type="button" class="modal-close" id="closeEditAdminModal" aria-label="Close">&times;</button>
        </div>
        <form method="post" action="#" id="editAdminForm">
            @csrf
            @method('PUT')
            @include('admin.admins._form', [
                'admin' => null,
                'forceEditMode' => true,
                'isPrimary' => false,
                'idPrefix' => 'edit_',
                'permissionKeys' => $permissionKeys,
                'permissionLabels' => $permissionLabels,
            ])
            <div style="display:flex;gap:10px;margin-top:16px;">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <button type="button" class="btn btn-secondary" id="cancelEditAdminModal">Cancel</button>
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
<script>
(function () {
    function bindModal(overlayId, openBtnId, closeIds) {
        var modal = document.getElementById(overlayId);
        if (!modal) return null;
        function open() {
            modal.classList.add('active');
            modal.setAttribute('aria-hidden', 'false');
        }
        function close() {
            modal.classList.remove('active');
            modal.setAttribute('aria-hidden', 'true');
        }
        var openBtn = openBtnId ? document.getElementById(openBtnId) : null;
        if (openBtn) openBtn.addEventListener('click', open);
        (closeIds || []).forEach(function (id) {
            var el = document.getElementById(id);
            if (el) el.addEventListener('click', close);
        });
        modal.addEventListener('click', function (e) {
            if (e.target === modal) close();
        });
        return { modal: modal, open: open, close: close };
    }

    var addUi = bindModal('addAdminModal', 'openAddAdminModal', ['closeAddAdminModal', 'cancelAddAdminModal']);
    var editUi = bindModal('editAdminModal', null, ['closeEditAdminModal', 'cancelEditAdminModal']);

    var addForm = document.getElementById('addAdminForm');
    if (addForm && addUi) {
        document.getElementById('openAddAdminModal')?.addEventListener('click', function () {
            addForm.reset();
            var activeCb = document.getElementById('add_is_active');
            if (activeCb) activeCb.checked = true;
            var roleSelect = document.getElementById('add_role');
            if (roleSelect) roleSelect.value = 'regular';
            roleSelect?.dispatchEvent(new Event('change'));
            document.getElementById('add_username')?.focus();
        });
    }

    var editModal = editUi && editUi.modal;
    var form = document.getElementById('editAdminForm');
    if (!editModal || !form) return;

    var roleSelect = document.getElementById('edit_role');
    var activeCheckbox = document.getElementById('edit_is_active');
    var usernameDisplay = document.getElementById('edit_username_display');
    var emailInput = document.getElementById('edit_email');
    var passwordInput = document.getElementById('edit_password');
    var panel = document.getElementById('edit_permissions-panel');
    var note = document.getElementById('edit_full-access-note');

    function syncRoleUi() {
        if (!roleSelect || !panel || !note) return;
        var isSuper = roleSelect.value === 'super' || roleSelect.disabled;
        panel.style.display = isSuper ? 'none' : '';
        note.style.display = isSuper ? '' : 'none';
    }

    function fillForm(btn) {
        var isPrimary = btn.getAttribute('data-primary') === '1';
        var role = btn.getAttribute('data-role') || 'regular';
        var active = btn.getAttribute('data-active') === '1';
        var perms = {};
        try { perms = JSON.parse(btn.getAttribute('data-permissions') || '{}'); } catch (e) { perms = {}; }

        form.action = btn.getAttribute('data-update-url') || '#';
        if (usernameDisplay) usernameDisplay.textContent = btn.getAttribute('data-username') || '';
        if (emailInput) emailInput.value = btn.getAttribute('data-email') || '';
        if (passwordInput) passwordInput.value = '';

        var roleGroup = roleSelect ? roleSelect.closest('.form-group') : null;
        if (roleGroup) {
            var existingHidden = roleGroup.querySelector('input[type="hidden"][name="role"]');
            if (existingHidden) existingHidden.remove();
            var existingHint = roleGroup.querySelector('.primary-role-hint');
            if (existingHint) existingHint.remove();

            if (isPrimary) {
                roleSelect.name = '';
                roleSelect.disabled = true;
                roleSelect.innerHTML = '<option value="super" selected>Super Admin</option>';
                var hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = 'role';
                hidden.value = 'super';
                roleGroup.insertBefore(hidden, roleSelect);
                var hint = document.createElement('small');
                hint.className = 'primary-role-hint';
                hint.style.cssText = 'color:#6b7280;display:block;margin-top:4px;font-size:0.75rem;';
                hint.textContent = 'The primary Super Admin cannot be demoted.';
                roleGroup.appendChild(hint);
            } else {
                roleSelect.disabled = false;
                roleSelect.name = 'role';
                roleSelect.innerHTML =
                    '<option value="regular"' + (role === 'regular' ? ' selected' : '') + '>Regular Admin</option>' +
                    '<option value="super"' + (role === 'super' ? ' selected' : '') + '>Super Admin</option>';
            }
        }

        if (activeCheckbox) {
            var activeGroup = activeCheckbox.closest('.form-group');
            var activeHidden = activeGroup ? activeGroup.querySelector('input[type="hidden"][name="is_active"]') : null;
            var activeHint = activeGroup ? activeGroup.querySelector('.primary-active-hint') : null;
            if (activeHint) activeHint.remove();

            if (isPrimary) {
                activeCheckbox.checked = true;
                activeCheckbox.disabled = true;
                activeCheckbox.removeAttribute('name');
                if (activeHidden) activeHidden.value = '1';
                if (activeGroup) {
                    var ah = document.createElement('small');
                    ah.className = 'primary-active-hint';
                    ah.style.cssText = 'color:#6b7280;display:block;margin-top:4px;font-size:0.75rem;';
                    ah.textContent = 'The primary Super Admin cannot be deactivated.';
                    activeGroup.appendChild(ah);
                }
            } else {
                activeCheckbox.disabled = false;
                activeCheckbox.name = 'is_active';
                activeCheckbox.checked = active;
                if (activeHidden) activeHidden.value = '0';
            }
        }

        form.querySelectorAll('.edit_perm-checkbox').forEach(function (cb) {
            var key = cb.getAttribute('data-perm');
            cb.checked = !!perms[key];
        });

        roleSelect = document.getElementById('edit_role');
        if (roleSelect && !roleSelect._bound) {
            roleSelect.addEventListener('change', syncRoleUi);
            roleSelect._bound = true;
        }
        syncRoleUi();
        editUi.open();
        if (emailInput) emailInput.focus();
    }

    document.querySelectorAll('.btn-edit-admin').forEach(function (btn) {
        btn.addEventListener('click', function () { fillForm(btn); });
    });

    var params = new URLSearchParams(window.location.search);
    var editId = params.get('edit');
    if (editId) {
        var match = document.querySelector('.btn-edit-admin[data-id="' + editId + '"]');
        if (match) fillForm(match);
    }
    if (params.get('create') === '1' && addUi) {
        document.getElementById('openAddAdminModal')?.click();
    }
})();
</script>
@endpush
