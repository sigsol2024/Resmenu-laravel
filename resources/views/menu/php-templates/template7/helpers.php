<?php
/**
 * Template 7 helpers — DESIGN_1 visual system, dynamic restaurant data.
 * All helpers are prefixed t7_ to avoid collisions with other templates.
 */

function t7_esc(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function t7_price($price, string $symbol = '₦'): string
{
    return formatPrice($price, $symbol);
}

function t7_section_url(string $fullMenuUrl, string $sectionSlug): string
{
    return rtrim($fullMenuUrl, '/') . '/' . ltrim($sectionSlug, '/');
}

function t7_section_image(string $uploadBaseUrl, array $section): ?string
{
    if (empty($section['image'])) {
        return null;
    }

    return rtrim($uploadBaseUrl, '/') . '/sections/' . ltrim((string) $section['image'], '/');
}

function t7_category_image(string $uploadBaseUrl, array $category): ?string
{
    if (empty($category['image'])) {
        return null;
    }

    return rtrim($uploadBaseUrl, '/') . '/categories/' . ltrim((string) $category['image'], '/');
}

function t7_item_image(string $uploadBaseUrl, array $item): ?string
{
    if (empty($item['image'])) {
        return null;
    }

    return rtrim($uploadBaseUrl, '/') . '/menu-items/' . ltrim((string) $item['image'], '/');
}

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

function t7_pattern_url(string $templateAssetBaseUrl, string $file = 'bg_black.png'): string
{
    return rtrim($templateAssetBaseUrl, '/') . '/patterns/' . $file;
}

/**
 * DESIGN_1 section shell themes by slug (generic — not restaurant-specific).
 *
 * @return array{shell:string,tone:string,card:string,price:string,photo:bool,label:string}
 */
function t7_section_theme(string $slug): array
{
    $slug = strtolower(trim($slug));

    return match ($slug) {
        'breakfast', 'starters', 'world', 'sides' => [
            'shell' => 'section-pattern section-pattern--light',
            'tone' => 'light',
            'card' => 'card-white',
            'price' => 'text-burgundy-deep',
            'photo' => false,
            'label' => 'light',
        ],
        'wraps', 'grill', 'vcp-specials', 'dessert' => [
            'shell' => 'section-pattern section-pattern--dark',
            'tone' => 'dark',
            'card' => 'card-dark',
            'price' => 'text-champagne-gold',
            'photo' => in_array($slug, ['wraps', 'grill', 'vcp-specials'], true),
            'label' => 'dark',
        ],
        'entree' => [
            'shell' => 'section-entree',
            'tone' => 'entree',
            'card' => 'entree-panel',
            'price' => 'entree-title',
            'photo' => false,
            'label' => 'entree',
        ],
        'national-menu' => [
            'shell' => 'section-pattern section-pattern--dark section-national',
            'tone' => 'national',
            'card' => 'card-dark',
            'price' => 'text-champagne-gold',
            'photo' => false,
            'label' => 'national',
        ],
        'drinks' => [
            'shell' => 'section-pattern section-pattern--white',
            'tone' => 'drinks',
            'card' => 'card-white',
            'price' => 'text-burgundy-deep',
            'photo' => false,
            'label' => 'drinks',
        ],
        default => [
            'shell' => 'section-pattern section-pattern--dark',
            'tone' => 'dark',
            'card' => 'card-dark',
            'price' => 'text-champagne-gold',
            'photo' => false,
            'label' => 'dark',
        ],
    };
}

/** Map drink category slug → DESIGN_1 drink-cat theme. */
function t7_drink_cat_theme(string $categorySlug): string
{
    $slug = strtolower(trim($categorySlug));

    return match (true) {
        str_contains($slug, 'hot') || str_contains($slug, 'tea') || str_contains($slug, 'coffee') => 'drink-cat--warm',
        str_contains($slug, 'mocktail') => 'drink-cat--fresh',
        str_contains($slug, 'smoothie') => 'drink-cat--smoothie',
        str_contains($slug, 'juice') => 'drink-cat--juice',
        str_contains($slug, 'soft') => 'drink-cat--soft',
        default => 'drink-cat--dark',
    };
}

/**
 * Parse option blocks from a DESIGN_1-style multi-line description.
 * Returns list of ['label'=>string,'choose'=>string,'items'=>string[]] or empty.
 *
 * @return list<array{label:string,choose:string,items:list<string>}>
 */
function t7_parse_option_blocks(string $description): array
{
    $description = trim(str_replace(["\r\n", "\r"], "\n", $description));
    if ($description === '') {
        return [];
    }

    $blocks = preg_split("/\n{2,}/", $description) ?: [];
    $out = [];
    foreach ($blocks as $block) {
        $lines = array_values(array_filter(array_map('trim', explode("\n", $block)), static fn ($l) => $l !== ''));
        if ($lines === []) {
            continue;
        }
        $header = array_shift($lines);
        $label = $header;
        $choose = '';
        if (preg_match('/^(.+?)\s*\((Choose\s+\d+)\)\s*:\s*(.*)$/i', $header, $m)) {
            $label = trim($m[1]);
            $choose = trim($m[2]);
            if ($m[3] !== '') {
                array_unshift($lines, trim($m[3]));
            }
        } elseif (str_contains($header, ':')) {
            [$label, $rest] = array_map('trim', explode(':', $header, 2));
            if ($rest !== '') {
                array_unshift($lines, $rest);
            }
        }
        $items = [];
        foreach ($lines as $line) {
            $line = ltrim($line, "•-\t ");
            if (str_contains($line, ',')) {
                foreach (array_map('trim', explode(',', $line)) as $piece) {
                    if ($piece !== '') {
                        $items[] = $piece;
                    }
                }
            } elseif ($line !== '') {
                $items[] = $line;
            }
        }
        if ($items !== [] || $label !== '') {
            $out[] = ['label' => $label, 'choose' => $choose, 'items' => $items];
        }
    }

    return $out;
}

/**
 * Landing grid slot — mirrors landing.html asymmetric rhythm.
 *
 * @return array{main: string, counter: string, featured: bool}
 */
function t7_home_grid_slot(int $index, int $total): array
{
    if ($total <= 1) {
        return ['main' => 'lg:col-span-12', 'counter' => '', 'featured' => true];
    }
    if ($total <= 3) {
        $spans = ['lg:col-span-12', 'lg:col-span-6', 'lg:col-span-6'];

        return ['main' => $spans[$index] ?? 'lg:col-span-6', 'counter' => '', 'featured' => $index === 0];
    }

    $slot = $index % 3;

    return match ($slot) {
        0 => ['main' => 'lg:col-span-7', 'counter' => sprintf('%02d', $index + 1), 'featured' => true],
        1 => ['main' => 'lg:col-span-5', 'counter' => sprintf('%02d', $index + 1), 'featured' => false],
        default => ['main' => 'lg:col-span-5', 'counter' => sprintf('%02d', $index + 1), 'featured' => false],
    };
}

/** Landing tile accent by section slug (DESIGN_1 / landing.html cues). */
function t7_landing_tile_accent(string $slug): string
{
    $slug = strtolower(trim($slug));

    return match ($slug) {
        'national-menu', 'vcp-specials' => 'tile-burgundy',
        'drinks' => 'tile-drinks',
        'grill' => 'tile-grill',
        'entree' => 'tile-entree',
        'world' => 'tile-world',
        default => 'tile-default',
    };
}
