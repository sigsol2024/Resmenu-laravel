<?php

namespace App\Console\Commands;

use App\Services\UploadService;
use App\Support\MenuTemplateResolver;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Process;

/**
 * Generate WebP gallery heroes from live template previews (Pass 2).
 *
 * Workflow on hosts without Node (cPanel jailshell):
 * 1. Capture locally: php artisan templates:generate-previews --all --base=https://our-menu.online --skip-db
 * 2. Upload public/uploads/template-previews/template-*.webp to the server
 * 3. On server: php artisan templates:generate-previews --all --db-only
 *
 * New templates: add data-template-preview-hero on the above-the-fold hero
 * (section, header, or div) in resources/views/menu/php-templates/template{N}
 * so --all includes them.
 */
class GenerateTemplatePreviewsCommand extends Command
{
    protected $signature = 'templates:generate-previews
        {--all : Generate for every available local template id}
        {--template= : Single template id}
        {--dry-run : Capture/check without updating templates.preview_image}
        {--base= : Base URL (default app.url)}
        {--skip-db : Do not update templates.preview_image even when not dry-run}
        {--db-only : Skip Playwright; only set preview_image from existing uploaded files}';

    protected $description = 'Playwright WebP hero screenshots for template gallery cards (CLI only)';

    public function handle(MenuTemplateResolver $resolver): int
    {
        $all = (bool) $this->option('all');
        $template = $this->option('template');
        $dryRun = (bool) $this->option('dry-run');
        $skipDb = (bool) $this->option('skip-db');
        $dbOnly = (bool) $this->option('db-only');
        $base = rtrim((string) ($this->option('base') ?: config('app.url')), '/');

        if ($dbOnly && $skipDb) {
            $this->error('Cannot combine --db-only with --skip-db.');

            return self::FAILURE;
        }

        if ($dbOnly && $dryRun) {
            $this->error('Cannot combine --db-only with --dry-run.');

            return self::FAILURE;
        }

        if (! $all && ($template === null || $template === '')) {
            $this->error('Specify --all or --template=N');

            return self::FAILURE;
        }

        $ids = $all
            ? $resolver->availableTemplateIds()
            : [(int) $template];

        $ids = array_values(array_filter(array_map('intval', $ids), fn ($id) => $id > 0));
        if ($ids === []) {
            $this->error('No template ids to process.');

            return self::FAILURE;
        }

        if ($dbOnly) {
            $this->info('DB-only mode — skipping Playwright (Node not required).');

            return $this->updatePreviewImageRows($ids);
        }

        if (! $this->nodeIsAvailable()) {
            $this->error('Node.js not found on PATH (npm/npx unavailable on this host).');
            $this->line('Capture screenshots on a machine with Node, upload template-*.webp, then run:');
            $this->line('  php artisan templates:generate-previews --all --db-only');

            return self::FAILURE;
        }

        $script = base_path('tools/generate-template-previews.mjs');
        if (! is_file($script)) {
            $this->error('Missing tools/generate-template-previews.mjs');

            return self::FAILURE;
        }

        $args = [
            'node',
            $script,
            '--base='.$base,
            '--ids='.implode(',', $ids),
        ];
        if ($dryRun) {
            $args[] = '--dry-run';
        }

        $this->info('Running Playwright capture…');
        $result = Process::timeout(60 * max(3, count($ids)))
            ->path(base_path())
            ->run($args);

        $this->output->write($result->output());
        if ($result->errorOutput() !== '') {
            $this->output->write($result->errorOutput());
        }

        if (! $result->successful()) {
            $this->error('Generator exited with errors.');
            $combined = $result->errorOutput().$result->output();
            if (str_contains($combined, 'Cannot find module') || str_contains($combined, 'Cannot find package')) {
                $this->line('Install deps locally: npm install && npx playwright install chromium');
            }

            return self::FAILURE;
        }

        if ($dryRun || $skipDb) {
            $this->comment($dryRun ? 'Dry-run complete — database not updated.' : 'DB update skipped (--skip-db).');

            return self::SUCCESS;
        }

        return $this->updatePreviewImageRows($ids);
    }

    /**
     * @param  list<int>  $ids
     */
    private function updatePreviewImageRows(array $ids): int
    {
        $updated = 0;
        $missing = 0;

        foreach ($ids as $id) {
            $file = $this->resolvePreviewFilename($id);
            if ($file === null) {
                $missing++;
                $this->warn("No image file for template {$id}; DB not updated.");

                continue;
            }

            $affected = DB::table('templates')->where('id', $id)->update([
                'preview_image' => $file,
                'updated_at' => now(),
            ]);

            if ($affected) {
                $updated++;
                $this->info("Updated templates.preview_image for id {$id} → {$file}");
            } else {
                $this->warn("No templates row for id {$id}");
            }
        }

        $this->info("Database rows updated: {$updated}");
        if ($missing > 0) {
            $this->warn('Missing files: '.$missing.'. Expected template-{id}.webp (or .png) under the upload root template-previews/ folder.');
        }

        return ($updated > 0 || $missing === 0) ? self::SUCCESS : self::FAILURE;
    }

    private function resolvePreviewFilename(int $id): ?string
    {
        /** @var UploadService $uploads */
        $uploads = app(UploadService::class);

        foreach (['template-'.$id.'.webp', 'template-'.$id.'.png'] as $candidate) {
            if ($uploads->resolveExistingPath('template-previews', $candidate) !== null) {
                return $candidate;
            }
        }

        // Fallback for local generate output before upload_root is configured
        foreach (['webp', 'png'] as $ext) {
            $path = public_path('uploads/template-previews/template-'.$id.'.'.$ext);
            if (is_file($path)) {
                return 'template-'.$id.'.'.$ext;
            }
        }

        return null;
    }

    private function nodeIsAvailable(): bool
    {
        try {
            $result = Process::timeout(8)->run(['node', '-v']);

            return $result->successful();
        } catch (\Throwable) {
            return false;
        }
    }
}
