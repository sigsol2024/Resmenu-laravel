<?php

/**
 * Resolve public asset URLs the same way as the original Resmenu site (/assets/…),
 * with fallbacks for files only published under /legacy/.
 */
function resmenu_public_asset(string $path): string
{
    $path = ltrim(str_replace('\\', '/', $path), '/');

    if (preg_match('#^css/pages/#', $path)) {
        foreach (['legacy/'.$path, 'assets/'.$path] as $rel) {
            if (is_file(public_path($rel))) {
                return asset($rel);
            }
        }

        return asset('legacy/'.$path);
    }

    if (preg_match('#^css/[^/]+\.css$#', $path)) {
        $file = basename($path);
        if (is_file(public_path('assets/css/'.$file))) {
            return asset('assets/css/'.$file);
        }
        if (is_file(public_path('legacy/css/'.$file))) {
            return asset('legacy/css/'.$file);
        }

        return asset('assets/css/'.$file);
    }

    if (is_file(public_path('assets/'.$path))) {
        return asset('assets/'.$path);
    }
    if (is_file(public_path('legacy/assets/'.$path))) {
        return asset('legacy/assets/'.$path);
    }

    return asset('assets/'.$path);
}

/** Inline page CSS (legacy manager pages embed styles in the document). */
function resmenu_inline_page_css(string $pageCssPath): string
{
    $pageCssPath = ltrim(str_replace('\\', '/', $pageCssPath), '/');

    foreach ([
        public_path('legacy/css/pages/'.$pageCssPath),
        public_path('assets/css/pages/'.$pageCssPath),
    ] as $full) {
        if (is_file($full)) {
            return (string) file_get_contents($full);
        }
    }

    return '';
}
