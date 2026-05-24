<?php

namespace App\Services;

use App\Models\Category;
use App\Models\MenuItem;
use App\Models\Restaurant;
use App\Models\Section;

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
            ->with(['categories' => function ($q) {
                $q->where('is_active', 1)->orderBy('display_order')
                    ->with(['menuItems' => function ($mq) {
                        $mq->where('is_available', 1)->orderBy('display_order');
                    }]);
            }])
            ->get();

        if ($sections->isEmpty()) {
            return $this->fallbackVirtualSection($restaurant);
        }

        return $sections->map(fn (Section $section) => $this->mapSection($section))->all();
    }

    public function sectionWithMenuBySlug(Restaurant $restaurant, string $sectionSlug): ?array
    {
        $section = Section::query()
            ->where('restaurant_id', $restaurant->id)
            ->where('slug', $sectionSlug)
            ->where('is_active', 1)
            ->with(['categories' => function ($q) {
                $q->where('is_active', 1)->orderBy('display_order')
                    ->with(['menuItems' => function ($mq) {
                        $mq->where('is_available', 1)->orderBy('display_order');
                    }]);
            }])
            ->first();

        return $section ? $this->mapSection($section) : null;
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

    private function mapSection(Section $section): array
    {
        $data = $section->toArray();
        $data['categories'] = $section->categories
            ->map(fn (Category $cat) => $this->mapCategory($cat))
            ->all();

        return $data;
    }

    private function mapCategory(Category $category): array
    {
        $data = $category->toArray();
        $data['menu_items'] = $category->menuItems
            ->map(fn (MenuItem $item) => $item->toArray())
            ->all();

        return $data;
    }

    /** Sample data for template preview routes. */
    public function samplePreviewPayload(int $templateId): array
    {
        $restaurant = [
            'id' => 0,
            'name' => 'Preview Restaurant',
            'slug' => 'preview',
            'template_id' => $templateId,
            'enable_food_ordering' => true,
            'enable_table_reservations' => true,
        ];

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

        return [
            'restaurant' => $restaurant,
            'sections' => $sections,
            'customization' => ['primary_color' => '#f20d0d', 'background_color' => '#f8f5f5'],
            'headerMenuItems' => [],
            'singleSectionView' => false,
            'fullMenuUrl' => url('/templates/'.$templateId.'/preview'),
            'sectionsForNav' => [['id' => 1, 'name' => 'Main', 'slug' => 'main']],
            'uploadBaseUrl' => rtrim(config('resmenu.upload_url'), '/'),
            'template4BaseUrl' => url('/templates/template4'),
            'supportsOrdering' => true,
            'supportsReservations' => true,
            'reservationUrl' => '#',
        ];
    }
}
