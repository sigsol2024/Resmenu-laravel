<?php

namespace App\Support;

/**
 * Canonical slug rules for restaurants, sections, categories, and menu items.
 */
class SlugNormalizer
{
    public static function normalize(string $value): string
    {
        $slug = strtolower(trim($value));
        $slug = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $slug) ?: $slug;
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug) ?? $slug;
        $slug = preg_replace('/-+/', '-', $slug) ?? $slug;

        return trim($slug, '-');
    }
}
