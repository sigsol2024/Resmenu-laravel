<?php

namespace App\Console\Commands;

use App\Services\UploadService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Copy curated Preview_covers into upload template-previews/ and update DB.
 * Only templates listed in config('template_preview_images.gallery_covers') are synced.
 * Other template rows are cleared so the marketing gallery stays empty (no fakes).
 */
class SyncPreviewCoversCommand extends Command
{
    protected $signature = 'templates:sync-preview-covers
        {--dry-run : Show actions without copying or updating the database}
        {--keep-others : Do not clear preview_image on templates without a curated cover}';

    protected $description = 'Sync supplied Preview_covers into template-previews and templates.preview_image';

    public function handle(UploadService $uploads): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $keepOthers = (bool) $this->option('keep-others');
        $covers = config('template_preview_images.gallery_covers', []);

        if (! is_array($covers) || $covers === []) {
            $this->error('No gallery_covers configured in config/template_preview_images.php');

            return self::FAILURE;
        }

        $previewRoot = public_path('templates/preview_images');
        $synced = 0;
        $failed = 0;
        $syncedIds = [];

        foreach ($covers as $templateId => $relativePath) {
            $templateId = (int) $templateId;
            $relativePath = str_replace('\\', '/', (string) $relativePath);
            if ($templateId < 1 || $relativePath === '') {
                $this->warn("Skipping invalid gallery_covers entry.");
                $failed++;

                continue;
            }

            $source = $previewRoot.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $relativePath);
            if (! is_file($source)) {
                // Filenames may use Unicode dashes; resolve by basename prefix under Preview_covers/.
                $source = $this->resolveCoverSource($previewRoot, $relativePath) ?? $source;
            }
            if (! is_file($source)) {
                $this->error("Missing cover file for template {$templateId}: {$relativePath}");
                $failed++;

                continue;
            }

            $ext = strtolower(pathinfo($source, PATHINFO_EXTENSION) ?: 'png');
            if (! in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true)) {
                $this->error("Unsupported extension for template {$templateId}: .{$ext}");
                $failed++;

                continue;
            }

            $destName = 'template-'.$templateId.'.'.$ext;
            $destPath = $uploads->filePath('template-previews', $destName);

            if ($dryRun) {
                $this->line("[dry-run] Would copy {$relativePath} → template-previews/{$destName}");
            } else {
                $destDir = dirname($destPath);
                if (! is_dir($destDir) && ! mkdir($destDir, 0755, true) && ! is_dir($destDir)) {
                    $this->error("Could not create directory for template {$templateId}");
                    $failed++;

                    continue;
                }
                if (! @copy($source, $destPath)) {
                    $this->error("Failed to copy cover for template {$templateId}");
                    $failed++;

                    continue;
                }

                $affected = DB::table('templates')->where('id', $templateId)->update([
                    'preview_image' => $destName,
                    'updated_at' => now(),
                ]);

                if (! $affected) {
                    $this->warn("Copied file but no templates row for id {$templateId}");
                } else {
                    $this->info("Synced template {$templateId} → {$destName}");
                }
            }

            $synced++;
            $syncedIds[] = $templateId;
        }

        if (! $keepOthers) {
            $query = DB::table('templates')
                ->whereNotNull('preview_image')
                ->where('preview_image', '!=', '');

            if ($syncedIds !== []) {
                $query->whereNotIn('id', $syncedIds);
            }

            if ($dryRun) {
                $wouldClear = (clone $query)->count();
                $this->line("[dry-run] Would clear preview_image on {$wouldClear} other template row(s)");
            } else {
                $cleared = $query->update([
                    'preview_image' => null,
                    'updated_at' => now(),
                ]);
                if ($cleared > 0) {
                    $this->comment("Cleared preview_image on {$cleared} template(s) without curated covers");
                }
            }
        }

        $this->info("Curated covers processed: {$synced}/".count($covers));

        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }

    private function resolveCoverSource(string $previewRoot, string $relativePath): ?string
    {
        $relativePath = str_replace('\\', '/', $relativePath);
        $basename = basename($relativePath);
        $dirRel = trim(dirname($relativePath), '.');
        $dir = $previewRoot.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $dirRel);
        if (! is_dir($dir)) {
            return null;
        }

        $exact = $dir.DIRECTORY_SEPARATOR.$basename;
        if (is_file($exact)) {
            return $exact;
        }

        // Match by prefix before the first em/en dash or triple hyphen.
        $prefix = preg_split('/(?:—|–|---+)/u', $basename, 2)[0] ?? $basename;
        $prefix = rtrim((string) $prefix, '-_');
        if ($prefix === '') {
            return null;
        }

        foreach (scandir($dir) ?: [] as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            if (str_starts_with($file, $prefix) && is_file($dir.DIRECTORY_SEPARATOR.$file)) {
                return $dir.DIRECTORY_SEPARATOR.$file;
            }
        }

        return null;
    }
}
