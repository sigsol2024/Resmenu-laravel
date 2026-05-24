<?php

namespace App\Console\Commands;

use App\Support\Schema\SchemaComparer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SchemaSnapshotCommand extends Command
{
    protected $signature = 'resmenu:schema:snapshot
                            {--write-checksum : Write checksum to database/schema/schema-snapshot.checksum}
                            {--compare : Verify committed checksum matches expected-snapshot.json}
                            {--strict : With --compare, also diff live SHOW CREATE vs expected-snapshot.json}';

    protected $description = 'Export normalized schema checksum for CI drift detection';

    public function handle(SchemaComparer $comparer): int
    {
        $checksum = $comparer->checksum();
        $this->line("Schema checksum: {$checksum}");

        $path = database_path('schema/schema-snapshot.checksum');

        if ($this->option('write-checksum')) {
            $snapshot = $comparer->snapshotCreateTables();
            $expectedPath = database_path('schema/expected-snapshot.json');
            File::put($expectedPath, json_encode($snapshot, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)."\n");
            File::put($path, $checksum.PHP_EOL);
            $this->info("Wrote {$path}");
            $this->info("Wrote {$expectedPath}");

            return self::SUCCESS;
        }

        if ($this->option('compare')) {
            if (! File::exists($path)) {
                $this->error("Checksum file missing: {$path}. Run generate_baseline.php or --write-checksum after migrate.");

                return self::FAILURE;
            }
            $committed = trim(File::get($path));
            $fromFile = $comparer->checksumFromExpectedFile();
            if (! hash_equals($committed, $fromFile)) {
                $this->error('schema-snapshot.checksum does not match expected-snapshot.json. Regenerate baseline artifacts.');

                return self::FAILURE;
            }

            if ($this->option('strict')) {
                $drift = $comparer->compareToExpectedSnapshot();
                if (! $drift['passed']) {
                    $this->error('Live schema drift vs expected-snapshot.json:');
                    foreach ($drift['errors'] as $error) {
                        $this->line('  - '.$error);
                    }

                    return self::FAILURE;
                }
            } else {
                $this->line("Live checksum (informational): {$checksum}");
            }

            $this->info('Committed schema artifacts are consistent.');

            return self::SUCCESS;
        }

        return self::SUCCESS;
    }
}
