<?php

namespace App\Services\MenuImport;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Same slug rule as the manual menu controllers: Str::slug(name), unique across
 * the restaurant, with "-" and 4 random characters appended on a clash. Unlike
 * the controllers it keeps retrying until the slug is free, because one import
 * can create many records with the same name.
 */
final class MenuImportSlugGenerator
{
    private const MAX_BASE_LENGTH = 250;

    private const MAX_ATTEMPTS = 25;

    public function forSection(int $restaurantId, string $name): string
    {
        return $this->generate('sections', $restaurantId, $name, 'section');
    }

    public function forCategory(int $restaurantId, string $name): string
    {
        return $this->generate('categories', $restaurantId, $name, 'category');
    }

    public function forMenuItem(int $restaurantId, string $name): string
    {
        return $this->generate('menu_items', $restaurantId, $name, 'item');
    }

    private function generate(string $table, int $restaurantId, string $name, string $fallback): string
    {
        $base = rtrim(substr(Str::slug($name), 0, self::MAX_BASE_LENGTH), '-');
        if ($base === '') {
            $base = $fallback;
        }

        $slug = $base;
        $attempts = 0;
        while ($this->taken($table, $restaurantId, $slug)) {
            if (++$attempts > self::MAX_ATTEMPTS) {
                throw new RuntimeException("Could not generate a unique slug for \"{$name}\".");
            }
            $slug = $base.'-'.Str::random(4);
        }

        return $slug;
    }

    private function taken(string $table, int $restaurantId, string $slug): bool
    {
        return DB::table($table)
            ->where('restaurant_id', $restaurantId)
            ->where('slug', $slug)
            ->exists();
    }
}
