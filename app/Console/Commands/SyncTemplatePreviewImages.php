<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * Copies template preview images into public/assets/images/menu-items and categories
 * so URLs like /assets/images/menu-items/{file}.jpg resolve without path tricks.
 */
class SyncTemplatePreviewImages extends Command
{
    protected $signature = 'resmenu:sync-preview-images';

    protected $description = 'Copy preview demo images into menu-items/ and categories/ subfolders';

    public function handle(): int
    {
        $root = public_path('assets/images');
        $menuItemsDir = $root.DIRECTORY_SEPARATOR.'menu-items';
        $categoriesDir = $root.DIRECTORY_SEPARATOR.'categories';

        if (! is_dir($root)) {
            $this->error('Missing directory: '.$root);

            return self::FAILURE;
        }

        foreach ([$menuItemsDir, $categoriesDir] as $dir) {
            if (! is_dir($dir) && ! mkdir($dir, 0755, true) && ! is_dir($dir)) {
                $this->error('Could not create: '.$dir);

                return self::FAILURE;
            }
        }

        $map = config('template_preview_images', []);
        $exclude = array_flip($map['exclude'] ?? []);
        $needed = $this->collectFilenames($map);

        $copiedMenu = 0;
        $copiedCat = 0;
        $missing = [];

        foreach ($needed as $filename) {
            $source = $this->resolveSource($root, $filename);
            if ($source === null) {
                $missing[] = $filename;
                continue;
            }

            if (copy($source, $menuItemsDir.DIRECTORY_SEPARATOR.$filename)) {
                $copiedMenu++;
            }
            if (copy($source, $categoriesDir.DIRECTORY_SEPARATOR.$filename)) {
                $copiedCat++;
            }
        }

        // Also mirror every image sitting in assets/images/ root (legacy uploads folder layout).
        foreach (glob($root.DIRECTORY_SEPARATOR.'*.{jpg,jpeg,png,gif,webp}', GLOB_BRACE) ?: [] as $source) {
            $filename = basename($source);
            if (isset($exclude[$filename])) {
                continue;
            }
            $target = $menuItemsDir.DIRECTORY_SEPARATOR.$filename;
            if (! is_file($target) && copy($source, $target)) {
                $copiedMenu++;
            }
        }

        $this->info("Synced {$copiedMenu} file(s) to menu-items/, {$copiedCat} to categories/.");

        if ($missing !== []) {
            $this->warn('Missing source files ('.count($missing).'):');
            foreach (array_slice($missing, 0, 10) as $file) {
                $this->line('  - '.$file);
            }
            if (count($missing) > 10) {
                $this->line('  ... and '.(count($missing) - 10).' more');
            }
        }

        return $missing !== [] ? self::FAILURE : self::SUCCESS;
    }

    /** @return list<string> */
    private function collectFilenames(array $map): array
    {
        $names = [];
        foreach (['items', 'categories', 'covers'] as $key) {
            foreach ($map[$key] ?? [] as $value) {
                if (is_string($value) && $value !== '') {
                    $names[$value] = true;
                }
            }
        }

        return array_keys($names);
    }

    private function resolveSource(string $root, string $filename): ?string
    {
        $candidates = [
            $root.DIRECTORY_SEPARATOR.$filename,
            $root.DIRECTORY_SEPARATOR.'menu-items'.DIRECTORY_SEPARATOR.$filename,
            $root.DIRECTORY_SEPARATOR.'categories'.DIRECTORY_SEPARATOR.$filename,
        ];

        foreach ($candidates as $path) {
            if (is_file($path)) {
                return $path;
            }
        }

        return null;
    }
}
