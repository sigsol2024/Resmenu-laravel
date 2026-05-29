<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class BuildSyncSqlCommand extends Command
{
    protected $signature = 'resmenu:build:sync-sql
                            {source? : Path to sigsolmenu_resmenu.sql}
                            {output? : Output directory for sync_part_*.sql}
                            {--include-scans : Include qr_code_scans per restaurant}
                            {--include-inventory : Include table_inventory_daily}
                            {--force-passwords : Overwrite admin/manager password hashes from dump}
                            {--dry-run : Print EXPECTED counts only}';

    protected $description = 'Generate slug-based sync SQL from a live production dump';

    public function handle(): int
    {
        $script = base_path('database/scripts/build_sync_laravel_from_live_dump.php');
        if (! is_file($script)) {
            $this->error('Generator script not found: '.$script);

            return self::FAILURE;
        }

        $cmd = [
            PHP_BINARY,
            $script,
        ];

        if ($source = $this->argument('source')) {
            $cmd[] = $source;
        }
        if ($output = $this->argument('output')) {
            $cmd[] = $output;
        }

        if ($this->option('include-scans')) {
            $cmd[] = '--include-scans';
        }
        if ($this->option('include-inventory')) {
            $cmd[] = '--include-inventory';
        }
        if ($this->option('force-passwords')) {
            $cmd[] = '--force-passwords';
        }
        if ($this->option('dry-run')) {
            $cmd[] = '--dry-run';
        }

        $process = new Process($cmd, base_path(), null, null, 600);
        $process->run(function (string $type, string $buffer) {
            $type === Process::ERR ? $this->error($buffer) : $this->output->write($buffer);
        });

        if (! $process->isSuccessful()) {
            return self::FAILURE;
        }

        if (! $this->option('dry-run')) {
            $this->info('Sync SQL written under database/seed-sql/production/');
        }

        return self::SUCCESS;
    }
}
