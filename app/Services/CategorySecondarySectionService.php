<?php

namespace App\Services;

use App\Support\TenantScope;
use Illuminate\Support\Facades\DB;

class CategorySecondarySectionService
{
    /** @return list<int> */
    public function sectionIdsForCategory(int $categoryId): array
    {
        return DB::table('category_secondary_sections')
            ->where('category_id', $categoryId)
            ->where('is_active', 1)
            ->pluck('section_id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }

    /** @param  list<int|string>|int|string|null  $sectionIds */
    public function sync(int $categoryId, int $primarySectionId, array|int|string|null $sectionIds, int $restaurantId): void
    {
        $ids = $this->normalizeIds($sectionIds);

        TenantScope::assertSectionBelongsToRestaurant($primarySectionId, $restaurantId);
        TenantScope::assertSectionsBelongToRestaurant($ids, $restaurantId);

        DB::transaction(function () use ($categoryId, $primarySectionId, $ids) {
            DB::table('category_secondary_sections')->where('category_id', $categoryId)->delete();

            $now = now();
            foreach ($ids as $sectionId) {
                if ($sectionId < 1 || $sectionId === $primarySectionId) {
                    continue;
                }

                DB::table('category_secondary_sections')->insert([
                    'category_id' => $categoryId,
                    'section_id' => $sectionId,
                    'is_active' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        });
    }

    /** @param  list<int|string>|int|string|null  $sectionIds
     *  @return list<int>
     */
    public function normalizeIds(array|int|string|null $sectionIds): array
    {
        if ($sectionIds === null || $sectionIds === '') {
            return [];
        }

        if (! is_array($sectionIds)) {
            $sectionIds = [$sectionIds];
        }

        $out = [];
        foreach ($sectionIds as $id) {
            $id = (int) $id;
            if ($id > 0 && ! in_array($id, $out, true)) {
                $out[] = $id;
            }
        }

        return $out;
    }
}
