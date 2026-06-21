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

function t6_item_anchor(array $item): string
{
    if (! empty($item['slug'])) {
        return 'item-'.preg_replace('/[^a-z0-9-]/', '', strtolower((string) $item['slug']));
    }

    return 'item-'.(int) ($item['id'] ?? 0);
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

/** @return list<array{label: string, url: string, icon: string}> */
function t6_connect_links(array $restaurant): array
{
    $links = [];
    if (! empty($restaurant['instagram_url'])) {
        $links[] = ['label' => 'Instagram', 'url' => (string) $restaurant['instagram_url'], 'icon' => 'instagram'];
    }
    if (! empty($restaurant['facebook_url'])) {
        $links[] = ['label' => 'Facebook', 'url' => (string) $restaurant['facebook_url'], 'icon' => 'facebook'];
    }
    if (! empty($restaurant['twitter_url'])) {
        $links[] = ['label' => 'Twitter (X)', 'url' => (string) $restaurant['twitter_url'], 'icon' => 'twitter'];
    }
    if (! empty($restaurant['whatsapp_link'])) {
        $links[] = ['label' => 'WhatsApp', 'url' => (string) $restaurant['whatsapp_link'], 'icon' => 'whatsapp'];
    }
    if (! empty($restaurant['website'])) {
        $links[] = ['label' => 'Website', 'url' => (string) $restaurant['website'], 'icon' => 'website'];
    }

    return $links;
}

function t6_connect_icon(string $icon): string
{
    $svgOpen = '<svg class="w-3.5 h-3.5 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">';

    return match ($icon) {
        'instagram' => $svgOpen.'<path d="M7.8 2h8.4C19.4 2 22 4.6 22 7.8v8.4a5.8 5.8 0 0 1-5.8 5.8H7.8C4.6 22 2 19.4 2 16.2V7.8A5.8 5.8 0 0 1 7.8 2m-.2 2A3.6 3.6 0 0 0 4 7.6v8.8C4 18.39 5.61 20 7.6 20h8.8a3.6 3.6 0 0 0 3.6-3.6V7.6C20 5.61 18.39 4 16.4 4H7.6m9.65 1.5a1.25 1.25 0 0 1 1.25 1.25A1.25 1.25 0 0 1 17.25 8 1.25 1.25 0 0 1 16 6.75a1.25 1.25 0 0 1 1.25-1.25M12 7a5 5 0 0 1 5 5 5 5 0 0 1-5 5 5 5 0 0 1-5-5 5 5 0 0 1 5-5m0 2a3 3 0 0 0-3 3 3 3 0 0 0 3 3 3 3 0 0 0 3-3 3 3 0 0 0-3-3z"/></svg>',
        'facebook' => $svgOpen.'<path d="M12 2.04c-5.5 0-10 4.49-10 10.02 0 5 3.66 9.15 8.44 9.9v-7H7.9v-2.9h2.54V9.85c0-2.51 1.49-3.89 3.78-3.89 1.09 0 2.23.19 2.23.19v2.47h-1.26c-1.24 0-1.63.77-1.63 1.56v1.88h2.78l-.45 2.9h-2.33v7a10 10 0 0 0 8.44-9.9c0-5.53-4.5-10.02-10-10.02z"/></svg>',
        'twitter' => $svgOpen.'<path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>',
        'whatsapp' => $svgOpen.'<path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.435 9.884-9.884 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/></svg>',
        default => '<span class="material-symbols-outlined text-base shrink-0">language</span>',
    };
}

/** @return array{class: string, isLarge: bool} */
function t6_section_category_grid_class(int $index): array
{
    $slot = $index % 7;

    $mobile = match ($slot) {
        0 => 'col-span-1 row-span-2 min-h-[270px]',
        1, 2 => 'col-span-1 row-span-1 min-h-[130px]',
        3 => 'col-span-2 row-span-1 min-h-[140px]',
        4, 5 => 'col-span-1 row-span-1 min-h-[130px]',
        6 => 'col-span-1 row-span-2 min-h-[270px]',
        default => 'col-span-1 row-span-1 min-h-[130px]',
    };

    $desktop = match ($slot) {
        0 => 'md:col-span-2 md:row-span-2 md:min-h-[300px]',
        1 => 'md:col-span-2 md:row-span-1 md:min-h-[300px]',
        2, 3 => 'md:col-span-1 md:row-span-1 md:min-h-[300px]',
        4 => 'md:col-span-2 md:row-span-1 md:min-h-[240px]',
        5, 6 => 'md:col-span-1 md:row-span-1 md:min-h-[240px]',
        default => 'md:col-span-1 md:min-h-[240px]',
    };

    return [
        'class' => $mobile.' '.$desktop,
        'isLarge' => in_array($slot, [0, 3], true),
    ];
}

function t6_rating_display(array $restaurant): string
{
    $rating = number_format((float) ($restaurant['google_rating'] ?? 4.5), 1);

    return $rating.' Rating';
}
