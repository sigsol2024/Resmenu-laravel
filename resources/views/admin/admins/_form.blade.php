@php
    $role = old('role', $admin ? ($admin->isSuperAdmin() ? 'super' : 'regular') : 'regular');
    $isActiveDefault = $admin ? $admin->isActive() : true;
    $isActive = old('is_active', $isActiveDefault ? '1' : '0');
@endphp

<div class="form-group">
    <label class="form-label" for="username">Username *</label>
    <input type="text" id="username" name="username" class="form-input" required autocomplete="off" value="{{ old('username', $admin->username ?? '') }}">
</div>

<div class="form-group">
    <label class="form-label" for="email">Email *</label>
    <input type="email" id="email" name="email" class="form-input" required autocomplete="off" value="{{ old('email', $admin->email ?? '') }}">
</div>

<div class="form-group">
    <label class="form-label" for="password">{{ $admin ? 'New password' : 'Password *' }}</label>
    <input type="password" id="password" name="password" class="form-input" autocomplete="new-password" {{ $admin ? '' : 'required' }} placeholder="{{ $admin ? 'Leave blank to keep current' : '' }}">
    <small style="color:#6b7280;display:block;margin-top:4px;font-size:0.75rem;">At least 8 characters with a letter and a number</small>
</div>

<div class="form-group">
    <label class="form-label" style="display:flex;align-items:center;gap:8px;">
        @if($isPrimary)
            <input type="hidden" name="is_active" value="1">
            <input type="checkbox" value="1" checked disabled>
        @else
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" {{ (string) $isActive === '1' ? 'checked' : '' }}>
        @endif
        Active
    </label>
    @if($isPrimary)
        <small style="color:#6b7280;display:block;margin-top:4px;font-size:0.75rem;">The primary Super Admin cannot be deactivated.</small>
    @endif
</div>

<div class="form-group">
    <label class="form-label" for="role">Role *</label>
    @if($isPrimary)
        <input type="hidden" name="role" value="super">
        <select id="role" class="form-input" disabled>
            <option value="super" selected>Super Admin</option>
        </select>
        <small style="color:#6b7280;display:block;margin-top:4px;font-size:0.75rem;">The primary Super Admin cannot be demoted.</small>
    @else
        <select id="role" name="role" class="form-input">
            <option value="regular" {{ $role === 'regular' ? 'selected' : '' }}>Regular Admin</option>
            <option value="super" {{ $role === 'super' ? 'selected' : '' }}>Super Admin</option>
        </select>
    @endif
</div>

<div id="full-access-note" class="info-box" style="margin-bottom:16px;{{ $role === 'super' ? '' : 'display:none;' }}">
    <div class="info-box-content">
        <strong>Full access</strong> — Super Admins can access every administrator module. Permission checkboxes are not required.
    </div>
</div>

<div id="permissions-panel" style="{{ $role === 'regular' ? '' : 'display:none;' }}">
    <p style="margin:0 0 12px;color:#6b7280;font-size:0.875rem;">Module permissions for Regular Admins:</p>
    @foreach($permissionKeys as $key)
        @php
            $column = \App\Models\Admin::PERMISSION_MAP[$key];
            $defaultChecked = $admin ? (bool) $admin->getAttribute($column) : false;
            $checked = (bool) old('permissions.'.$key, $defaultChecked);
        @endphp
        <div class="form-group" style="margin-bottom:8px;">
            <label class="form-label" style="display:flex;align-items:center;gap:8px;font-weight:500;">
                <input type="checkbox" name="permissions[{{ $key }}]" value="1" {{ $checked ? 'checked' : '' }}>
                {{ $permissionLabels[$key] }}
            </label>
        </div>
    @endforeach
</div>
