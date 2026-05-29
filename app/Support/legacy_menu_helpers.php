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
