<?php

namespace App\Services;

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

    /** @param  list<int>  $sectionIds */
    public function sync(int $categoryId, int $primarySectionId, array $sectionIds): void
    {
        DB::table('category_secondary_sections')->where('category_id', $categoryId)->delete();

        foreach ($sectionIds as $sectionId) {
            $sectionId = (int) $sectionId;
            if ($sectionId < 1 || $sectionId === $primarySectionId) {
                continue;
            }
            DB::table('category_secondary_sections')->insert([
                'category_id' => $categoryId,
                'section_id' => $sectionId,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
