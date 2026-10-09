<?php

namespace App\Support;

/**
 * Applies a manager's primary/price colours over a PHP menu template's own palette.
 * Callers pass only colours the manager changed, so untouched menus keep their design.
 */
final class MenuBrandColors
{
    /** Tailwind colour keys that carry each template's accent colour. */
    private const TAILWIND_ACCENT_KEYS = [
        2 => ['primary'],
        3 => ['primary'],
        5 => ['gold'],
        6 => ['primary'],
        7 => ['primary', 'champagne-gold'],
        8 => ['soft-berry'],
        9 => ['brandYellow'],
        10 => ['accent-gold'],
        11 => ['accent'],
        12 => ['medBlue'],
        13 => ['copper', 'amber-glow'],
        14 => ['terracotta'],
        15 => ['neonPink'],
        17 => ['brandGold'],
        18 => ['brandGold'],
    ];

    /** Accents that live in plain CSS rather than the Tailwind config; %s is the primary colour. */
    private const PRIMARY_CSS = [
        1 => ':root{--dark:%s}',
        16 => '.nmc-section-title,.nmc-section-title a{color:%s!important}',
    ];

    /** Templates that already read customization colours themselves. */
    private const SELF_THEMED = [4];

    /** @param  array<string, mixed>  $colors  keys: primary_color, price_color, menu_title_color, category_title_color, description_color */
    public static function apply(int $templateId, string $html, array $colors): string
    {
        if ($colors === [] || in_array($templateId, self::SELF_THEMED, true)) {
            return $html;
        }

        $primary = self::hex($colors['primary_color'] ?? null);
        $price = self::hex($colors['price_color'] ?? null);
        $css = [];

        if ($primary !== null) {
            $keys = self::TAILWIND_ACCENT_KEYS[$templateId] ?? [];
            if ($keys !== []) {
                $html = self::recolorTailwindConfig($html, $keys, $primary);
            }
            if (isset(self::PRIMARY_CSS[$templateId])) {
                $css[] = sprintf(self::PRIMARY_CSS[$templateId], $primary);
            }
            $css[] = ':root{--rm-primary:'.$primary.'}';
        }

        if ($price !== null) {
            $css[] = '.rm-price{color:'.$price.'!important}';
        }

        $itemTitle = self::hex($colors['menu_title_color'] ?? null);
        if ($itemTitle !== null) {
            $css[] = '.rm-item-title{color:'.$itemTitle.'!important}';
        }

        $categoryTitle = self::hex($colors['category_title_color'] ?? null);
        if ($categoryTitle !== null) {
            $css[] = '.rm-category-title,.rm-category-title a{color:'.$categoryTitle.'!important;-webkit-text-fill-color:'.$categoryTitle.'!important}';
        }

        $description = self::hex($colors['description_color'] ?? null);
        if ($description !== null) {
            $css[] = '.rm-description{color:'.$description.'!important}';
        }

        if ($css === []) {
            return $html;
        }

        $style = '<style id="rm-brand-colors">'.implode('', $css).'</style>';
        $headClose = stripos($html, '</head>');

        return $headClose === false
            ? $style.$html
            : substr($html, 0, $headClose).$style.substr($html, $headClose);
    }

    /** @param  list<string>  $keys */
    private static function recolorTailwindConfig(string $html, array $keys, string $color): string
    {
        return (string) preg_replace_callback('/tailwind\.config\s*=.*?<\/script>/s', function (array $m) use ($keys, $color) {
            $block = $m[0];
            foreach ($keys as $key) {
                $block = (string) preg_replace(
                    '/(?<![\w-])(["\']?)'.preg_quote($key, '/').'\1(\s*:\s*)(["\'])#[0-9a-fA-F]{3,8}\3/',
                    '${1}'.$key.'${1}${2}${3}'.$color.'${3}',
                    $block
                );
            }

            return $block;
        }, $html);
    }

    private static function hex(mixed $value): ?string
    {
        $value = trim((string) $value);

        return preg_match('/^#(?:[0-9a-fA-F]{3}|[0-9a-fA-F]{6}|[0-9a-fA-F]{8})$/', $value) === 1 ? $value : null;
    }
}
