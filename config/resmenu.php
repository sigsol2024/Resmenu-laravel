<?php

$uploadUrl = rtrim(env('UPLOAD_URL', env('APP_URL', 'http://localhost').'/uploads'), '/');

$uploadRoot = env('UPLOAD_ROOT');
if (is_string($uploadRoot)) {
    $uploadRoot = trim($uploadRoot);
}
if ($uploadRoot === '') {
    $uploadRoot = null;
}
if ($uploadRoot && ! str_starts_with($uploadRoot, '/') && ! preg_match('#^[A-Za-z]:\\\\#', $uploadRoot)) {
    $uploadRoot = base_path($uploadRoot);
}

if (! $uploadRoot) {
    // Keep disk path in sync with UPLOAD_URL (production uses /storage/uploads).
    // Applies to all manager uploads: logos, heroes, sections, categories, menu items, QR previews, etc.
    if (preg_match('#/storage/uploads/?$#', $uploadUrl) || str_contains($uploadUrl, '/storage/uploads/')) {
        $uploadRoot = public_path('storage/uploads');
    } else {
        $uploadRoot = public_path('uploads');
    }
}

return [
    'upload_root' => $uploadRoot,
    'upload_url' => $uploadUrl,
    'canonical_upload_url' => env('CANONICAL_UPLOAD_URL') ? rtrim(env('CANONICAL_UPLOAD_URL'), '/') : null,

    'password_min_length' => (int) env('PASSWORD_MIN_LENGTH', 8),
    'auth_session_idle_seconds' => (int) env('AUTH_SESSION_IDLE_SECONDS', 3600),
    'app_hmac_secret' => env('APP_HMAC_SECRET', ''),
    'payment_encryption_key' => env('PAYMENT_ENCRYPTION_KEY', 'your-32-character-secret-key-here'),
    /** Dedicated CRM token encryption; decrypt falls back to payment key for legacy ciphertext. */
    'crm_encryption_key' => env('CRM_ENCRYPTION_KEY', ''),
    'trust_proxy_headers' => filter_var(env('TRUST_PROXY_HEADERS', false), FILTER_VALIDATE_BOOLEAN),
    /** Admin username promoted on bootstrap migration / ops when set. Never hardcode identity in source. */
    'super_admin_bootstrap_username' => env('SUPER_ADMIN_BOOTSTRAP_USERNAME', ''),

    'max_file_size' => (int) env('MAX_FILE_SIZE', 5 * 1024 * 1024),
    'image_max_bytes' => (int) env('IMAGE_MAX_BYTES', 512000),
    /** Hard reject above this size (default 1MB). */
    'image_upload_max_bytes' => (int) env('IMAGE_UPLOAD_MAX_BYTES', 1048576),
    /** Soft optimize band: keep files already under max; compress larger uploads toward ≤ max. */
    'image_target_min_bytes' => (int) env('IMAGE_TARGET_MIN_BYTES', 300000),
    'image_target_max_bytes' => (int) env('IMAGE_TARGET_MAX_BYTES', 512000),
    'image_max_long_edge' => (int) env('IMAGE_MAX_LONG_EDGE', 1920),
    'image_min_long_edge' => (int) env('IMAGE_MIN_LONG_EDGE', 800),
    'image_jpeg_quality_start' => (int) env('IMAGE_JPEG_QUALITY_START', 85),
    'image_jpeg_quality_floor' => (int) env('IMAGE_JPEG_QUALITY_FLOOR', 72),
    'allowed_image_types' => ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],

    'mail_enabled' => filter_var(env('MAIL_ENABLED', true), FILTER_VALIDATE_BOOLEAN),
    'mail_from_email' => env('MAIL_FROM_ADDRESS', env('MAIL_FROM_EMAIL', 'noreply@resmenu.net')),
    'mail_from_name' => env('MAIL_FROM_NAME', env('APP_NAME', 'Resmenu')),
    'smtp_host' => env('MAIL_HOST', env('SMTP_HOST', '')),
    'smtp_port' => env('MAIL_PORT', env('SMTP_PORT', '465')),
    'smtp_secure' => env('MAIL_ENCRYPTION', env('SMTP_SECURE', 'ssl')),
    'smtp_username' => env('MAIL_USERNAME', env('SMTP_USERNAME', '')),
    'smtp_password' => env('MAIL_PASSWORD', env('SMTP_PASSWORD', '')),
    'mail_php_fallback_enabled' => filter_var(env('MAIL_PHP_FALLBACK_ENABLED', true), FILTER_VALIDATE_BOOLEAN),

    'zeptomail_sendmail_token' => env('ZEPTOMAIL_SENDMAIL_TOKEN', ''),
    'zeptomail_url' => env('ZEPTOMAIL_URL', 'https://api.zeptomail.com/v1.1/email'),
    'zeptomail_from_address' => env('ZEPTOMAIL_FROM_ADDRESS', 'noreply@resmenu.net'),
    'zeptomail_from_name' => env('ZEPTOMAIL_FROM_NAME', env('APP_NAME', 'Resmenu')),
    'zeptomail_reply_to' => env('ZEPTOMAIL_REPLY_TO', 'support@resmenu.net'),
    'zeptomail_timeout_seconds' => (int) env('ZEPTOMAIL_TIMEOUT_SECONDS', 30),

    'recaptcha_site_key' => env('RECAPTCHA_SITE_KEY', ''),
    'recaptcha_secret_key' => env('RECAPTCHA_SECRET_KEY', ''),
    'recaptcha_timeout_seconds' => (int) env('RECAPTCHA_TIMEOUT_SECONDS', 5),

    'reg_otp_limit_per_email' => (int) env('REG_OTP_LIMIT_PER_EMAIL', 3),
    'reg_otp_email_window_seconds' => (int) env('REG_OTP_EMAIL_WINDOW_SECONDS', 3600),
    'reg_otp_limit_per_ip' => (int) env('REG_OTP_LIMIT_PER_IP', 5),
    'reg_otp_ip_window_seconds' => (int) env('REG_OTP_IP_WINDOW_SECONDS', 3600),
    'reg_otp_limit_global' => (int) env('REG_OTP_LIMIT_GLOBAL', 8),
    'reg_otp_global_window_seconds' => (int) env('REG_OTP_GLOBAL_WINDOW_SECONDS', 60),
    'reg_otp_cooldown_email_seconds' => (int) env('REG_OTP_COOLDOWN_EMAIL_SECONDS', 60),
    'reg_otp_cooldown_ip_seconds' => (int) env('REG_OTP_COOLDOWN_IP_SECONDS', 60),
    'reg_otp_ttl_minutes' => (int) env('REG_OTP_TTL_MINUTES', 10),
    'reg_otp_strict_local_part' => filter_var(env('REG_OTP_STRICT_LOCAL_PART', false), FILTER_VALIDATE_BOOLEAN),
    'reg_otp_bounce_webhook_secret' => env('REG_OTP_BOUNCE_WEBHOOK_SECRET', ''),

    'email_verify_ttl_minutes' => (int) env('EMAIL_VERIFY_TTL_MINUTES', 60),
    'email_verify_limit_per_email' => (int) env('EMAIL_VERIFY_LIMIT_PER_EMAIL', 5),
    'email_verify_limit_per_ip' => (int) env('EMAIL_VERIFY_LIMIT_PER_IP', 10),
    'email_verify_window_seconds' => (int) env('EMAIL_VERIFY_WINDOW_SECONDS', 3600),

    'marketing_consent_text_version' => env('MARKETING_CONSENT_TEXT_VERSION', 'v2'),
    'marketing_consent_text' => env(
        'MARKETING_CONSENT_TEXT',
        "Yes, I'd like to receive product updates, news, offers and other marketing emails from Resmenu."
    ),

    // Signed welcome-email → marketing subscribe confirm link
    'marketing_subscribe_ttl_days' => (int) env('MARKETING_SUBSCRIBE_TTL_DAYS', 7),
    'marketing_subscribe_limit_per_email' => (int) env('MARKETING_SUBSCRIBE_LIMIT_PER_EMAIL', 10),
    'marketing_subscribe_limit_per_ip' => (int) env('MARKETING_SUBSCRIBE_LIMIT_PER_IP', 20),
    'marketing_subscribe_window_seconds' => (int) env('MARKETING_SUBSCRIBE_WINDOW_SECONDS', 3600),

    'rate_limit_dir' => env('RATE_LIMIT_DIR', ''),

    'subscription_payment_pending_hours' => (int) env('SUBSCRIPTION_PAYMENT_PENDING_HOURS', 6),

    // Production: comma list, e.g. https://resmenu.net,https://www.resmenu.net — never rely on *
    'cors_allowed_origins' => env('CORS_ALLOWED_ORIGINS', ''),

    'crm_lead_dedup_minutes' => (int) env('CRM_LEAD_DEDUP_MINUTES', 15),
    'crm_lead_limit_per_email' => (int) env('CRM_LEAD_LIMIT_PER_EMAIL', 10),
    'crm_lead_limit_per_ip' => (int) env('CRM_LEAD_LIMIT_PER_IP', 30),
    'crm_lead_limit_window_seconds' => (int) env('CRM_LEAD_LIMIT_WINDOW_SECONDS', 3600),
];
