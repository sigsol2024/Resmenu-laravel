<?php

use App\Models\Category;
use App\Models\MenuItem;
use App\Models\Restaurant;
use App\Models\Section;
use App\Services\UploadService;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Seed Alo Alo Restaurant menu (reservations@vcphotels.com) and switch to Template 7.
 *
 * Resolves restaurant by slug/email (never by hardcoded id).
 * Tracks only rows this migration creates; pre-existing rows are left alone and not claimed.
 * Acceptance taxonomy: 11 sections / 27 categories / 162 items.
 */
return new class extends Migration
{
    private const DATA_DIR = 'data/alo_alo';

    private const MANIFEST_FILE = 'last_upload_manifest.json';

    /** @var list<array{subdir:string,filename:string}> */
    private array $newlyUploadedFiles = [];

    public function up(): void
    {
        $menu = require database_path(self::DATA_DIR.'/menu.php');
        $sources = require database_path(self::DATA_DIR.'/image_sources.php');

        $email = $menu['restaurant']['email'] ?? 'reservations@vcphotels.com';
        $slug = $menu['restaurant']['slug'] ?? 'alo-alo-restaurant';

        $restaurant = $this->resolveRestaurant($slug, $email);
        if (! $restaurant) {
            $message = sprintf(
                'Alo Alo seed migration aborted: restaurant not found for slug "%s" or email "%s". No menu was seeded.',
                $slug,
                $email
            );
            Log::error($message);
            throw new RuntimeException($message);
        }

        $manifestPath = $this->manifestPath();
        $priorManifest = $this->readManifest($manifestPath);

        $uploads = app(UploadService::class);
        $imageMap = $this->importImagesIdempotent($sources, $uploads, $priorManifest['image_map'] ?? []);

        $createdSectionIds = array_values(array_map('intval', $priorManifest['created_section_ids'] ?? []));
        $createdCategoryIds = array_values(array_map('intval', $priorManifest['created_category_ids'] ?? []));
        $createdItemIds = array_values(array_map('intval', $priorManifest['created_item_ids'] ?? []));

        // Preserve the original previous_template_id from the first successful seed run.
        $previousTemplateId = array_key_exists('previous_template_id', $priorManifest)
            ? (string) $priorManifest['previous_template_id']
            : (string) ($restaurant->template_id ?? '');

        $targetTemplateId = (int) ($menu['restaurant']['template_id'] ?? 7);
        $sectionSlugs = array_column($menu['sections'], 'slug');

        try {
            DB::transaction(function () use (
                $menu,
                $restaurant,
                $imageMap,
                $targetTemplateId,
                &$createdSectionIds,
                &$createdCategoryIds,
                &$createdItemIds
            ) {
                if ((int) $restaurant->template_id !== $targetTemplateId) {
                    $restaurant->template_id = $targetTemplateId;
                    $restaurant->save();
                }

                foreach ($menu['sections'] as $sectionData) {
                    $section = Section::query()->firstOrCreate(
                        [
                            'restaurant_id' => $restaurant->id,
                            'slug' => $sectionData['slug'],
                        ],
                        [
                            'name' => $sectionData['name'],
                            'display_order' => (int) ($sectionData['display_order'] ?? 0),
                            'is_active' => 1,
                            'image' => $imageMap[$sectionData['image_key'] ?? ''] ?? null,
                        ]
                    );

                    if ($section->wasRecentlyCreated) {
                        $createdSectionIds[] = (int) $section->id;
                    }
                    // Pre-existing sections: do not overwrite fields; do not claim ownership.

                    foreach ($sectionData['categories'] as $categoryData) {
                        $category = Category::query()->firstOrCreate(
                            [
                                'restaurant_id' => $restaurant->id,
                                'slug' => $categoryData['slug'],
                            ],
                            [
                                'section_id' => $section->id,
                                'name' => $categoryData['name'],
                                'display_order' => (int) ($categoryData['display_order'] ?? 0),
                                'is_active' => 1,
                                'image' => $imageMap[$categoryData['image_key'] ?? ''] ?? null,
                                'description' => $categoryData['description'] ?? null,
                            ]
                        );

                        if ($category->wasRecentlyCreated) {
                            $createdCategoryIds[] = (int) $category->id;
                        }
                        // Pre-existing categories: leave as-is; do not claim ownership.

                        foreach ($categoryData['items'] as $itemData) {
                            $existing = MenuItem::query()
                                ->where('restaurant_id', $restaurant->id)
                                ->where('category_id', $category->id)
                                ->where('slug', $itemData['slug'])
                                ->first();

                            if ($existing) {
                                continue;
                            }

                            $item = MenuItem::query()->create([
                                'restaurant_id' => $restaurant->id,
                                'category_id' => $category->id,
                                'name' => $itemData['name'],
                                'slug' => $itemData['slug'],
                                'description' => $itemData['description'] ?? '',
                                'price' => $itemData['price'],
                                'image' => $imageMap[$itemData['image_key'] ?? ''] ?? null,
                                'display_order' => (int) ($itemData['display_order'] ?? 0),
                                'is_available' => 1,
                            ]);

                            $createdItemIds[] = (int) $item->id;
                        }
                    }
                }

                $restaurant->available_items_count = MenuItem::query()
                    ->where('restaurant_id', $restaurant->id)
                    ->where('is_available', 1)
                    ->count();
                $restaurant->save();
            });
        } catch (\Throwable $e) {
            $this->cleanupNewlyUploadedFiles($uploads);
            throw $e;
        }

        $createdSectionIds = array_values(array_unique($createdSectionIds));
        $createdCategoryIds = array_values(array_unique($createdCategoryIds));
        $createdItemIds = array_values(array_unique($createdItemIds));

        $files = [];
        foreach ($imageMap as $imageKey => $filename) {
            $subdir = $this->subdirForKey((string) $imageKey);
            if ($subdir !== null && $filename !== '') {
                $files[] = ['subdir' => $subdir, 'filename' => $filename];
            }
        }

        file_put_contents($manifestPath, json_encode([
            'restaurant_id' => $restaurant->id,
            'restaurant_slug' => $slug,
            'previous_template_id' => $previousTemplateId,
            'section_slugs' => $sectionSlugs,
            'image_map' => $imageMap,
            'files' => $files,
            'created_section_ids' => $createdSectionIds,
            'created_category_ids' => $createdCategoryIds,
            'created_item_ids' => $createdItemIds,
        ], JSON_PRETTY_PRINT));
    }

    public function down(): void
    {
        $menu = require database_path(self::DATA_DIR.'/menu.php');
        $email = $menu['restaurant']['email'] ?? 'reservations@vcphotels.com';
        $slug = $menu['restaurant']['slug'] ?? 'alo-alo-restaurant';

        $manifestPath = $this->manifestPath();
        $manifest = $this->readManifest($manifestPath);

        if ($manifest === []) {
            $message = 'Alo Alo seed rollback aborted: missing last_upload_manifest.json with created_* IDs. Refusing slug-scoped delete to protect pre-existing data.';
            Log::error($message);
            throw new RuntimeException($message);
        }

        foreach (['created_section_ids', 'created_category_ids', 'created_item_ids'] as $key) {
            if (! array_key_exists($key, $manifest) || ! is_array($manifest[$key])) {
                $message = sprintf(
                    'Alo Alo seed rollback aborted: manifest missing "%s". Refusing destructive cleanup.',
                    $key
                );
                Log::error($message);
                throw new RuntimeException($message);
            }
        }

        $restaurant = $this->resolveRestaurant($slug, $email);
        if (! $restaurant) {
            $message = sprintf(
                'Alo Alo seed rollback aborted: restaurant not found for slug "%s" or email "%s".',
                $slug,
                $email
            );
            Log::error($message);
            throw new RuntimeException($message);
        }

        $createdSectionIds = array_values(array_filter(array_map('intval', $manifest['created_section_ids'])));
        $createdCategoryIds = array_values(array_filter(array_map('intval', $manifest['created_category_ids'])));
        $createdItemIds = array_values(array_filter(array_map('intval', $manifest['created_item_ids'])));

        DB::transaction(function () use (
            $restaurant,
            $manifest,
            $createdSectionIds,
            $createdCategoryIds,
            $createdItemIds
        ) {
            if ($createdItemIds !== []) {
                MenuItem::query()
                    ->where('restaurant_id', $restaurant->id)
                    ->whereIn('id', $createdItemIds)
                    ->delete();
            }

            if ($createdCategoryIds !== []) {
                Category::query()
                    ->where('restaurant_id', $restaurant->id)
                    ->whereIn('id', $createdCategoryIds)
                    ->delete();
            }

            if ($createdSectionIds !== []) {
                Section::query()
                    ->where('restaurant_id', $restaurant->id)
                    ->whereIn('id', $createdSectionIds)
                    ->delete();
            }

            if (array_key_exists('previous_template_id', $manifest) && $manifest['previous_template_id'] !== '') {
                $restaurant->template_id = (int) $manifest['previous_template_id'];
            }

            $restaurant->available_items_count = MenuItem::query()
                ->where('restaurant_id', $restaurant->id)
                ->where('is_available', 1)
                ->count();
            $restaurant->save();
        });

        $uploads = app(UploadService::class);
        $imageMap = is_array($manifest['image_map'] ?? null) ? $manifest['image_map'] : [];
        foreach ($imageMap as $imageKey => $filename) {
            $subdir = $this->subdirForKey((string) $imageKey);
            if ($subdir !== null && is_string($filename) && $filename !== '') {
                $uploads->delete($subdir, $filename);
            }
        }

        if (is_file($manifestPath)) {
            @unlink($manifestPath);
        }
    }

    private function resolveRestaurant(string $slug, string $email): ?Restaurant
    {
        return Restaurant::query()
            ->where(function ($q) use ($slug, $email) {
                $q->where('slug', $slug)
                    ->orWhere('email', $email)
                    ->orWhere('manager_email', $email);
            })
            ->first();
    }

    private function manifestPath(): string
    {
        return database_path(self::DATA_DIR.'/'.self::MANIFEST_FILE);
    }

    /**
     * @return array<string, mixed>
     */
    private function readManifest(string $path): array
    {
        if (! is_file($path)) {
            return [];
        }

        $decoded = json_decode((string) file_get_contents($path), true);

        return is_array($decoded) ? $decoded : [];
    }

    /**
     * @param  array<string, string>  $sources
     * @param  array<string, string>  $priorMap
     * @return array<string, string> image_key => stored filename
     */
    private function importImagesIdempotent(array $sources, UploadService $uploads, array $priorMap): array
    {
        $map = [];
        $stagingRoot = database_path(self::DATA_DIR.'/images');
        $publicRoot = public_path();

        foreach ($sources as $imageKey => $source) {
            if ($imageKey === '' || $source === '') {
                continue;
            }

            $subdir = $this->subdirForKey((string) $imageKey);
            if ($subdir === null) {
                continue;
            }

            $priorFilename = is_string($priorMap[$imageKey] ?? null) ? $priorMap[$imageKey] : null;
            if ($priorFilename !== null && $priorFilename !== '' && $uploads->resolveExistingPath($subdir, $priorFilename) !== null) {
                $map[$imageKey] = $priorFilename;
                continue;
            }

            $binary = null;
            $ext = pathinfo((string) $imageKey, PATHINFO_EXTENSION) ?: 'jpg';
            $ext = strtolower($ext);
            if (! in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true)) {
                $ext = 'jpg';
            }

            $staged = $stagingRoot.DIRECTORY_SEPARATOR.str_replace(['/', '\\'], DIRECTORY_SEPARATOR, (string) $imageKey);
            if (is_file($staged)) {
                $binary = @file_get_contents($staged);
            } elseif (str_starts_with((string) $source, 'http://') || str_starts_with((string) $source, 'https://')) {
                try {
                    $response = Http::timeout(45)->withHeaders([
                        'User-Agent' => 'ResmenuAloAloSeed/1.0',
                    ])->get((string) $source);
                    if ($response->successful()) {
                        $binary = $response->body();
                    }
                } catch (\Throwable) {
                    $binary = null;
                }
            } else {
                $local = $publicRoot.DIRECTORY_SEPARATOR.str_replace(['/', '\\'], DIRECTORY_SEPARATOR, ltrim((string) $source, '/\\'));
                if (is_file($local)) {
                    $binary = @file_get_contents($local);
                }
            }

            if ($binary === null || $binary === false || strlen($binary) < 100) {
                continue;
            }

            $filename = Str::random(12).'.'.$ext;
            $stored = $uploads->storeRawContents($subdir, $filename, $binary);
            if ($stored === null) {
                throw new RuntimeException(sprintf(
                    'Alo Alo seed migration failed: UploadService could not store image key "%s".',
                    $imageKey
                ));
            }

            $map[$imageKey] = $stored;
            $this->newlyUploadedFiles[] = ['subdir' => $subdir, 'filename' => $stored];
        }

        return $map;
    }

    private function cleanupNewlyUploadedFiles(UploadService $uploads): void
    {
        foreach ($this->newlyUploadedFiles as $file) {
            if (! empty($file['subdir']) && ! empty($file['filename'])) {
                $uploads->delete($file['subdir'], $file['filename']);
            }
        }
        $this->newlyUploadedFiles = [];
    }

    private function subdirForKey(string $imageKey): ?string
    {
        if (str_starts_with($imageKey, 'sections/')) {
            return 'sections';
        }
        if (str_starts_with($imageKey, 'categories/')) {
            return 'categories';
        }
        if (str_starts_with($imageKey, 'menu-items/')) {
            return 'menu-items';
        }
        if (str_starts_with($imageKey, 'logos/')) {
            return 'logos';
        }
        if (str_starts_with($imageKey, 'heroes/')) {
            return 'heroes';
        }

        return null;
    }
};
