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

    /**
     * Full-menu payload (all sections on one page / menu API).
     * Primary categories only — never secondary — so a category is not duplicated
     * under multiple sections on the same scrollable menu.
     * Per-section pages use sectionWithMenuBySlug() which includes secondary links.
     */
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
            $mapped = $section->toArray();
            $mapped['categories'] = $this->primaryCategoriesForSection((int) $restaurant->id, (int) $section->id);
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
        $seen = [];

        foreach ($this->sectionsWithMenu($restaurant) as $section) {
            foreach ($section['categories'] ?? [] as $category) {
                foreach ($category['menu_items'] ?? [] as $item) {
                    if (empty($item['is_available'])) {
                        continue;
                    }
                    $id = (int) ($item['id'] ?? 0);
                    if ($id > 0) {
                        if (isset($seen[$id])) {
                            continue;
                        }
                        $seen[$id] = true;
                    }
                    $items[] = $item;
                    if (count($items) >= max(0, $limit)) {
                        return $items;
                    }
                }
            }
        }

        return $items;
    }

    /**
     * Landing / directory cards.
     * Includes primary and secondary-linked categories (same source as section pages).
     * Template 7 directory should match the sidebar: every active section with browseable
     * content appears; sections that only exist via secondary links are included.
     *
     * @return list<array<string, mixed>>
     */
    public function sectionsForHome(Restaurant $restaurant): array
    {
        $sections = Section::query()
            ->where('restaurant_id', $restaurant->id)
            ->where('is_active', 1)
            ->orderBy('display_order')
            ->orderBy('name')
            ->get();

        if ($sections->isEmpty()) {
            return $this->decorateHomeSections($this->fallbackVirtualSection($restaurant));
        }

        $result = [];
        foreach ($sections as $section) {
            // Same loader as the public section page (primary + secondary).
            $full = $this->sectionWithMenuBySlug($restaurant, (string) $section->slug);
            if ($full === null) {
                continue;
            }

            $entry = $this->toHomeSectionEntry($full);
            if ($entry !== null) {
                $result[] = $entry;
            }
        }

        return $result;
    }

    /**
     * @param  list<array<string, mixed>>  $sections
     * @return list<array<string, mixed>>
     */
    private function decorateHomeSections(array $sections): array
    {
        $out = [];
        foreach ($sections as $section) {
            $entry = $this->toHomeSectionEntry($section);
            if ($entry !== null) {
                $out[] = $entry;
            }
        }

        return $out;
    }

    /**
     * Keep a section on the landing when the section page would show categories/items,
     * or when it has active primary/secondary mappings (so secondary-linked sections surface).
     *
     * @param  array<string, mixed>  $section
     * @return array<string, mixed>|null
     */
    private function toHomeSectionEntry(array $section): ?array
    {
        $allCategories = is_array($section['categories'] ?? null) ? $section['categories'] : [];

        $withItems = array_values(array_filter($allCategories, static function ($cat): bool {
            if (! is_array($cat)) {
                return false;
            }
            if (array_key_exists('is_active', $cat) && empty($cat['is_active'])) {
                return false;
            }
            $items = $cat['menu_items'] ?? null;
            if ($items instanceof \Countable) {
                return count($items) > 0;
            }

            return is_array($items) && $items !== [];
        }));

        $sectionId = (int) ($section['id'] ?? 0);
        $restaurantId = (int) ($section['restaurant_id'] ?? 0);

        // No visible items: still show the card if this section has primary/secondary mappings.
        if ($withItems === [] && ! $this->sectionHasCategoryMappings($restaurantId, $sectionId)) {
            return null;
        }

        $itemCount = 0;
        foreach ($withItems as $cat) {
            $items = $cat['menu_items'] ?? [];
            $itemCount += is_countable($items) ? count($items) : 0;
        }

        $section['item_count'] = $itemCount;
        $section['categories'] = $this->stripMenuItemsFromCategories(
            $withItems !== [] ? $withItems : $allCategories
        );

        return $section;
    }

    private function sectionHasCategoryMappings(int $restaurantId, int $sectionId): bool
    {
        if ($sectionId < 1 || $restaurantId < 1) {
            return false;
        }

        $hasPrimary = Category::query()
            ->where('restaurant_id', $restaurantId)
            ->where('section_id', $sectionId)
            ->where('is_active', 1)
            ->exists();

        if ($hasPrimary) {
            return true;
        }

        try {
            return DB::table('category_secondary_sections as css')
                ->join('categories as c', 'c.id', '=', 'css.category_id')
                ->where('c.restaurant_id', $restaurantId)
                ->where('css.section_id', $sectionId)
                ->where('css.is_active', 1)
                ->where('c.is_active', 1)
                ->exists();
        } catch (\Throwable) {
            return false;
        }
    }

    /**
     * Slim searchable item list for Template 7 landing autocomplete.
     * Does not attach full menu payloads to the home view.
     *
     * @return list<array{name:string,slug:string,section_slug:string,category_slug:string,price:float|int|string}>
     */
    public function menuSearchIndex(Restaurant $restaurant): array
    {
        $index = [];
        $sections = Section::query()
            ->where('restaurant_id', $restaurant->id)
            ->where('is_active', 1)
            ->orderBy('display_order')
            ->get();

        $source = $sections->isEmpty()
            ? $this->fallbackVirtualSection($restaurant)
            : $sections->map(function (Section $section) use ($restaurant): array {
                $mapped = $section->toArray();
                $mapped['categories'] = $this->categoriesForSectionPage((int) $restaurant->id, (int) $section->id);

                return $mapped;
            })->all();

        foreach ($source as $section) {
            $sectionSlug = (string) ($section['slug'] ?? '');
            if ($sectionSlug === '') {
                continue;
            }
            foreach ($section['categories'] ?? [] as $category) {
                $categorySlug = (string) ($category['slug'] ?? '');
                foreach ($category['menu_items'] ?? [] as $item) {
                    if (empty($item['is_available'])) {
                        continue;
                    }
                    $itemSlug = (string) ($item['slug'] ?? '');
                    if ($itemSlug === '') {
                        continue;
                    }
                    $index[] = [
                        'name' => (string) ($item['name'] ?? ''),
                        'slug' => $itemSlug,
                        'section_slug' => $sectionSlug,
                        'category_slug' => $categorySlug,
                        'price' => $item['price'] ?? 0,
                    ];
                }
            }
        }

        return $index;
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

    /** Primary categories only — used by full-menu pages (no secondary duplicates). */
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
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Secondary category load failed; using primary only', [
                'restaurant_id' => $restaurantId,
                'section_id' => $sectionId,
                'error' => $e->getMessage(),
            ]);

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
        $categoryId = (int) $category->id;
        $visibility = $this->visibilityForCategory($restaurantId, $categoryId);

        if (! $visibility->isCategoryVisibleOnPublicMenu($categoryId)) {
            return null;
        }

        $category->loadMissing(['menuItems' => fn ($q) => $q->where('is_available', 1)->orderByRaw('COALESCE(display_order, 999999) ASC')->orderBy('id')]);
        $items = [];
        foreach ($category->menuItems as $item) {
            $itemId = (int) $item->id;
            $visibility = $this->visibilityForMenuItem($restaurantId, $itemId);
            if (! $visibility->isMenuItemVisibleOnPublicMenu($itemId)) {
                continue;
            }
            $items[] = $item->toArray();
        }

        $data = $category->toArray();
        $data['menu_items'] = LegacyMenuViewData::normalizeMenuItems($items);

        return $data;
    }

    private function visibilityForCategory(int $restaurantId, int $categoryId): PlanVisibilityResult
    {
        $visibility = $this->visibilityFor($restaurantId);
        if (array_key_exists($categoryId, $visibility->categories)) {
            return $visibility;
        }

        // Stale cache can omit brand-new category IDs (unknown => hidden).
        $this->planVisibility->forgetCache($restaurantId);
        $this->visibility = null;
        $this->visibilityRestaurantId = null;

        return $this->visibilityFor($restaurantId);
    }

    private function visibilityForMenuItem(int $restaurantId, int $menuItemId): PlanVisibilityResult
    {
        $visibility = $this->visibilityFor($restaurantId);
        if (array_key_exists($menuItemId, $visibility->menuItems)) {
            return $visibility;
        }

        $this->planVisibility->forgetCache($restaurantId);
        $this->visibility = null;
        $this->visibilityRestaurantId = null;

        return $this->visibilityFor($restaurantId);
    }

    public function samplePreviewPayload(int $templateId): array
    {
        return app(TemplatePreviewDemoService::class)->buildPayload($templateId);
    }
}
