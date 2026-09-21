<?php

namespace App\Services;

/**
 * Shared branded HTML wrapper for platform (Resmenu) transactional emails.
 */
class PlatformMailTemplate
{
    public function __construct(private SiteSettingsService $siteSettings) {}

    public function render(string $title, string $bodyHtml): string
    {
        $siteName = e($this->siteSettings->siteName());
        $logoUrl = $this->siteSettings->siteLogoUrl();
        $homeUrl = e(rtrim((string) config('app.url'), '/'));
        $year = date('Y');

        $header = $logoUrl
            ? '<img src="'.e($logoUrl).'" alt="'.$siteName.'" style="max-height:48px;display:block;margin:0 auto;">'
            : '<div style="font-size:22px;font-weight:700;color:#fff;letter-spacing:-0.02em;">'.$siteName.'</div>';

        return '<!DOCTYPE html><html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">'
            .'<title>'.e($title).'</title></head>'
            .'<body style="margin:0;padding:24px;background:#f3f4f6;font-family:Inter,Segoe UI,Helvetica,Arial,sans-serif;">'
            .'<div style="max-width:560px;margin:0 auto;background:#ffffff;border-radius:12px;overflow:hidden;border:1px solid #e5e7eb;">'
            .'<header style="background:#111827;padding:28px 24px;text-align:center;">'.$header.'</header>'
            .'<section style="padding:28px 24px;color:#374151;font-size:15px;line-height:1.6;">'.$bodyHtml.'</section>'
            .'<footer style="background:#f9fafb;padding:18px 24px;text-align:center;color:#9ca3af;font-size:12px;line-height:1.5;border-top:1px solid #e5e7eb;">'
            .'<div style="margin-bottom:6px;"><a href="'.$homeUrl.'" style="color:#6b7280;text-decoration:none;font-weight:600;">'.$siteName.'</a></div>'
            .'<div>&copy; '.$year.' '.$siteName.'. All rights reserved.</div>'
            .'<div style="margin-top:6px;">This is a transactional message from your Resmenu account.</div>'
            .'</footer></div></body></html>';
    }
}
