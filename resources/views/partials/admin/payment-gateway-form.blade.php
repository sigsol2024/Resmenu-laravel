@php
    $gateway = $gateway ?? 'paystack';
    $label = $label ?? ucfirst($gateway);
    $settings = $settings ?? null;
    $webhookUrl = $webhookUrl ?? '';
    $isActive = !empty($settings->is_active);
    $testMode = ($settings->test_mode ?? 1) ? true : false;
    $hasSecretLive = !empty($settings->secret_key_live);
    $hasSecretTest = !empty($settings->secret_key_test);
@endphp

<form method="post" action="{{ route('admin.payment-settings.update') }}">
    @csrf
    <input type="hidden" name="gateway" value="{{ $gateway }}">

    <div class="settings-card">
        <div class="section-header">
            <div class="section-title">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ $label }} Configuration
            </div>
            <span class="status-badge {{ $isActive ? 'active' : 'inactive' }}">
                <span class="status-dot"></span>
                {{ $isActive ? 'Active' : 'Inactive' }}
            </span>
        </div>

        <div class="toggle-row">
            <div class="toggle-info">
                <div class="toggle-label">Enable {{ $label }}</div>
                <div class="toggle-description">Allow customers to pay with {{ $label }}</div>
            </div>
            <label class="toggle-switch">
                <input type="checkbox" name="is_active" value="1" @checked($isActive)>
                <span class="toggle-slider"></span>
            </label>
        </div>

        <div class="toggle-row">
            <div class="toggle-info">
                <div class="toggle-label">Test Mode</div>
                <div class="toggle-description">Use test credentials (no real payments)</div>
            </div>
            <label class="toggle-switch">
                <input type="checkbox" name="test_mode" value="1" id="{{ $gateway }}_test_mode" @checked($testMode) onchange="toggleTestCredentials('{{ $gateway }}', this.checked)">
                <span class="toggle-slider"></span>
            </label>
        </div>
    </div>

    <div class="settings-card" id="{{ $gateway }}_test_credentials" style="display: {{ $testMode ? 'block' : 'none' }};">
        <div class="section-subtitle">
            <span>Test Credentials</span>
            <span class="badge badge-test">TEST</span>
        </div>
        <div class="form-group">
            <label for="{{ $gateway }}_public_key_test">Public Key</label>
            <input type="text" id="{{ $gateway }}_public_key_test" name="public_key_test" value="{{ $settings->public_key_test ?? '' }}" placeholder="{{ $gateway === 'paystack' ? 'pk_test_xxxxxxxxxxxxx' : 'FLWPUBK_TEST-xxxxxxxxxxxxx' }}">
        </div>
        <div class="form-group">
            <label for="{{ $gateway }}_secret_key_test">Secret Key</label>
            <input type="password" id="{{ $gateway }}_secret_key_test" name="secret_key_test" placeholder="{{ $hasSecretTest ? '••••••••••••••••' : ($gateway === 'paystack' ? 'sk_test_xxxxxxxxxxxxx' : 'FLWSECK_TEST-xxxxxxxxxxxxx') }}">
            <div class="form-hint">Leave empty to keep existing key</div>
        </div>
        <div class="form-group">
            <label for="{{ $gateway }}_webhook_secret_test">Webhook Secret</label>
            <input type="text" id="{{ $gateway }}_webhook_secret_test" name="webhook_secret_test" value="{{ $settings->webhook_secret_test ?? '' }}" placeholder="Optional">
        </div>
    </div>

    <div class="settings-card">
        <div class="section-subtitle">
            <span>Live Credentials</span>
            <span class="badge badge-live">LIVE</span>
        </div>
        <div class="form-group">
            <label for="{{ $gateway }}_public_key_live">Public Key</label>
            <input type="text" id="{{ $gateway }}_public_key_live" name="public_key_live" value="{{ $settings->public_key_live ?? '' }}" placeholder="{{ $gateway === 'paystack' ? 'pk_live_xxxxxxxxxxxxx' : 'FLWPUBK-xxxxxxxxxxxxx' }}">
        </div>
        <div class="form-group">
            <label for="{{ $gateway }}_secret_key_live">Secret Key</label>
            <input type="password" id="{{ $gateway }}_secret_key_live" name="secret_key_live" placeholder="{{ $hasSecretLive ? '••••••••••••••••' : ($gateway === 'paystack' ? 'sk_live_xxxxxxxxxxxxx' : 'FLWSECK-xxxxxxxxxxxxx') }}">
            <div class="form-hint">Leave empty to keep existing key</div>
        </div>
        <div class="form-group">
            <label for="{{ $gateway }}_webhook_secret_live">Webhook Secret</label>
            <input type="text" id="{{ $gateway }}_webhook_secret_live" name="webhook_secret_live" value="{{ $settings->webhook_secret_live ?? '' }}" placeholder="Optional">
        </div>
    </div>

    <div class="settings-card">
        <div class="section-subtitle"><span>Webhook URL</span></div>
        <div class="form-hint">Copy this URL and add it to your {{ $label }} dashboard:</div>
        <div class="webhook-url-wrap">
            <input type="text" class="webhook-url" id="{{ $gateway }}-webhook-url" readonly value="{{ $webhookUrl }}">
            <button type="button" class="webhook-copy-btn" onclick="copyWebhookUrl('{{ $gateway }}-webhook-url', this)">Copy URL</button>
        </div>
    </div>

    <div class="save-section">
        <button type="submit" class="btn-save">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            Save {{ $label }} Settings
        </button>
    </div>
</form>
