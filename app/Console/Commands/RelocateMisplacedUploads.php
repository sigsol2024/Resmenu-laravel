<?php

namespace App\Console\Commands;

use App\Services\UploadService;
use Illuminate\Console\Command;

class RelocateMisplacedUploads extends Command
{
    protected $signature = 'resmenu:relocate-uploads {--dry-run : List files that would be moved without changing disk}';

    protected $description = 'Move upload files from public/uploads into the configured upload_root (e.g. public/storage/uploads)';

    public function handle(UploadService $uploads): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $source = public_path('uploads');
        $target = $uploads->root();

        $this->line('Source: '.$source);
        $this->line('Target: '.$target);

        if ($source === $target) {
            $this->info('Upload root already matches public/uploads — nothing to relocate.');

            return self::SUCCESS;
        }

        if ($dryRun) {
            $this->warn('Dry run — no files will be moved.');
        }

        $stats = $uploads->relocateFromLegacyPublicUploads($dryRun);

        $this->info(($dryRun ? 'Would move' : 'Moved').": {$stats['moved']}");
        $this->line("Skipped (already at target): {$stats['skipped']}");
        if ($stats['errors'] > 0) {
            $this->warn("Errors: {$stats['errors']}");
        }

        if (! $dryRun && $stats['moved'] > 0) {
            $this->comment('Run php artisan config:clear if you changed UPLOAD_ROOT or UPLOAD_URL recently.');
        }

        return self::SUCCESS;
    }
}
