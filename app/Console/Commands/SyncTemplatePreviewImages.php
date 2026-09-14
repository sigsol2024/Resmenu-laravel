<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * Copies template preview images into public/assets/images/menu-items, categories, and sections
 * so nested URLs resolve even without rewrite tricks.
 */
class SyncTemplatePreviewImages extends Command
{
    protected $signature = 'resmenu:sync-preview-images';

    protected $description = 'Copy preview demo images into menu-items/, categories/, and sections/ subfolders';

    public function handle(): int
    {
        $root = public_path('assets/images');
        $dirs = [
            'menu-items' => $root.DIRECTORY_SEPARATOR.'menu-items',
            'categories' => $root.DIRECTORY_SEPARATOR.'categories',
            'sections' => $root.DIRECTORY_SEPARATOR.'sections',
        ];

        if (! is_dir($root)) {
            $this->error('Missing directory: '.$root);

            return self::FAILURE;
        }

        foreach ($dirs as $dir) {
            if (! is_dir($dir) && ! mkdir($dir, 0755, true) && ! is_dir($dir)) {
                $this->error('Could not create: '.$dir);

                return self::FAILURE;
            }
        }

        $map = config('template_preview_images', []);
        $exclude = array_flip($map['exclude'] ?? []);
        $needed = $this->collectFilenames($map);

        $copied = ['menu-items' => 0, 'categories' => 0, 'sections' => 0];
        $missing = [];

        foreach ($needed as $filename) {
            $source = $this->resolveSource($root, $filename);
            if ($source === null) {
                $missing[] = $filename;
                continue;
            }

            foreach ($dirs as $key => $dir) {
                if (copy($source, $dir.DIRECTORY_SEPARATOR.$filename)) {
                    $copied[$key]++;
                }
            }
        }

        foreach (glob($root.DIRECTORY_SEPARATOR.'*.{jpg,jpeg,png,gif,webp}', GLOB_BRACE) ?: [] as $source) {
            $filename = basename($source);
            if (isset($exclude[$filename])) {
                continue;
            }
            $target = $dirs['menu-items'].DIRECTORY_SEPARATOR.$filename;
            if (! is_file($target) && copy($source, $target)) {
                $copied['menu-items']++;
            }
        }

        $this->info(sprintf(
            'Synced %d to menu-items/, %d to categories/, %d to sections/.',
            $copied['menu-items'],
            $copied['categories'],
            $copied['sections']
        ));

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
        foreach (['items', 'categories', 'covers', 'sections'] as $key) {
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
            $root.DIRECTORY_SEPARATOR.'sections'.DIRECTORY_SEPARATOR.$filename,
        ];

        foreach ($candidates as $path) {
            if (is_file($path)) {
                return $path;
            }
        }

        return null;
    }
}
