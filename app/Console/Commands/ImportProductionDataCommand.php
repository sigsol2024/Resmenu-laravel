<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ImportProductionDataCommand extends Command
{
    protected $signature = 'resmenu:import:production
                            {file? : Path to data-only SQL (default: database/seed-sql/production/sigsolmenu_data_only.sql)}
                            {--force : Skip confirmation}';

    protected $description = 'Import production INSERT data after migrations (full site parity)';

    public function handle(): int
    {
        $file = $this->argument('file')
            ?? database_path('seed-sql/production/sigsolmenu_data_only.sql');

        if (! File::exists($file)) {
            $this->warn('Data file missing. Generate it first:');
            $this->line('  php database/scripts/build_data_only_dump.php');
            $this->line('  (uses Resmenu/database/sigsolmenu_resmenu.sql by default)');

            return self::FAILURE;
        }

        if (! $this->option('force') && ! $this->confirm('This will merge/overwrite rows via INSERT. Continue?', false)) {
            return self::SUCCESS;
        }

        $sql = File::get($file);
        $this->info('Importing: '.$file);

        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        $statements = $this->splitStatements($sql);
        $bar = $this->output->createProgressBar(count($statements));
        $bar->start();

        foreach ($statements as $statement) {
            if (trim($statement) === '') {
                continue;
            }
            try {
                DB::unprepared($statement);
            } catch (\Throwable $e) {
                $bar->finish();
                $this->newLine();
                $this->error('Failed: '.$e->getMessage());
                $this->line(substr($statement, 0, 200).'...');

                DB::statement('SET FOREIGN_KEY_CHECKS=1');

                return self::FAILURE;
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        $this->info('Production data import completed.');

        return self::SUCCESS;
    }

    /** @return list<string> */
    private function splitStatements(string $sql): array
    {
        $parts = preg_split('/;\s*\n/', $sql) ?: [];
        $statements = [];
        foreach ($parts as $part) {
            $part = trim($part);
            if ($part === '' || str_starts_with($part, '--')) {
                continue;
            }
            $statements[] = str_ends_with($part, ';') ? $part : $part.';';
        }

        return $statements;
    }
}
