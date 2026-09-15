<?php

/**
 * Global helpers for legacy menu PHP templates (ported from Resmenu/includes/functions.php).
 * Loaded via Composer autoload "files" and MenuViewHelpers::register().
 */

if (! function_exists('formatPrice')) {
    function formatPrice($price, $currency = '₦'): string
    {
        $p = (float) $price;
        if ($p == 0.0) {
            return '';
        }
        $str = number_format($p, 2, '.', ',');
        if (str_ends_with($str, '.00')) {
            $str = substr($str, 0, -3);
        }

        return $currency.$str;
    }
}

if (! function_exists('getTemplateAssetBaseUrl')) {
    function getTemplateAssetBaseUrl($templateId): string
    {
        $id = max(1, (int) $templateId);

        return rtrim((string) config('app.url'), '/').'/templates/template'.$id;
    }
}

if (! function_exists('templateSupportsOrdering')) {
    function templateSupportsOrdering($templateId): bool
    {
        return true;
    }
}

if (! function_exists('e_menu')) {
    function e_menu($value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

/**
 * Resolve upload/media URLs for menu templates.
 * Nested paths (demo preview_images) use {base}/{path}; basenames use {base}/{kind}/{file}.
 */
if (! function_exists('resmenu_media_url')) {
    function resmenu_media_url(?string $base, string $kind, ?string $file): string
    {
        if ($file === null || trim((string) $file) === '') {
            return '';
        }

        $file = (string) $file;
        if (preg_match('#^https?://#i', $file)) {
            return $file;
        }

        $base = rtrim((string) $base, '/');
        if (str_starts_with($file, '/')) {
            return rtrim((string) config('app.url'), '/').$file;
        }

        if (str_contains($file, '/')) {
            return $base.'/'.ltrim($file, '/');
        }

        return $base.'/'.trim($kind, '/').'/'.ltrim($file, '/');
    }
}

/**
 * Public logo URL only when the file exists on disk (avoids broken img + hidden name).
 */
if (! function_exists('resmenu_logo_url')) {
    function resmenu_logo_url(?string $base, ?string $logoFilename): ?string
    {
        if ($logoFilename === null || trim((string) $logoFilename) === '') {
            return null;
        }

        try {
            /** @var \App\Services\UploadService $uploads */
            $uploads = app(\App\Services\UploadService::class);
            if (! $uploads->logoFileExists($logoFilename)) {
                return null;
            }
        } catch (\Throwable) {
            // Cannot verify the file — do not claim a usable logo (show name instead).
            return null;
        }

        $url = resmenu_media_url($base, 'logos', $logoFilename);

        return $url !== '' ? $url : null;
    }
}
