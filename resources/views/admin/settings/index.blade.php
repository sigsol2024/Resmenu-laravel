@extends('layouts.admin')

@section('title', 'Settings')

@section('content')
<div class="page-header">
    <h1 class="page-title">Settings</h1>
    <p class="page-subtitle">Site configuration, email test, and your profile</p>
</div>

<div class="tabs-container">
    <div class="tabs-nav">
        <button type="button" class="tab-button active" data-tab="site">Site</button>
        <button type="button" class="tab-button" data-tab="contact">Contact Page</button>
        <button type="button" class="tab-button" data-tab="account">Admin Account</button>
    </div>

    <div class="tab-content active" id="tab-site">
        <div class="card" style="margin-bottom:16px;">
            <div class="card-header">
                <h2 class="card-title">Email Configuration Test</h2>
            </div>
            <div class="card-body">
                <p style="margin: 0 0 16px; color: #6b7280; font-size: 0.875rem;">
                    Send a test email to verify your mail configuration.
                </p>
                <form method="post" action="{{ route('admin.settings.index') }}">
                    @csrf
                    <input type="hidden" name="action" value="test_email">
                    <div class="form-group">
                        <label class="form-label" for="test_email">Email address to send test to</label>
                        <input type="email" id="test_email" name="test_email" class="form-input" required placeholder="admin@example.com" value="{{ old('test_email') }}">
                    </div>
                    <button type="submit" class="btn btn-primary">Send Test Email</button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Site Settings</h2>
            </div>
            <div class="card-body">
                <form method="post" action="{{ route('admin.settings.index') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="action" value="update_site">
                    <div class="form-group">
                        <label class="form-label" for="site_name">Site Name</label>
                        <input type="text" id="site_name" name="site_name" class="form-input" value="{{ old('site_name', $settings['site_name'] ?? 'Resmenu') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Site Logo</label>
                        @if($siteLogoUrl)
                            <div><img src="{{ $siteLogoUrl }}" alt="Logo" class="image-preview"></div>
                        @endif
                        <input type="file" name="site_logo" accept="image/jpeg,image/png,image/gif,image/webp" style="margin-top: 8px;">
                        <small style="color: #6b7280; display: block; margin-top: 4px;">Leave empty to keep current. JPG, PNG, GIF, WebP. Max 5MB.</small>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Favicon</label>
                        @if($faviconUrl)
                            <div><img src="{{ $faviconUrl }}" alt="Favicon" class="image-preview"></div>
                        @endif
                        <input type="file" name="favicon" accept="image/jpeg,image/png,image/gif,image/webp,image/x-icon,.ico" style="margin-top: 8px;">
                        <small style="color: #6b7280; display: block; margin-top: 4px;">Leave empty to keep current. PNG, ICO recommended.</small>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Site Settings</button>
                </form>
            </div>
        </div>
    </div>

    <div class="tab-content" id="tab-contact">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Contact Page Settings</h2>
            </div>
            <div class="card-body">
                <form method="post" action="{{ route('admin.settings.index') }}">
                    @csrf
                    <input type="hidden" name="action" value="update_contact">
                    <div class="form-group">
                        <label class="form-label" for="contact_sales_email">Sales email</label>
                        <input type="email" id="contact_sales_email" name="contact_sales_email" class="form-input" value="{{ old('contact_sales_email', $settings['contact_sales_email'] ?? '') }}" placeholder="sales@yourdomain.com">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="contact_sales_phone">Sales phone</label>
                        <input type="text" id="contact_sales_phone" name="contact_sales_phone" class="form-input" value="{{ old('contact_sales_phone', $settings['contact_sales_phone'] ?? '') }}" placeholder="+234 ...">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="contact_support_email">Support email</label>
                        <input type="email" id="contact_support_email" name="contact_support_email" class="form-input" value="{{ old('contact_support_email', $settings['contact_support_email'] ?? '') }}" placeholder="support@yourdomain.com">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="contact_support_phone">Support phone</label>
                        <input type="text" id="contact_support_phone" name="contact_support_phone" class="form-input" value="{{ old('contact_support_phone', $settings['contact_support_phone'] ?? '') }}" placeholder="+234 ...">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="contact_partners_email">Partnerships email</label>
                        <input type="email" id="contact_partners_email" name="contact_partners_email" class="form-input" value="{{ old('contact_partners_email', $settings['contact_partners_email'] ?? '') }}" placeholder="partners@yourdomain.com">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="contact_form_recipient">Contact form recipient email</label>
                        <input type="email" id="contact_form_recipient" name="contact_form_recipient" class="form-input" value="{{ old('contact_form_recipient', $settings['contact_form_recipient'] ?? '') }}" placeholder="where contact form emails go">
                    </div>
                    <hr style="margin: 20px 0;">
                    <div class="form-group">
                        <label class="form-label" for="contact_hq_title">HQ label</label>
                        <input type="text" id="contact_hq_title" name="contact_hq_title" class="form-input" value="{{ old('contact_hq_title', $settings['contact_hq_title'] ?? '') }}" placeholder="Lagos HQ">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="contact_hq_address">HQ address</label>
                        <textarea id="contact_hq_address" name="contact_hq_address" class="form-input" rows="3" placeholder="Street, city, country">{{ old('contact_hq_address', $settings['contact_hq_address'] ?? '') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="contact_map_embed">Custom map embed (optional)</label>
                        <textarea id="contact_map_embed" name="contact_map_embed" class="form-input" rows="4" placeholder="Optional: paste a full Google Maps iframe here">{{ old('contact_map_embed', $settings['contact_map_embed'] ?? '') }}</textarea>
                        <small style="color:#6b7280;display:block;margin-top:4px;font-size:0.75rem;">Leave empty to auto-generate a map from the HQ address. Use Google Maps &quot;Share&quot; &rarr; &quot;Embed a map&quot; iframe only if you need full control.</small>
                    </div>
                    <hr style="margin: 20px 0;">
                    <div class="form-group">
                        <label class="form-label" for="contact_social_facebook">Facebook URL</label>
                        <input type="url" id="contact_social_facebook" name="contact_social_facebook" class="form-input" value="{{ old('contact_social_facebook', $settings['contact_social_facebook'] ?? '') }}" placeholder="https://facebook.com/yourpage">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="contact_social_twitter">Twitter/X URL</label>
                        <input type="url" id="contact_social_twitter" name="contact_social_twitter" class="form-input" value="{{ old('contact_social_twitter', $settings['contact_social_twitter'] ?? '') }}" placeholder="https://twitter.com/yourhandle">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="contact_social_instagram">Instagram URL</label>
                        <input type="url" id="contact_social_instagram" name="contact_social_instagram" class="form-input" value="{{ old('contact_social_instagram', $settings['contact_social_instagram'] ?? '') }}" placeholder="https://instagram.com/yourhandle">
                    </div>
                    <button type="submit" class="btn btn-primary">Update Contact Settings</button>
                </form>
            </div>
        </div>
    </div>

    <div class="tab-content" id="tab-account">
        <div class="card" style="margin-bottom:16px;">
            <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
                <h2 class="card-title" style="margin:0;">Administrators ({{ $admins->count() }})</h2>
                <button type="button" class="btn btn-primary btn-sm" onclick="openAddAdminModal()">Add Administrator</button>
            </div>
            <div class="card-body">
                <p style="margin:0 0 16px;color:#6b7280;font-size:0.875rem;">Create additional admin logins, edit their details, reset passwords, or remove accounts. The primary account cannot be deleted.</p>
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
                                <div class="admin-list-meta">{{ $admin->email }}</div>
                                <div class="admin-list-meta">Added {{ $admin->created_at ? $admin->created_at->format('M j, Y') : 'N/A' }}</div>
                            </div>
                            <div class="admin-actions">
                                <button type="button" class="btn btn-secondary btn-sm btn-edit-admin"
                                    data-id="{{ $admin->id }}"
                                    data-username="{{ $admin->username }}"
                                    data-email="{{ $admin->email }}">Edit</button>
                                @if((int) $admin->id !== (int) $currentAdmin->id && (int) $admin->id !== (int) $primaryAdminId)
                                    <form method="post" action="{{ route('admin.settings.index') }}" style="display:inline;" onsubmit="return confirm('Delete this administrator? This cannot be undone.');">
                                        @csrf
                                        <input type="hidden" name="action" value="delete_admin">
                                        <input type="hidden" name="target_admin_id" value="{{ $admin->id }}">
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="card" style="margin-bottom:16px;">
            <div class="card-header">
                <h2 class="card-title">Account Information</h2>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Username</label>
                    <div class="info-display">{{ $currentAdmin->username }}</div>
                </div>
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <div class="info-display">{{ $currentAdmin->email }}</div>
                </div>
                <div class="form-group">
                    <label class="form-label">Account Created</label>
                    <div class="info-display">{{ $currentAdmin->created_at ? $currentAdmin->created_at->format('F j, Y g:i A') : 'N/A' }}</div>
                </div>
            </div>
        </div>

        <div class="card" style="margin-bottom:16px;">
            <div class="card-header">
                <h2 class="card-title">Update Profile</h2>
            </div>
            <div class="card-body">
                <form method="post" action="{{ route('admin.settings.index') }}">
                    @csrf
                    <input type="hidden" name="action" value="update_profile">
                    <div class="form-group">
                        <label class="form-label" for="username">Username *</label>
                        <input type="text" id="username" name="username" class="form-input" required value="{{ old('username', $currentAdmin->username) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="email">Email *</label>
                        <input type="email" id="email" name="email" class="form-input" required value="{{ old('email', $currentAdmin->email) }}">
                    </div>
                    <button type="submit" class="btn btn-primary">Update Profile</button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Change Password</h2>
            </div>
            <div class="card-body">
                <form method="post" action="{{ route('admin.settings.index') }}">
                    @csrf
                    <input type="hidden" name="action" value="update_password">
                    <div class="form-group">
                        <label class="form-label" for="current_password">Current Password *</label>
                        <input type="password" id="current_password" name="current_password" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="new_password">New Password *</label>
                        <input type="password" id="new_password" name="new_password" class="form-input" required minlength="8">
                        <small style="color: #6b7280; display: block; margin-top: 5px; font-size: 0.75rem;">At least 8 characters with a letter and a number</small>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="new_password_confirmation">Confirm New Password *</label>
                        <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="form-input" required minlength="8">
                    </div>
                    <button type="submit" class="btn btn-primary">Change Password</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal-overlay" id="addAdminModal" aria-hidden="true">
    <div class="modal-box" role="dialog" aria-labelledby="addAdminModalTitle">
        <div class="modal-header">
            <h3 class="modal-title" id="addAdminModalTitle">Add Administrator</h3>
            <button type="button" class="modal-close" onclick="closeAddAdminModal()" aria-label="Close">&times;</button>
        </div>
        <form method="post" action="{{ route('admin.settings.index') }}">
            @csrf
            <input type="hidden" name="action" value="add_admin">
            <div class="form-group">
                <label class="form-label" for="new_admin_username">Username *</label>
                <input type="text" id="new_admin_username" name="new_admin_username" class="form-input" required autocomplete="off" value="{{ old('new_admin_username') }}">
            </div>
            <div class="form-group">
                <label class="form-label" for="new_admin_email">Email *</label>
                <input type="email" id="new_admin_email" name="new_admin_email" class="form-input" required autocomplete="off" value="{{ old('new_admin_email') }}">
            </div>
            <div class="form-group">
                <label class="form-label" for="new_admin_password">Password *</label>
                <input type="password" id="new_admin_password" name="new_admin_password" class="form-input" required autocomplete="new-password">
                <small style="color:#6b7280;display:block;margin-top:4px;font-size:0.75rem;">At least 8 characters with a letter and a number</small>
            </div>
            <div style="display:flex;gap:10px;margin-top:8px;">
                <button type="submit" class="btn btn-primary">Add Administrator</button>
                <button type="button" class="btn btn-secondary" onclick="closeAddAdminModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<div class="modal-overlay" id="editAdminModal" aria-hidden="true">
    <div class="modal-box" role="dialog" aria-labelledby="editAdminModalTitle">
        <div class="modal-header">
            <h3 class="modal-title" id="editAdminModalTitle">Edit Administrator</h3>
            <button type="button" class="modal-close" onclick="closeEditAdminModal()" aria-label="Close">&times;</button>
        </div>
        <form method="post" action="{{ route('admin.settings.index') }}" id="editAdminForm">
            @csrf
            <input type="hidden" name="action" value="update_admin">
            <input type="hidden" name="target_admin_id" id="edit_target_admin_id" value="">
            <div class="form-group">
                <label class="form-label" for="target_username">Username *</label>
                <input type="text" id="target_username" name="target_username" class="form-input" required autocomplete="off">
            </div>
            <div class="form-group">
                <label class="form-label" for="target_email">Email *</label>
                <input type="email" id="target_email" name="target_email" class="form-input" required autocomplete="off">
            </div>
            <div class="form-group">
                <label class="form-label" for="target_new_password">New password</label>
                <input type="password" id="target_new_password" name="target_new_password" class="form-input" autocomplete="new-password" placeholder="Leave blank to keep current">
                <small style="color:#6b7280;display:block;margin-top:4px;font-size:0.75rem;">Only fill in to reset this admin&apos;s password</small>
            </div>
            <div style="display:flex;gap:10px;margin-top:8px;">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <button type="button" class="btn btn-secondary" onclick="closeEditAdminModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('head')
<link rel="stylesheet" href="{{ asset('legacy/css/pages/admin-settings.css') }}">
@endpush

@push('scripts')
<script>
function openAddAdminModal() {
    document.getElementById('addAdminModal').classList.add('active');
    document.getElementById('new_admin_username').focus();
}
function closeAddAdminModal() {
    document.getElementById('addAdminModal').classList.remove('active');
}
function openEditAdminModal(id, username, email) {
    document.getElementById('edit_target_admin_id').value = id;
    document.getElementById('target_username').value = username;
    document.getElementById('target_email').value = email;
    document.getElementById('target_new_password').value = '';
    document.getElementById('editAdminModal').classList.add('active');
}
function closeEditAdminModal() {
    document.getElementById('editAdminModal').classList.remove('active');
    document.getElementById('editAdminForm').reset();
}
document.querySelectorAll('.btn-edit-admin').forEach(function(btn) {
    btn.addEventListener('click', function() {
        openEditAdminModal(
            parseInt(this.getAttribute('data-id'), 10),
            this.getAttribute('data-username') || '',
            this.getAttribute('data-email') || ''
        );
    });
});
['addAdminModal', 'editAdminModal'].forEach(function(id) {
    var el = document.getElementById(id);
    if (el) el.addEventListener('click', function(e) {
        if (e.target === el) {
            if (id === 'addAdminModal') closeAddAdminModal();
            else closeEditAdminModal();
        }
    });
});
(function() {
    var buttons = document.querySelectorAll('.tab-button');
    var contents = {
        site: document.getElementById('tab-site'),
        contact: document.getElementById('tab-contact'),
        account: document.getElementById('tab-account')
    };
    buttons.forEach(function(btn) {
        btn.addEventListener('click', function() {
            var tab = this.getAttribute('data-tab');
            buttons.forEach(function(b) { b.classList.remove('active'); });
            this.classList.add('active');
            Object.keys(contents).forEach(function(key) {
                if (contents[key]) {
                    contents[key].classList.toggle('active', key === tab);
                }
            });
        });
    });
})();
</script>
@endpush
