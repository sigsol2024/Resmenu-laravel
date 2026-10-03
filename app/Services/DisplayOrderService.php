<?php

namespace App\Services;

use App\Models\Category;
use App\Models\MenuItem;
use App\Models\Section;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Display orders are dense 1..n per group: sections per restaurant, categories per
 * primary section, menu items per category. Placing a record at position N shifts
 * the rest of its group so no two records share a number.
 */
class DisplayOrderService
{
    /** Blank form input means "no explicit position". */
    public function requestedPosition(mixed $value): ?int
    {
        return ($value === null || $value === '') ? null : (int) $value;
    }

    /**
     * Edit forms post the stored number back even when only other fields changed; treat an
     * unchanged number as "stay put" so legacy, non-dense numbers do not move the record.
     */
    public function positionForUpdate(mixed $value, ?int $previousOrder, bool $movedGroup): ?int
    {
        $position = $this->requestedPosition($value);

        return (! $movedGroup && $position !== null && $position === $previousOrder) ? null : $position;
    }

    /** Null position keeps the current slot (or appends when the record is new to the group). */
    public function placeSection(Section $section, ?int $position, bool $newToGroup = false): void
    {
        $this->place($this->sectionGroup((int) $section->restaurant_id), $section, $position, $newToGroup);
    }

    public function placeCategory(Category $category, ?int $position, bool $newToGroup = false): void
    {
        $this->place(
            $this->categoryGroup((int) $category->restaurant_id, (int) $category->section_id),
            $category,
            $position,
            $newToGroup,
        );
    }

    public function placeMenuItem(MenuItem $item, ?int $position, bool $newToGroup = false): void
    {
        $this->place(
            $this->menuItemGroup((int) $item->restaurant_id, (int) $item->category_id),
            $item,
            $position,
            $newToGroup,
        );
    }

    public function resequenceSections(int $restaurantId): int
    {
        return $this->resequence($this->sectionGroup($restaurantId));
    }

    public function resequenceCategories(int $restaurantId, int $sectionId): int
    {
        return $this->resequence($this->categoryGroup($restaurantId, $sectionId));
    }

    public function resequenceMenuItems(int $restaurantId, int $categoryId): int
    {
        return $this->resequence($this->menuItemGroup($restaurantId, $categoryId));
    }

    /** Renumbers every section, category and menu item group of a restaurant. Returns rows changed. */
    public function resequenceRestaurant(int $restaurantId): int
    {
        $changed = $this->resequenceSections($restaurantId);

        $sectionIds = Category::query()->where('restaurant_id', $restaurantId)->distinct()->pluck('section_id');
        foreach ($sectionIds as $sectionId) {
            $changed += $this->resequenceCategories($restaurantId, (int) $sectionId);
        }

        $categoryIds = MenuItem::query()->where('restaurant_id', $restaurantId)->distinct()->pluck('category_id');
        foreach ($categoryIds as $categoryId) {
            $changed += $this->resequenceMenuItems($restaurantId, (int) $categoryId);
        }

        return $changed;
    }

    public function nextSectionOrder(int $restaurantId): int
    {
        return $this->next(Section::query()->where('restaurant_id', $restaurantId));
    }

    public function nextCategoryOrder(int $restaurantId, ?int $sectionId = null): int
    {
        $query = Category::query()->where('restaurant_id', $restaurantId);

        if ($sectionId) {
            $query->where('section_id', $sectionId);
        }

        return $this->next($query);
    }

    public function nextMenuItemOrder(int $restaurantId, ?int $categoryId = null): int
    {
        $query = MenuItem::query()->where('restaurant_id', $restaurantId);

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        return $this->next($query);
    }

    /** @return array<int, int> */
    public function nextCategoryOrderPerSection(int $restaurantId): array
    {
        return $this->nextPerGroup(
            Category::query()->where('restaurant_id', $restaurantId),
            'section_id',
        );
    }

    /** @return array<int, int> */
    public function nextMenuItemOrderPerCategory(int $restaurantId): array
    {
        return $this->nextPerGroup(
            MenuItem::query()->where('restaurant_id', $restaurantId),
            'category_id',
        );
    }

    private function sectionGroup(int $restaurantId): Builder
    {
        return Section::query()->where('restaurant_id', $restaurantId);
    }

    private function categoryGroup(int $restaurantId, int $sectionId): Builder
    {
        return Category::query()->where('restaurant_id', $restaurantId)->where('section_id', $sectionId);
    }

    private function menuItemGroup(int $restaurantId, int $categoryId): Builder
    {
        return MenuItem::query()->where('restaurant_id', $restaurantId)->where('category_id', $categoryId);
    }

    private function place(Builder $group, Model $model, ?int $position, bool $newToGroup): void
    {
        DB::transaction(function () use ($group, $model, $position, $newToGroup) {
            $rows = $this->lockedRows($group);
            $ids = array_keys($rows);
            $currentIndex = array_search((int) $model->getKey(), $ids, true);
            $others = array_values(array_filter($ids, fn ($id) => $id !== (int) $model->getKey()));

            if ($position !== null) {
                $index = min(max($position, 1), count($others) + 1) - 1;
            } elseif ($newToGroup || $currentIndex === false) {
                $index = count($others);
            } else {
                $index = $currentIndex;
            }

            array_splice($others, $index, 0, [(int) $model->getKey()]);
            $this->writeOrder($group, $rows, $others);
            $model->setAttribute('display_order', $index + 1);
            $model->syncOriginalAttribute('display_order');
        });
    }

    private function resequence(Builder $group): int
    {
        return DB::transaction(function () use ($group) {
            $rows = $this->lockedRows($group);

            return $this->writeOrder($group, $rows, array_keys($rows));
        });
    }

    /** @return array<int, int|null> id => display_order, in current display order */
    private function lockedRows(Builder $group): array
    {
        return (clone $group)
            ->toBase()
            ->orderByRaw('COALESCE(display_order, 999999) ASC')
            ->orderBy('id')
            ->lockForUpdate()
            ->get(['id', 'display_order'])
            ->mapWithKeys(fn ($row) => [(int) $row->id => $row->display_order === null ? null : (int) $row->display_order])
            ->all();
    }

    /**
     * @param  array<int, int|null>  $rows
     * @param  list<int>  $orderedIds
     */
    private function writeOrder(Builder $group, array $rows, array $orderedIds): int
    {
        $changed = 0;
        foreach ($orderedIds as $i => $id) {
            if (($rows[$id] ?? null) !== $i + 1) {
                (clone $group)->toBase()->where('id', $id)->update(['display_order' => $i + 1]);
                $changed++;
            }
        }

        return $changed;
    }

    private function next(Builder $query): int
    {
        return max(0, (int) $query->max('display_order')) + 1;
    }

    /** @return array<int, int> */
    private function nextPerGroup(Builder $query, string $column): array
    {
        $rows = $query->toBase()
            ->selectRaw($column.' as group_id, MAX(display_order) as max_order')
            ->groupBy($column)
            ->get();

        $result = [];
        foreach ($rows as $row) {
            $result[(int) $row->group_id] = max(0, (int) $row->max_order) + 1;
        }

        return $result;
    }
}
