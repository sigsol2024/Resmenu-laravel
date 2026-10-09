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
 * Footer social icons from the restaurant's profile links. Icons inherit the surrounding text colour.
 * Only http(s) links are rendered; a bare WhatsApp number becomes a wa.me link.
 */
if (! function_exists('resmenu_social_links')) {
    function resmenu_social_links(array $restaurant, string $class = ''): string
    {
        $icons = [
            'instagram_url' => ['Instagram', 'M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z'],
            'facebook_url' => ['Facebook', 'M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z'],
            'twitter_url' => ['X (Twitter)', 'M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z'],
            'whatsapp_link' => ['WhatsApp', 'M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z'],
        ];

        $links = '';
        foreach ($icons as $field => [$label, $path]) {
            $url = trim((string) ($restaurant[$field] ?? ''));
            if ($url === '') {
                continue;
            }
            if ($field === 'whatsapp_link' && preg_match('/^\+?[\d\s()-]{7,}$/', $url)) {
                $url = 'https://wa.me/'.preg_replace('/\D/', '', $url);
            }
            if (! preg_match('#^https?://#i', $url)) {
                continue;
            }
            $links .= '<a href="'.e_menu($url).'" target="_blank" rel="noopener" aria-label="'.e_menu($label).'" title="'.e_menu($label).'"'
                .' style="display:inline-flex;color:inherit;opacity:.85;transition:opacity .2s" onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=.85">'
                .'<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="'.$path.'"/></svg></a>';
        }

        if ($links === '') {
            return '';
        }

        return '<div class="rm-social '.e_menu($class).'" style="display:flex;flex-wrap:wrap;justify-content:center;align-items:center;gap:16px">'.$links.'</div>';
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
