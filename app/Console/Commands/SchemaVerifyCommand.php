<?php

namespace App\Console\Commands;

use App\Support\Schema\SchemaComparer;
use Illuminate\Console\Command;

class SchemaVerifyCommand extends Command
{
    protected $signature = 'resmenu:schema:verify
                            {--strict : Compare column definitions and index/FK names}';

    protected $description = 'Verify live MariaDB schema against MIGRATION_MANIFEST.json';

    public function handle(SchemaComparer $comparer): int
    {
        $strict = (bool) $this->option('strict');
        $results = $comparer->verify($strict);

        foreach ($results['tables'] as $table => $info) {
            if ($info['passed']) {
                $this->line("<info>PASS</info> {$table}");
            } else {
                $this->line("<error>FAIL</error> {$table}");
                foreach ($info['errors'] as $error) {
                    $this->line('  - '.$error);
                }
            }
        }

        if (! empty($results['extra_tables'])) {
            $this->warn('Unexpected tables: '.implode(', ', $results['extra_tables']));
        }

        if ($results['passed']) {
            $this->info('Schema verification passed ('.count($results['tables']).' tables).');

            return self::SUCCESS;
        }

        $this->error('Schema verification failed.');

        return self::FAILURE;
    }
}
