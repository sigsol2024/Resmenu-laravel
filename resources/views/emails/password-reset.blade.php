<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reset your password</title>
</head>
<body style="margin:0;padding:24px;background:#f8f5f5;font-family:Inter,sans-serif;">
<div style="max-width:560px;margin:0 auto;background:#fff;border-radius:12px;padding:32px;border:1px solid #e5e7eb;">
    <h1 style="margin:0 0 16px;font-size:22px;color:#111827;">Reset your password</h1>
    <p style="color:#374151;line-height:1.6;">Hello {{ $name }},</p>
    <p style="color:#374151;line-height:1.6;">We received a request to reset your Resmenu manager password. Click the button below to choose a new password. This link expires in one hour.</p>
    <p style="margin:24px 0;">
        <a href="{{ $resetUrl }}" style="display:inline-block;background:#111827;color:#fff;text-decoration:none;padding:12px 24px;border-radius:8px;font-weight:600;">Reset password</a>
    </p>
    <p style="color:#6b7280;font-size:13px;line-height:1.5;">If you did not request this, you can ignore this email.</p>
</div>
</body>
</html>
