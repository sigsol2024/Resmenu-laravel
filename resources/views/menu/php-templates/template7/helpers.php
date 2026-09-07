<?php
/**
 * Template 7 — Ember Noir helper functions.
 * All helpers are prefixed t7_ to avoid collisions with other templates.
 */

/** HTML-escape a value safely. */
function t7_esc(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

/** Format a price using the platform's formatPrice() utility. */
function t7_price($price, string $symbol = '₦'): string
{
    return formatPrice($price, $symbol);
}

/** Build the URL for a section page. */
function t7_section_url(string $fullMenuUrl, string $sectionSlug): string
{
    return rtrim($fullMenuUrl, '/') . '/' . ltrim($sectionSlug, '/');
}

/** Return the full URL for a section's cover image, or null if none. */
function t7_section_image(string $uploadBaseUrl, array $section): ?string
{
    if (empty($section['image'])) {
        return null;
    }
    return rtrim($uploadBaseUrl, '/') . '/sections/' . ltrim((string) $section['image'], '/');
}

/** Return the full URL for a category image, or null if none. */
function t7_category_image(string $uploadBaseUrl, array $category): ?string
{
    if (empty($category['image'])) {
        return null;
    }
    return rtrim($uploadBaseUrl, '/') . '/categories/' . ltrim((string) $category['image'], '/');
}

/** Return the full URL for a menu-item image, or null if none. */
function t7_item_image(string $uploadBaseUrl, array $item): ?string
{
    if (empty($item['image'])) {
        return null;
    }
    return rtrim($uploadBaseUrl, '/') . '/menu-items/' . ltrim((string) $item['image'], '/');
}

/** Return the restaurant logo URL, or null in preview mode or if no logo set. */
function t7_logo_url(string $uploadBaseUrl, array $restaurant): ?string
{
    if (! empty($GLOBALS['t7_is_template_preview'] ?? false)) {
        return null;
    }
    if (empty($restaurant['logo'])) {
        return null;
    }
    return rtrim($uploadBaseUrl, '/') . '/logos/' . ltrim((string) $restaurant['logo'], '/');
}

/**
 * Return a URL for a pattern asset under the template's public folder.
 *
 * Available files:
 *   bg_black.png   — dot-grid dark pattern
 *   binding_dark.png — bookbinding weave
 */
function t7_pattern_url(string $templateAssetBaseUrl, string $file = 'bg_black.png'): string
{
    return rtrim($templateAssetBaseUrl, '/') . '/patterns/' . $file;
}

/**
 * Return a grid span class pair for section cards in the home editorial grid.
 * Produces an asymmetric layout similar to the REFERENCES/landing.html design.
 *
 * @return array{main: string, counter: string, featured: bool}
 */
function t7_home_grid_slot(int $index, int $total): array
{
    // For 1-3 sections use simpler layouts
    if ($total <= 1) {
        return ['main' => 'lg:col-span-12', 'counter' => '', 'featured' => true];
    }
    if ($total <= 3) {
        $spans = ['lg:col-span-12', 'lg:col-span-6', 'lg:col-span-6'];
        $s = $spans[$index] ?? 'lg:col-span-6';
        return ['main' => $s, 'counter' => '', 'featured' => $index === 0];
    }

    // For 4+ sections, editorial asymmetric pattern repeating every 3 slots
    $slot = $index % 3;
    return match ($slot) {
        0 => ['main' => 'lg:col-span-7', 'counter' => sprintf('%02d', $index + 1), 'featured' => true],
        1 => ['main' => 'lg:col-span-5', 'counter' => sprintf('%02d', $index + 1), 'featured' => false],
        default => ['main' => 'lg:col-span-5', 'counter' => sprintf('%02d', $index + 1), 'featured' => false],
    };
}
