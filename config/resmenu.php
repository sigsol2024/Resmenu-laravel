<?php

$uploadRoot = env('UPLOAD_ROOT');
if ($uploadRoot && ! str_starts_with($uploadRoot, '/') && ! preg_match('#^[A-Za-z]:\\\\#', $uploadRoot)) {
    $uploadRoot = base_path($uploadRoot);
}

return [
    'upload_root' => $uploadRoot ?: public_path('storage/uploads'),
    'upload_url' => rtrim(env('UPLOAD_URL', env('APP_URL', 'http://localhost').'/storage/uploads'), '/'),
    'canonical_upload_url' => env('CANONICAL_UPLOAD_URL') ? rtrim(env('CANONICAL_UPLOAD_URL'), '/') : null,

    'password_min_length' => (int) env('PASSWORD_MIN_LENGTH', 8),
    'auth_session_idle_seconds' => (int) env('AUTH_SESSION_IDLE_SECONDS', 3600),
    'app_hmac_secret' => env('APP_HMAC_SECRET', ''),
    'payment_encryption_key' => env('PAYMENT_ENCRYPTION_KEY', 'your-32-character-secret-key-here'),
    'trust_proxy_headers' => filter_var(env('TRUST_PROXY_HEADERS', false), FILTER_VALIDATE_BOOLEAN),

    'max_file_size' => (int) env('MAX_FILE_SIZE', 5 * 1024 * 1024),
    'image_max_bytes' => (int) env('IMAGE_MAX_BYTES', 512000),
    'image_upload_max_bytes' => (int) env('IMAGE_UPLOAD_MAX_BYTES', 1048576),
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

    'rate_limit_dir' => env('RATE_LIMIT_DIR', ''),
];
