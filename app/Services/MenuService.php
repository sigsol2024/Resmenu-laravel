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
     * Keep a section on the landing only when it has browseable categories with items
     * (primary or secondary). Avoids empty "mapping-only" cards that open to
     * "No items in this section".
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

        if ($withItems === []) {
            return null;
        }

        $itemCount = 0;
        foreach ($withItems as $cat) {
            $items = $cat['menu_items'] ?? [];
            $itemCount += is_countable($items) ? count($items) : 0;
        }

        $section['item_count'] = $itemCount;
        $section['categories'] = $this->stripMenuItemsFromCategories($withItems);

        return $section;
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

    /** Primary + secondary mapped categories (single-section views / T6–T7 section pages). */
    private function categoriesForSectionPage(int $restaurantId, int $sectionId): array
    {
        $primaryIds = Category::query()
            ->where('restaurant_id', $restaurantId)
            ->where('section_id', $sectionId)
            ->where('is_active', 1)
            ->orderByRaw('COALESCE(display_order, 999999) ASC')
            ->orderBy('id')
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();

        $secondaryIds = [];
        try {
            $secondaryIds = DB::table('category_secondary_sections as css')
                ->join('categories as c', 'c.id', '=', 'css.category_id')
                ->where('c.restaurant_id', $restaurantId)
                ->where('css.section_id', $sectionId)
                ->where('css.is_active', 1)
                ->where('c.is_active', 1)
                ->where('c.section_id', '<>', $sectionId)
                ->orderByRaw('COALESCE(c.display_order, 999999) ASC')
                ->orderBy('c.id')
                ->pluck('c.id')
                ->map(fn ($id) => (int) $id)
                ->all();
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Secondary category lookup failed; using primary only', [
                'restaurant_id' => $restaurantId,
                'section_id' => $sectionId,
                'error' => $e->getMessage(),
            ]);
        }

        $orderedIds = [];
        foreach (array_merge($primaryIds, $secondaryIds) as $id) {
            if ($id > 0 && ! in_array($id, $orderedIds, true)) {
                $orderedIds[] = $id;
            }
        }

        if ($orderedIds === []) {
            return [];
        }

        $categories = Category::query()
            ->where('restaurant_id', $restaurantId)
            ->whereIn('id', $orderedIds)
            ->get()
            ->keyBy('id');

        $mapped = [];
        foreach ($orderedIds as $id) {
            $cat = $categories->get($id);
            if (! $cat) {
                continue;
            }
            $row = $this->mapCategory($cat, $restaurantId);
            if (is_array($row)) {
                $mapped[] = $row;
            }
        }

        return $mapped;
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

        // Fresh query — avoid loadMissing leaving a stale/empty relation from earlier loads.
        $itemModels = MenuItem::query()
            ->where('restaurant_id', $restaurantId)
            ->where('category_id', $categoryId)
            ->where('is_available', 1)
            ->orderByRaw('COALESCE(display_order, 999999) ASC')
            ->orderBy('id')
            ->get();

        $items = [];
        foreach ($itemModels as $item) {
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
