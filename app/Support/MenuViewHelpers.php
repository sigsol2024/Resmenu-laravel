<?php

namespace App\Support;

class MenuViewHelpers
{
    /** @param array<string, mixed> $context */
    public static function register(array $context = []): void
    {
        $helpers = __DIR__.'/legacy_menu_helpers.php';
        if (is_file($helpers)) {
            require_once $helpers;
        }

        $icons = __DIR__.'/legacy_resmenu_icons.php';
        if (is_file($icons)) {
            require_once $icons;
        }

        if (! defined('SITE_URL')) {
            define('SITE_URL', rtrim((string) config('app.url'), '/'));
        }

        if (! defined('UPLOAD_URL')) {
            $uploadUrl = $context['uploadBaseUrl']
                ?? config('resmenu.canonical_upload_url')
                ?? config('resmenu.upload_url');
            define('UPLOAD_URL', rtrim((string) $uploadUrl, '/'));
        }

        if (! defined('UPLOAD_PATH')) {
            define('UPLOAD_PATH', rtrim((string) config('resmenu.upload_root'), '/\\'));
        }
    }
}
