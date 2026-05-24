<?php

namespace App\Support;

class MenuViewHelpers
{
    public static function register(): void
    {
        if (! defined('SITE_URL')) {
            define('SITE_URL', rtrim((string) config('app.url'), '/'));
        }

        if (! function_exists('formatPrice')) {
            function formatPrice($price, $currency = '₦'): string
            {
                return $currency.number_format((float) $price, 0);
            }
        }

        if (! function_exists('getTemplateAssetBaseUrl')) {
            function getTemplateAssetBaseUrl($templateId): string
            {
                $id = max(1, (int) $templateId);

                return rtrim(config('app.url'), '/').'/templates/template'.$id;
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
    }
}
