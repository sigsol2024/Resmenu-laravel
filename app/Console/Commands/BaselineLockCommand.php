<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class BaselineLockCommand extends Command
{
    protected $signature = 'resmenu:migrate:baseline-lock
                            {--force : Run without confirmation}';

    protected $description = 'Mark baseline migrations as ran without executing up() (brownfield Path B)';

    public function handle(): int
    {
        $baselinePath = database_path('migrations/baseline');
        if (! File::isDirectory($baselinePath)) {
            $this->error('Baseline migrations directory not found.');

            return self::FAILURE;
        }

        $files = collect(File::files($baselinePath))
            ->map(fn ($f) => pathinfo($f->getFilename(), PATHINFO_FILENAME))
            ->sort()
            ->values();

        if ($files->isEmpty()) {
            $this->error('No baseline migration files found.');

            return self::FAILURE;
        }

        if (! $this->option('force') && ! $this->confirm('Record '.$files->count().' baseline migrations as ran WITHOUT running them?')) {
            return self::SUCCESS;
        }

        $batch = (int) DB::table('migrations')->max('batch') + 1;
        $ran = DB::table('migrations')->pluck('migration')->all();
        $inserted = 0;

        foreach ($files as $migration) {
            if (in_array($migration, $ran, true)) {
                continue;
            }
            DB::table('migrations')->insert([
                'migration' => $migration,
                'batch' => $batch,
            ]);
            $inserted++;
        }

        $this->info("Baseline lock complete. Recorded {$inserted} migration(s) in batch {$batch}.");

        return self::SUCCESS;
    }
}
