<?php

namespace App\Services;

use App\Support\LegacyMenuViewData;
use App\Support\PlanVisibilityResult;
use App\Models\Category;
use App\Models\MenuItem;
use App\Models\Restaurant;
use App\Models\Section;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class MenuService
{
    private ?PlanVisibilityResult $visibility = null;

    private ?int $visibilityRestaurantId = null;

    public function __construct(private PlanVisibilityService $planVisibility) {}

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

    /** Section with category metadata only (no menu_items) for Template 6 category grid. */
    public function sectionWithCategoriesOnlyBySlug(Restaurant $restaurant, string $sectionSlug): ?array
    {
        $section = $this->sectionWithMenuBySlug($restaurant, $sectionSlug);
        if ($section === null) {
            return null;
        }

        $section['categories'] = $this->stripMenuItemsFromCategories($section['categories'] ?? []);

        return $section;
    }

    /**
     * @return array{section: array<string, mixed>, category: array<string, mixed>}|null
     */
    public function categoryWithMenuInSection(Restaurant $restaurant, string $sectionSlug, string $categorySlug): ?array
    {
        $sectionRow = Section::query()
            ->where('restaurant_id', $restaurant->id)
            ->where('slug', $sectionSlug)
            ->where('is_active', 1)
            ->first();

        if (! $sectionRow) {
            return null;
        }

        $category = Category::query()
            ->where('restaurant_id', $restaurant->id)
            ->where('slug', $categorySlug)
            ->where('is_active', 1)
            ->first();

        if (! $category) {
            return null;
        }

        if (! $this->categoryBelongsToSection($restaurant->id, (int) $sectionRow->id, (int) $category->id)) {
            return null;
        }

        $mappedCategory = $this->mapCategory($category, $restaurant->id);
        if ($mappedCategory === null) {
            return null;
        }

        $section = $sectionRow->toArray();
        $section['categories'] = $this->stripMenuItemsFromCategories(
            $this->categoriesForSectionPage($restaurant->id, (int) $sectionRow->id)
        );

        return [
            'section' => $section,
            'category' => $mappedCategory,
        ];
    }

    /** @return list<array<string, mixed>> */
    public function popularMenuItems(Restaurant $restaurant, int $limit = 3): array
    {
        $items = [];
        foreach ($this->sectionsWithMenu($restaurant) as $section) {
            foreach ($section['categories'] ?? [] as $category) {
                foreach ($category['menu_items'] ?? [] as $item) {
                    if (! empty($item['is_available'])) {
                        $items[] = $item;
                    }
                }
            }
        }

        return array_slice($items, 0, max(0, $limit));
    }

    /** @return list<array<string, mixed>> */
    public function sectionsForHome(Restaurant $restaurant): array
    {
        $sections = $this->sectionsWithMenu($restaurant);

        return array_values(array_filter(array_map(function (array $section): ?array {
            $categories = array_values(array_filter($section['categories'] ?? [], static function (array $cat): bool {
                return ! empty($cat['menu_items']) && is_array($cat['menu_items']);
            }));
            if ($categories === []) {
                return null;
            }
            $section['categories'] = $this->stripMenuItemsFromCategories($categories);

            return $section;
        }, $sections)));
    }

    /** @param list<array<string, mixed>> $categories */
    public function stripMenuItemsFromCategories(array $categories): array
    {
        return array_values(array_map(static function (array $category): array {
            unset($category['menu_items']);

            return $category;
        }, $categories));
    }

    private function categoryBelongsToSection(int $restaurantId, int $sectionId, int $categoryId): bool
    {
        $primary = Category::query()
            ->where('id', $categoryId)
            ->where('restaurant_id', $restaurantId)
            ->where('section_id', $sectionId)
            ->exists();

        if ($primary) {
            return true;
        }

        try {
            return DB::table('category_secondary_sections')
                ->where('category_id', $categoryId)
                ->where('section_id', $sectionId)
                ->where('is_active', 1)
                ->exists();
        } catch (\Throwable) {
            return false;
        }
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
            ->orderByRaw('COALESCE(display_order, 999999) ASC')
            ->orderBy('id')
            ->get()
            ->map(fn (Category $cat) => $this->mapCategory($cat, $restaurantId))
            ->filter()
            ->values()
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
                ORDER BY x.is_secondary ASC, COALESCE(c.display_order, 999999) ASC, c.id ASC
            ', [$restaurantId, $sectionId, $restaurantId, $sectionId, $sectionId]);

            return collect($rows)->map(function ($row) {
                $cat = Category::query()->find($row->id);
                if (! $cat) {
                    return null;
                }

                return $this->mapCategory($cat, $restaurantId);
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
            ->orderByRaw('COALESCE(display_order, 999999) ASC')
            ->orderBy('id')
            ->with(['menuItems' => function ($mq) {
                $mq->where('is_available', 1)
                    ->orderByRaw('COALESCE(display_order, 999999) ASC')
                    ->orderBy('id');
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
            'categories' => $categories->map(fn (Category $cat) => $this->mapCategory($cat, $restaurant->id))
                ->filter()
                ->values()
                ->all(),
        ]];
    }

  private function visibilityFor(int $restaurantId): PlanVisibilityResult
    {
        if ($this->visibility === null || $this->visibilityRestaurantId !== $restaurantId) {
            $this->visibility = $this->planVisibility->resolve($restaurantId);
            $this->visibilityRestaurantId = $restaurantId;
        }

        return $this->visibility;
    }

    private function mapCategory(Category $category, int $restaurantId): ?array
    {
        $visibility = $this->visibilityFor($restaurantId);

        if (! $visibility->isCategoryVisibleOnPublicMenu((int) $category->id)) {
            return null;
        }

        $category->loadMissing(['menuItems' => fn ($q) => $q->where('is_available', 1)->orderByRaw('COALESCE(display_order, 999999) ASC')->orderBy('id')]);
        $items = $category->menuItems
            ->filter(fn (MenuItem $item) => $visibility->isMenuItemVisibleOnPublicMenu((int) $item->id))
            ->map(fn (MenuItem $item) => $item->toArray())
            ->values()
            ->all();

        $data = $category->toArray();
        $data['menu_items'] = LegacyMenuViewData::normalizeMenuItems($items);

        return $data;
    }

    public function samplePreviewPayload(int $templateId): array
    {
        return app(TemplatePreviewDemoService::class)->buildPayload($templateId);
    }
}
