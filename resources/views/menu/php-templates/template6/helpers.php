<?php

function t6_price($price, $symbol = '₦'): string
{
    return formatPrice($price, $symbol);
}

function t6_platform_base(?string $fullMenuUrl): string
{
    if (empty($fullMenuUrl)) {
        return defined('SITE_URL') ? rtrim((string) SITE_URL, '/') : '';
    }

    return preg_replace('#/restaurant/[^/]+.*$#', '', $fullMenuUrl) ?: $fullMenuUrl;
}

function t6_section_url(string $fullMenuUrl, string $sectionSlug): string
{
    return rtrim($fullMenuUrl, '/').'/'.ltrim($sectionSlug, '/');
}

function t6_category_url(string $fullMenuUrl, string $sectionSlug, string $categorySlug): string
{
    return t6_section_url($fullMenuUrl, $sectionSlug).'/'.ltrim($categorySlug, '/');
}

function t6_section_image(string $uploadBaseUrl, array $section): ?string
{
    if (empty($section['image'])) {
        return null;
    }

    return rtrim($uploadBaseUrl, '/').'/sections/'.ltrim((string) $section['image'], '/');
}

function t6_category_image(string $uploadBaseUrl, array $category): ?string
{
    if (empty($category['image'])) {
        return null;
    }

    return rtrim($uploadBaseUrl, '/').'/categories/'.ltrim((string) $category['image'], '/');
}

function t6_item_image(string $uploadBaseUrl, array $item): ?string
{
    if (empty($item['image'])) {
        return null;
    }

    return rtrim($uploadBaseUrl, '/').'/menu-items/'.ltrim((string) $item['image'], '/');
}

function t6_logo_url(string $uploadBaseUrl, array $restaurant): ?string
{
    if (! empty($GLOBALS['t6_is_template_preview'] ?? false)) {
        return null;
    }
    if (empty($restaurant['logo'])) {
        return null;
    }

    return rtrim($uploadBaseUrl, '/').'/logos/'.ltrim((string) $restaurant['logo'], '/');
}

function t6_esc(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

/** Lusso Template 6 design accent — always gold, not restaurant customization. */
function t6_design_primary(): string
{
    return '#f0be78';
}

function t6_design_on_primary(): string
{
    return '#452b00';
}

function t6_map_embed_url(array $restaurant): ?string
{
    $lat = $restaurant['map_latitude'] ?? null;
    $lng = $restaurant['map_longitude'] ?? null;
    if ($lat !== null && $lat !== '' && $lng !== null && $lng !== '') {
        $query = (string) $lat.','.(string) $lng;
    } else {
        $query = trim((string) ($restaurant['address'] ?? ''));
        if ($query === '') {
            return null;
        }
    }

    return 'https://maps.google.com/maps?q='.rawurlencode($query).'&z=15&output=embed';
}

function t6_directions_url(array $restaurant): ?string
{
    $lat = $restaurant['map_latitude'] ?? null;
    $lng = $restaurant['map_longitude'] ?? null;
    if ($lat !== null && $lat !== '' && $lng !== null && $lng !== '') {
        return 'https://www.google.com/maps/dir/?api=1&destination='.rawurlencode((string) $lat.','.(string) $lng);
    }
    $address = trim((string) ($restaurant['address'] ?? ''));

    return $address !== ''
        ? 'https://www.google.com/maps/dir/?api=1&destination='.rawurlencode($address)
        : null;
}

function t6_has_contact_info(array $restaurant): bool
{
    return trim((string) ($restaurant['phone'] ?? '')) !== ''
        || trim((string) ($restaurant['email'] ?? '')) !== ''
        || trim((string) ($restaurant['address'] ?? '')) !== '';
}

function t6_rating_display(array $restaurant): string
{
    $rating = number_format((float) ($restaurant['google_rating'] ?? 4.5), 1);

    return $rating.' Rating';
}
