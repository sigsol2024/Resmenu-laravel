<?php

namespace App\Console\Commands;

use App\Support\MenuTemplateResolver;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Process;

/**
 * Generate WebP gallery heroes from live template previews (Pass 2).
 *
 * New templates: add <section data-template-preview-hero> around the above-the-fold
 * hero in resources/views/menu/php-templates/template{N}/index.php so --all includes them.
 */
class GenerateTemplatePreviewsCommand extends Command
{
    protected $signature = 'templates:generate-previews
        {--all : Generate for every available local template id}
        {--template= : Single template id}
        {--dry-run : Capture/check without updating templates.preview_image}
        {--base= : Base URL (default app.url)}
        {--skip-db : Do not update templates.preview_image even when not dry-run}';

    protected $description = 'Playwright WebP hero screenshots for template gallery cards (CLI only)';

    public function handle(MenuTemplateResolver $resolver): int
    {
        $all = (bool) $this->option('all');
        $template = $this->option('template');
        $dryRun = (bool) $this->option('dry-run');
        $skipDb = (bool) $this->option('skip-db');
        $base = rtrim((string) ($this->option('base') ?: config('app.url')), '/');

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

            return self::FAILURE;
        }

        if ($dryRun || $skipDb) {
            $this->comment($dryRun ? 'Dry-run complete — database not updated.' : 'DB update skipped (--skip-db).');

            return self::SUCCESS;
        }

        $updated = 0;
        foreach ($ids as $id) {
            $webp = public_path('uploads/template-previews/template-'.$id.'.webp');
            $png = public_path('uploads/template-previews/template-'.$id.'.png');
            $file = is_file($webp) ? 'template-'.$id.'.webp' : (is_file($png) ? 'template-'.$id.'.png' : null);
            if ($file === null) {
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

        return self::SUCCESS;
    }
}
