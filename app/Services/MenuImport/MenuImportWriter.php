<?php

namespace App\Services\MenuImport;

use App\Models\Category;
use App\Models\MenuItem;
use App\Models\Section;
use App\Services\ActivityLogService;
use App\Services\DisplayOrderService;
use App\Services\PlanVisibilityService;
use Illuminate\Support\Facades\DB;

/**
 * Writes an analyzed draft in one transaction. The restaurant row is locked
 * first so concurrent imports (or a double submit) run one after the other,
 * and the analysis is repeated inside the lock: if it no longer matches the
 * revision the manager confirmed, nothing is written.
 */
final class MenuImportWriter
{
    public function __construct(
        private MenuImportAnalyzer $analyzer,
        private MenuImportSlugGenerator $slugs,
        private DisplayOrderService $displayOrders,
        private PlanVisibilityService $planVisibility,
        private ActivityLogService $activityLog,
    ) {}

    /**
     * @param  list<array<string, mixed>>  $rows
     * @param  array{filename?: string, ip?: ?string, user_agent?: ?string, impersonator_admin_id?: ?int}  $context
     * @return array{status: 'imported'|'stale'|'blocked', analysis: array<string, mixed>, result?: array<string, int>}
     */
    public function import(int $restaurantId, int $managerId, array $rows, string $confirmedRevision, array $context = []): array
    {
        $outcome = DB::transaction(function () use ($restaurantId, $rows, $confirmedRevision) {
            DB::table('restaurants')->where('id', $restaurantId)->lockForUpdate()->first();

            $analysis = $this->analyzer->analyze($restaurantId, $rows);
            if (! hash_equals($analysis['revision'], $confirmedRevision)) {
                return ['status' => 'stale', 'analysis' => $analysis];
            }
            if (! $analysis['can_import']) {
                return ['status' => 'blocked', 'analysis' => $analysis];
            }

            return ['status' => 'imported', 'analysis' => $analysis, 'result' => $this->apply($restaurantId, $analysis)];
        });

        if ($outcome['status'] === 'imported') {
            $this->planVisibility->forgetCache($restaurantId);
            $this->activityLog->record(
                'manager',
                $managerId,
                'menu_import',
                $restaurantId,
                'restaurant',
                $restaurantId,
                null,
                array_filter([
                    'filename' => $context['filename'] ?? null,
                    'impersonator_admin_id' => $context['impersonator_admin_id'] ?? null,
                ] + $outcome['result'], fn ($v) => $v !== null),
                $context['ip'] ?? null,
                $context['user_agent'] ?? null,
            );
        }

        return $outcome;
    }

    /** @return array<string, int> */
    private function apply(int $restaurantId, array $analysis): array
    {
        $plan = $analysis['plan'];
        $summary = $analysis['summary'];
        $result = [
            'sections_created' => 0,
            'sections_reused' => 0,
            'categories_created' => 0,
            'categories_reused' => 0,
            'items_created' => 0,
            'items_updated' => 0,
            'items_unchanged' => $summary['items']['unchanged'],
            'items_skipped' => $summary['items']['duplicate'] + $summary['items']['excluded'],
        ];

        $sectionIds = [];
        foreach ($plan['sections'] as $key => $section) {
            if ($section['status'] === 'existing') {
                $sectionIds[$key] = (int) $section['existing_id'];
                $result['sections_reused']++;

                continue;
            }
            $sectionIds[$key] = (int) Section::create([
                'restaurant_id' => $restaurantId,
                'name' => $section['name'],
                'slug' => $this->slugs->forSection($restaurantId, $section['name']),
                'display_order' => $this->displayOrders->nextSectionOrder($restaurantId),
                'is_active' => true,
            ])->id;
            $result['sections_created']++;
        }

        $categoryIds = [];
        foreach ($plan['categories'] as $key => $category) {
            if ($category['status'] === 'existing') {
                $categoryIds[$key] = (int) $category['existing_id'];
                $result['categories_reused']++;

                continue;
            }
            $sectionId = $sectionIds[$category['section_key']];
            $categoryIds[$key] = (int) Category::create([
                'restaurant_id' => $restaurantId,
                'section_id' => $sectionId,
                'name' => $category['name'],
                'slug' => $this->slugs->forCategory($restaurantId, $category['name']),
                'description' => null,
                'display_order' => $this->displayOrders->nextCategoryOrder($restaurantId, $sectionId),
                'is_active' => true,
            ])->id;
            $result['categories_created']++;
        }

        foreach ($plan['items'] as $item) {
            if ($item['action'] === 'update') {
                $existing = MenuItem::query()
                    ->where('restaurant_id', $restaurantId)
                    ->whereKey($item['existing_id'])
                    ->firstOrFail();
                $updates = [];
                if (isset($item['changes']['price'])) {
                    $updates['price'] = $item['price'];
                }
                if (isset($item['changes']['description'])) {
                    $updates['description'] = $item['description'];
                }
                $existing->update($updates);
                $result['items_updated']++;

                continue;
            }

            $categoryId = $categoryIds[$item['category_key']];
            MenuItem::create([
                'restaurant_id' => $restaurantId,
                'category_id' => $categoryId,
                'name' => $item['name'],
                'slug' => $this->slugs->forMenuItem($restaurantId, $item['name']),
                'description' => $item['description'] !== '' ? $item['description'] : null,
                'price' => $item['price'],
                'display_order' => $this->displayOrders->nextMenuItemOrder($restaurantId, $categoryId),
                'is_available' => true,
            ]);
            $result['items_created']++;
        }

        return $result;
    }
}
