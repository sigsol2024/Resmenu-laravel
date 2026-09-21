@extends('layouts.admin')

@section('title', 'Settings')

@section('content')
<div class="page-header">
    <h1 class="page-title">Settings</h1>
    <p class="page-subtitle">Site branding, contact page, and email configuration</p>
</div>

<div class="tabs-container">
    <div class="tabs-nav">
        <button type="button" class="tab-button active" data-tab="site">Site</button>
        <button type="button" class="tab-button" data-tab="contact">Contact Page</button>
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
                        <small style="color: #6b7280; display: block; margin-top: 4px;">Leave empty to keep current. JPG, PNG, GIF, WebP. Max ~1MB.</small>
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
</div>
@endsection

@push('head')
<link rel="stylesheet" href="{{ asset('legacy/css/pages/admin-settings.css') }}">
@endpush

@push('scripts')
<script>
(function() {
    var buttons = document.querySelectorAll('.tab-button');
    var contents = {
        site: document.getElementById('tab-site'),
        contact: document.getElementById('tab-contact')
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
