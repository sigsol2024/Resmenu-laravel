<?php

namespace App\Support;

/**
 * Ensures menu PHP templates receive legacy-shaped arrays with all expected keys defined.
 */
class LegacyMenuViewData
{
    /** @var array<string, mixed> */
    private const RESTAURANT_DEFAULTS = [
        'id' => 0,
        'name' => '',
        'slug' => '',
        'logo' => null,
        'hero_image' => null,
        'hero_image_url' => null,
        'description' => null,
        'phone' => null,
        'email' => null,
        'address' => null,
        'website' => null,
        'whatsapp_link' => null,
        'instagram_url' => null,
        'facebook_url' => null,
        'twitter_url' => null,
        'footer_content' => null,
        'opening_hours' => null,
        'map_latitude' => null,
        'map_longitude' => null,
        'google_rating' => 4.5,
        'rating_source' => 'Google',
        'template_id' => 1,
        'enable_food_ordering' => 1,
        'enable_table_reservations' => 1,
        'header_menu_items' => null,
        'manager_email' => null,
        'is_active' => 1,
        'available_items_count' => 0,
        'unavailable_items_count' => 0,
        'subscription_id' => null,
    ];

    /** @var array<string, mixed> */
    private const SECTION_DEFAULTS = [
        'id' => 0,
        'name' => '',
        'slug' => '',
        'display_order' => 0,
        'is_active' => 1,
        'image' => null,
        'categories' => [],
    ];

    /** @var array<string, mixed> */
    private const CATEGORY_DEFAULTS = [
        'id' => 0,
        'name' => '',
        'slug' => '',
        'display_order' => 0,
        'is_active' => 1,
        'image' => null,
        'menu_items' => [],
    ];

    /** @var array<string, mixed> */
    private const MENU_ITEM_DEFAULTS = [
        'id' => 0,
        'name' => '',
        'description' => '',
        'price' => 0,
        'image' => null,
        'is_available' => 1,
        'display_order' => 0,
    ];

    /** @param array<string, mixed> $viewData */
    public static function normalize(array $viewData): array
    {
        $uploadBaseUrl = rtrim((string) ($viewData['uploadBaseUrl'] ?? config('resmenu.upload_url')), '/');

        if (isset($viewData['restaurant']) && is_array($viewData['restaurant'])) {
            $viewData['restaurant'] = self::normalizeRestaurant($viewData['restaurant'], $uploadBaseUrl);
        }

        if (isset($viewData['sections']) && is_array($viewData['sections'])) {
            $viewData['sections'] = self::normalizeSections($viewData['sections']);
        }

        return $viewData;
    }

    /** @param array<string, mixed> $restaurant */
    public static function normalizeRestaurant(array $restaurant, ?string $uploadBaseUrl = null): array
    {
        $data = array_merge(self::RESTAURANT_DEFAULTS, $restaurant);

        if (empty($data['hero_image_url']) && ! empty($data['hero_image']) && $uploadBaseUrl) {
            $data['hero_image_url'] = $uploadBaseUrl.'/heroes/'.ltrim((string) $data['hero_image'], '/');
        }

        if ($data['google_rating'] === null || $data['google_rating'] === '') {
            $data['google_rating'] = 4.5;
        }

        if ($data['rating_source'] === null || $data['rating_source'] === '') {
            $data['rating_source'] = 'Google';
        }

        return $data;
    }

    /** @param list<array<string, mixed>> $sections */
    public static function normalizeSections(array $sections): array
    {
        return array_map(static function (array $section): array {
            $section = array_merge(self::SECTION_DEFAULTS, $section);
            if (isset($section['categories']) && is_array($section['categories'])) {
                $section['categories'] = self::normalizeCategories($section['categories']);
            } else {
                $section['categories'] = [];
            }

            return $section;
        }, $sections);
    }

    /** @param list<array<string, mixed>> $categories */
    public static function normalizeCategories(array $categories): array
    {
        return array_map(static function (array $category): array {
            $category = array_merge(self::CATEGORY_DEFAULTS, $category);
            if (isset($category['menu_items']) && is_array($category['menu_items'])) {
                $category['menu_items'] = self::normalizeMenuItems($category['menu_items']);
            } else {
                $category['menu_items'] = [];
            }

            return $category;
        }, $categories);
    }

    /** @param list<array<string, mixed>> $items */
    public static function normalizeMenuItems(array $items): array
    {
        return array_map(static fn (array $item): array => array_merge(self::MENU_ITEM_DEFAULTS, $item), $items);
    }
}
