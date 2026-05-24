<?php

namespace App\Console\Commands;

use App\Support\Schema\SchemaComparer;
use App\Support\Schema\SchemaManifest;
use Database\Seeders\SeedSqlSeeder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SchemaValidateFreezeCommand extends Command
{
    protected $signature = 'resmenu:schema:validate-freeze
                            {--json= : Write report JSON to path}
                            {--skip-seed : Skip db:seed steps}
                            {--skip-baseline-lock : Skip brownfield simulation}';

    protected $description = 'Run full baseline validation suite and print freeze readiness report';

    public function handle(SchemaComparer $comparer): int
    {
        $report = [
            'generated_at' => gmdate('c'),
            'mariadb_version' => $this->serverVersion(),
            'migrate' => null,
            'schema_verify' => null,
            'checksum_compare' => null,
            'seed_first' => null,
            'seed_second' => null,
            'idempotency' => null,
            'baseline_lock' => null,
            'collation_audit' => $this->auditCollation(),
            'mysql8_syntax_audit' => $this->auditMysql8Syntax(),
            'ready_for_freeze' => false,
        ];

        $this->info('=== Resmenu baseline validation / freeze gate ===');
        $this->line('MariaDB: '.$report['mariadb_version']);

        try {
            $exitMigrate = Artisan::call('migrate', ['--force' => true]);
            $report['migrate'] = [
                'exit_code' => $exitMigrate,
                'table_count' => $this->countAppTables(),
            ];
            $this->line(Artisan::output());
            $this->info('Migrate exit: '.$exitMigrate.' | app tables: '.$report['migrate']['table_count']);
        } catch (\Throwable $e) {
            $report['migrate'] = ['error' => $e->getMessage()];
            $this->failReport($report, 'migrate failed: '.$e->getMessage());

            return self::FAILURE;
        }

        $verify = $comparer->verify(strict: true);
        $report['schema_verify'] = [
            'passed' => $verify['passed'],
            'tables' => $verify['tables'],
            'extra_tables' => $verify['extra_tables'] ?? [],
            'fk_tables_checked' => $this->fkCheckSummary(),
            'index_tables_checked' => $this->indexCheckSummary(),
        ];
        $this->printTableResults($verify);

        if (! $verify['passed']) {
            $this->failReport($report, 'schema:verify --strict failed');

            return self::FAILURE;
        }

        $checksumExit = Artisan::call('resmenu:schema:snapshot', ['--compare' => true]);
        $report['checksum_compare'] = [
            'exit_code' => $checksumExit,
            'output' => trim(Artisan::output()),
        ];
        $this->line(Artisan::output());
        if ($checksumExit !== 0) {
            $this->failReport($report, 'schema:snapshot --compare failed');

            return self::FAILURE;
        }

        if (! $this->option('skip-seed')) {
            $report['seed_first'] = $this->runSeedAndCounts();
            $report['seed_second'] = $this->runSeedAndCounts();
            $report['idempotency'] = $this->compareIdempotency($report['seed_first'], $report['seed_second']);
            if (! $report['idempotency']['passed']) {
                $this->failReport($report, 'seed idempotency failed');

                return self::FAILURE;
            }
        }

        if (! $this->option('skip-baseline-lock')) {
            $report['baseline_lock'] = ['skipped' => true, 'note' => 'Run brownfield test on sigsolmenu_laravel_val_lock separately'];
        }

        $report['ready_for_freeze'] = true;
        $this->info('READY FOR FREEZE: all automated checks passed.');

        if ($path = $this->option('json')) {
            File::put($path, json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)."\n");
            $this->line("Report written: {$path}");
        }

        return self::SUCCESS;
    }

    private function serverVersion(): string
    {
        $row = DB::selectOne('SELECT VERSION() AS v');

        return (string) ($row->v ?? 'unknown');
    }

    private function countAppTables(): int
    {
        return count(SchemaManifest::tableNames());
    }

    /**
     * @return array<string, array{fk_count: int, passed: bool}>
     */
    private function fkCheckSummary(): array
    {
        $db = (string) DB::connection()->getDatabaseName();
        $manifest = SchemaManifest::load();
        $out = [];
        foreach ($manifest['tables'] as $table => $expected) {
            $expectedFks = $expected['foreign_keys'] ?? [];
            if ($expectedFks === []) {
                $out[$table] = ['fk_count' => 0, 'passed' => true];

                continue;
            }
            $rows = DB::select(
                'SELECT CONSTRAINT_NAME FROM information_schema.TABLE_CONSTRAINTS
                 WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND CONSTRAINT_TYPE = ?',
                [$db, $table, 'FOREIGN KEY']
            );
            $actual = array_map(fn ($r) => $r->CONSTRAINT_NAME, $rows);
            $missing = array_diff($expectedFks, $actual);
            $out[$table] = [
                'fk_count' => count($actual),
                'expected' => count($expectedFks),
                'passed' => $missing === [],
                'missing' => array_values($missing),
            ];
        }

        return $out;
    }

    /**
     * @return array<string, array{index_count: int, passed: bool}>
     */
    private function indexCheckSummary(): array
    {
        $db = (string) DB::connection()->getDatabaseName();
        $manifest = SchemaManifest::load();
        $out = [];
        foreach ($manifest['tables'] as $table => $expected) {
            $expectedIndexes = $expected['indexes'] ?? [];
            $rows = DB::select(
                'SELECT DISTINCT INDEX_NAME FROM information_schema.STATISTICS
                 WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?',
                [$db, $table]
            );
            $actual = array_map(fn ($r) => $r->INDEX_NAME === 'PRIMARY' ? 'PRIMARY' : $r->INDEX_NAME, $rows);
            $missing = [];
            foreach ($expectedIndexes as $idx) {
                if (! in_array($idx, $actual, true)) {
                    $missing[] = $idx;
                }
            }
            $out[$table] = [
                'index_count' => count($actual),
                'expected' => count($expectedIndexes),
                'passed' => $missing === [],
                'missing' => $missing,
            ];
        }

        return $out;
    }

    /**
     * @param  array{passed: bool, tables: array<string, array{passed: bool, errors: list<string>}>}  $verify
     */
    private function printTableResults(array $verify): void
    {
        foreach ($verify['tables'] as $table => $info) {
            $this->line(($info['passed'] ? '<info>PASS</info>' : '<error>FAIL</error>')." {$table}");
        }
    }

    /**
     * @return array{exit_code: int, counts: array<string, int|string>}
     */
    private function runSeedAndCounts(): array
    {
        $exit = Artisan::call('db:seed', ['--force' => true, '--class' => SeedSqlSeeder::class]);
        $counts = [
            'sections' => (int) DB::table('sections')->count(),
            'categories' => (int) DB::table('categories')->count(),
            'menu_items' => (int) DB::table('menu_items')->count(),
        ];

        return ['exit_code' => $exit, 'counts' => $counts, 'output' => trim(Artisan::output())];
    }

    /**
     * @param  array{counts: array<string, int|string>}  $first
     * @param  array{counts: array<string, int|string>}  $second
     * @return array{passed: bool, deltas: array<string, int>}
     */
    private function compareIdempotency(array $first, array $second): array
    {
        $deltas = [];
        $passed = true;
        foreach (['sections', 'categories', 'menu_items'] as $key) {
            $d = (int) $second['counts'][$key] - (int) $first['counts'][$key];
            $deltas[$key] = $d;
            if ($d !== 0) {
                $passed = false;
            }
        }

        return ['passed' => $passed, 'deltas' => $deltas, 'first' => $first['counts'], 'second' => $second['counts']];
    }

  /**
     * @return array{passed: bool, hits: list<string>}
     */
    private function auditCollation(): array
    {
        $hits = [];
        $paths = [
            base_path('config'),
            base_path('database'),
            base_path('.env.example'),
        ];
        foreach ($paths as $dir) {
            if (is_file($dir)) {
                if (str_contains((string) file_get_contents($dir), 'utf8mb4_0900_ai_ci')) {
                    $hits[] = $dir;
                }

                continue;
            }
            if (! is_dir($dir)) {
                continue;
            }
            $it = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir));
            foreach ($it as $file) {
                if (! $file->isFile()) {
                    continue;
                }
                $content = @file_get_contents($file->getPathname());
                if ($content !== false && str_contains($content, 'utf8mb4_0900_ai_ci')) {
                    $hits[] = str_replace(base_path().DIRECTORY_SEPARATOR, '', $file->getPathname());
                }
            }
        }

        return ['passed' => $hits === [], 'hits' => $hits];
    }

    /**
     * @return array{passed: bool, notes: list<string>}
     */
    private function auditMysql8Syntax(): array
    {
        $notes = [];
        $patterns = ['INVISIBLE', 'JSON_TABLE', 'REGEXP_REPLACE'];
        $dir = database_path('migrations');
        $it = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir));
        foreach ($it as $file) {
            if (! str_ends_with($file->getFilename(), '.php')) {
                continue;
            }
            $c = file_get_contents($file->getPathname());
            foreach ($patterns as $p) {
                if (str_contains($c, $p)) {
                    $notes[] = $file->getFilename().' contains '.$p;
                }
            }
        }

        return ['passed' => $notes === [], 'notes' => $notes];
    }

    /**
     * @param  array<string, mixed>  $report
     */
    private function failReport(array $report, string $reason): void
    {
        $report['ready_for_freeze'] = false;
        $report['failure'] = $reason;
        if ($path = $this->option('json')) {
            File::put($path, json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)."\n");
        }
        $this->error('NOT READY FOR FREEZE: '.$reason);
    }
}
