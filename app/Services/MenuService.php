<?php

namespace App\Services;

use App\Support\LegacyMenuViewData;
use App\Models\Category;
use App\Models\MenuItem;
use App\Models\Restaurant;
use App\Models\Section;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class MenuService
{
    public function findActiveRestaurantBySlug(string $slug): ?Restaurant
    {
        return Restaurant::query()
            ->where('slug', $slug)
            ->where('is_active', 1)
            ->first();
    }

    public function sectionsWithMenu(Restaurant $restaurant): array
    {
        $sections = Section::query()
            ->where('restaurant_id', $restaurant->id)
            ->where('is_active', 1)
            ->orderBy('display_order')
            ->get();

        if ($sections->isEmpty()) {
            return $this->fallbackVirtualSection($restaurant);
        }

        $result = [];
        foreach ($sections as $section) {
            $categories = $this->primaryCategoriesForSection($restaurant->id, $section->id);
            $mapped = $section->toArray();
            $mapped['categories'] = $categories;
            $result[] = $mapped;
        }

        return $result;
    }

    public function sectionWithMenuBySlug(Restaurant $restaurant, string $sectionSlug): ?array
    {
        $section = Section::query()
            ->where('restaurant_id', $restaurant->id)
            ->where('slug', $sectionSlug)
            ->where('is_active', 1)
            ->first();

        if (! $section) {
            return null;
        }

        $mapped = $section->toArray();
        $mapped['categories'] = $this->categoriesForSectionPage($restaurant->id, $section->id);

        return $mapped;
    }

    /** @return list<array{id:int,name:string,slug:string}> */
    public function sectionsForNav(int $restaurantId): array
    {
        return Section::query()
            ->where('restaurant_id', $restaurantId)
            ->where('is_active', 1)
            ->orderBy('display_order')
            ->get(['id', 'name', 'slug'])
            ->map(fn (Section $s) => ['id' => $s->id, 'name' => $s->name, 'slug' => $s->slug])
            ->all();
    }

    /** Primary categories only (full menu pages). */
    private function primaryCategoriesForSection(int $restaurantId, int $sectionId): array
    {
        return Category::query()
            ->where('restaurant_id', $restaurantId)
            ->where('section_id', $sectionId)
            ->where('is_active', 1)
            ->orderBy('display_order')
            ->orderBy('name')
            ->get()
            ->map(fn (Category $cat) => $this->mapCategory($cat))
            ->all();
    }

    /** Primary + secondary mapped categories (single-section views). */
    private function categoriesForSectionPage(int $restaurantId, int $sectionId): array
    {
        try {
            $rows = DB::select('
                SELECT c.*, x.is_secondary
                FROM (
                    SELECT c.id, 0 AS is_secondary
                    FROM categories c
                    WHERE c.restaurant_id = ? AND c.section_id = ? AND c.is_active = 1
                    UNION ALL
                    SELECT c.id, 1 AS is_secondary
                    FROM categories c
                    INNER JOIN category_secondary_sections css ON css.category_id = c.id
                    WHERE c.restaurant_id = ? AND css.section_id = ? AND c.is_active = 1
                      AND css.is_active = 1 AND c.section_id <> ?
                ) x
                INNER JOIN categories c ON c.id = x.id
                ORDER BY x.is_secondary ASC, c.display_order ASC, c.name ASC
            ', [$restaurantId, $sectionId, $restaurantId, $sectionId, $sectionId]);

            return collect($rows)->map(function ($row) {
                $cat = Category::query()->find($row->id);
                if (! $cat) {
                    return null;
                }

                return $this->mapCategory($cat);
            })->filter()->values()->all();
        } catch (\Throwable) {
            return $this->primaryCategoriesForSection($restaurantId, $sectionId);
        }
    }

    private function fallbackVirtualSection(Restaurant $restaurant): array
    {
        $categories = Category::query()
            ->where('restaurant_id', $restaurant->id)
            ->where('is_active', 1)
            ->orderBy('display_order')
            ->with(['menuItems' => function ($mq) {
                $mq->where('is_available', 1)->orderBy('display_order');
            }])
            ->get();

        if ($categories->isEmpty()) {
            return [];
        }

        return [[
            'id' => 0,
            'name' => 'Menu',
            'slug' => 'menu',
            'display_order' => 1,
            'is_active' => 1,
            'image' => null,
            'categories' => $categories->map(fn (Category $cat) => $this->mapCategory($cat))->all(),
        ]];
    }

    private function mapCategory(Category $category): array
    {
        $category->loadMissing(['menuItems' => fn ($q) => $q->where('is_available', 1)->orderBy('display_order')]);
        $data = $category->toArray();
        $data['menu_items'] = LegacyMenuViewData::normalizeMenuItems(
            $category->menuItems->map(fn (MenuItem $item) => $item->toArray())->all()
        );

        return $data;
    }

    public function samplePreviewPayload(int $templateId): array
    {
        $uploadBaseUrl = rtrim(config('resmenu.upload_url'), '/');

        $restaurant = LegacyMenuViewData::normalizeRestaurant([
            'id' => 0,
            'name' => 'Your Restaurant',
            'slug' => 'template-preview',
            'logo' => null,
            'description' => 'Template preview – replace with your own name, logo, and menu.',
            'template_id' => $templateId,
            'header_menu_items' => null,
            'address' => '123 Sample Street, City Centre',
            'phone' => '+1 (555) 123-4567',
            'email' => 'hello@yourrestaurant.com',
            'hero_image' => null,
            'hero_image_url' => url('/templates/template'.$templateId.'/cover.jpg'),
            'footer_content' => 'At Your Restaurant, we believe in great food and warm service. This is a template preview – your real content will replace this.',
            'google_rating' => 4.8,
            'rating_source' => 'Google',
            'map_latitude' => null,
            'map_longitude' => null,
            'opening_hours' => "Mon–Fri: 11:00 – 22:00\nSat–Sun: 10:00 – 23:00",
            'instagram_url' => 'https://instagram.com',
            'facebook_url' => 'https://facebook.com',
            'twitter_url' => 'https://twitter.com',
            'whatsapp_link' => 'https://wa.me/15551234567',
            'enable_food_ordering' => true,
            'enable_table_reservations' => true,
        ], $uploadBaseUrl);

        $sections = [[
            'id' => 1,
            'name' => 'Main',
            'slug' => 'main',
            'categories' => [[
                'id' => 1,
                'name' => 'Starters',
                'menu_items' => [
                    ['id' => 1, 'name' => 'Sample Dish', 'description' => 'Preview item', 'price' => 2500, 'image' => null],
                ],
            ]],
        ]];

        return LegacyMenuViewData::normalize([
            'restaurant' => $restaurant,
            'sections' => $sections,
            'customization' => ['primary_color' => '#f20d0d', 'background_color' => '#f8f5f5'],
            'headerMenuItems' => [],
            'singleSectionView' => false,
            'fullMenuUrl' => url('/templates/'.$templateId.'/preview'),
            'sectionsForNav' => [['id' => 1, 'name' => 'Main', 'slug' => 'main']],
            'uploadBaseUrl' => $uploadBaseUrl,
            'templateAssetBaseUrl' => url('/templates/template'.$templateId),
            'template4BaseUrl' => url('/templates/template4'),
            'supportsOrdering' => true,
            'supportsReservations' => true,
            'reservationUrl' => '#',
            'isTemplatePreview' => true,
        ]);
    }
}
