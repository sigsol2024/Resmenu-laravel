<?php

namespace App\Services;

use App\Models\Category;
use App\Models\MenuItem;
use App\Models\Restaurant;
use App\Models\Section;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

/**
 * Permanent restaurant deletion — shared by admin UI and scheduled purge.
 *
 * DB row deletion commits first; upload files are unlinked only after a successful
 * commit, and only when no other restaurant still references the same filename.
 */
class RestaurantDeletionService
{
    public function __construct(private UploadService $uploads) {}

    public function permanentlyDelete(Restaurant $restaurant): void
    {
        $restaurantId = (int) $restaurant->id;
        $files = [];

        DB::transaction(function () use ($restaurant, &$files) {
            $files = $this->deleteRowCollectingFiles($restaurant);
        });

        $this->unlinkCollectedFiles($files);

        Log::info('restaurant.permanently_deleted', ['restaurant_id' => $restaurantId]);
    }

    /**
     * Delete the restaurant row inside an existing transaction and return files to unlink
     * only after that outer transaction commits successfully.
     *
     * @return list<array{0: string, 1: string}>
     */
    public function deleteRowCollectingFiles(Restaurant $restaurant): array
    {
        $files = $this->collectDeletableOwnedFiles((int) $restaurant->id, $restaurant);
        $restaurant->delete();

        return $files;
    }

    /**
     * @param  list<array{0: string, 1: string}>  $files
     */
    public function unlinkCollectedFiles(array $files): void
    {
        foreach ($files as [$subdir, $filename]) {
            if ($this->filenameInUseByOtherRestaurant($subdir, $filename, null)) {
                continue;
            }
            $this->uploads->delete($subdir, $filename);
        }
    }

    /**
     * @return list<array{0: string, 1: string}>
     */
    private function collectDeletableOwnedFiles(int $restaurantId, Restaurant $restaurant): array
    {
        $candidates = [];

        foreach ([['logos', $restaurant->logo], ['heroes', $restaurant->hero_image]] as [$subdir, $filename]) {
            if (is_string($filename) && $filename !== '') {
                $candidates[] = [$subdir, $filename];
            }
        }

        Section::query()->where('restaurant_id', $restaurantId)->orderBy('id')->chunkById(100, function ($sections) use (&$candidates) {
            foreach ($sections as $section) {
                if (! empty($section->image) && is_string($section->image)) {
                    $candidates[] = ['sections', $section->image];
                }
            }
        });

        Category::query()->where('restaurant_id', $restaurantId)->orderBy('id')->chunkById(100, function ($categories) use (&$candidates) {
            foreach ($categories as $category) {
                if (! empty($category->image) && is_string($category->image)) {
                    $candidates[] = ['categories', $category->image];
                }
            }
        });

        MenuItem::query()->where('restaurant_id', $restaurantId)->orderBy('id')->chunkById(100, function ($items) use (&$candidates) {
            foreach ($items as $item) {
                if (! empty($item->image) && is_string($item->image)) {
                    $candidates[] = ['menu-items', $item->image];
                }
            }
        });

        if (Schema::hasTable('restaurant_qr_codes') && Schema::hasColumn('restaurant_qr_codes', 'image')) {
            try {
                $qrFiles = DB::table('restaurant_qr_codes')->where('restaurant_id', $restaurantId)->pluck('image');
                foreach ($qrFiles as $file) {
                    if (is_string($file) && $file !== '') {
                        $candidates[] = ['qr-codes', $file];
                    }
                }
            } catch (\Throwable) {
                // Skip QR file cleanup if schema differs.
            }
        }

        $unique = [];
        $out = [];
        foreach ($candidates as [$subdir, $filename]) {
            $key = $subdir."\0".$filename;
            if (isset($unique[$key])) {
                continue;
            }
            $unique[$key] = true;
            if ($this->filenameInUseByOtherRestaurant($subdir, $filename, $restaurantId)) {
                Log::info('restaurant.delete_skip_shared_file', [
                    'restaurant_id' => $restaurantId,
                    'subdir' => $subdir,
                    'filename' => $filename,
                ]);
                continue;
            }
            $out[] = [$subdir, $filename];
        }

        return $out;
    }

    private function filenameInUseByOtherRestaurant(string $subdir, string $filename, ?int $excludingRestaurantId): bool
    {
        $filename = basename($filename);
        if ($filename === '' || $filename === '.' || $filename === '..') {
            return true;
        }

        return match ($subdir) {
            'logos' => Restaurant::query()
                ->when($excludingRestaurantId, fn ($q) => $q->where('id', '!=', $excludingRestaurantId))
                ->where('logo', $filename)
                ->exists(),
            'heroes' => Restaurant::query()
                ->when($excludingRestaurantId, fn ($q) => $q->where('id', '!=', $excludingRestaurantId))
                ->where('hero_image', $filename)
                ->exists(),
            'sections' => Section::query()
                ->when($excludingRestaurantId, fn ($q) => $q->where('restaurant_id', '!=', $excludingRestaurantId))
                ->where('image', $filename)
                ->exists(),
            'categories' => Category::query()
                ->when($excludingRestaurantId, fn ($q) => $q->where('restaurant_id', '!=', $excludingRestaurantId))
                ->where('image', $filename)
                ->exists(),
            'menu-items' => MenuItem::query()
                ->when($excludingRestaurantId, fn ($q) => $q->where('restaurant_id', '!=', $excludingRestaurantId))
                ->where('image', $filename)
                ->exists(),
            'qr', 'qr-codes' => $this->qrFilenameInUse($filename, $excludingRestaurantId),
            default => true,
        };
    }

    private function qrFilenameInUse(string $filename, ?int $excludingRestaurantId): bool
    {
        if (! Schema::hasTable('restaurant_qr_codes') || ! Schema::hasColumn('restaurant_qr_codes', 'image')) {
            return false;
        }

        try {
            $q = DB::table('restaurant_qr_codes')->where('image', $filename);
            if ($excludingRestaurantId) {
                $q->where('restaurant_id', '!=', $excludingRestaurantId);
            }

            return $q->exists();
        } catch (\Throwable) {
            return true;
        }
    }
}
