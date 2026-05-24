<?php

namespace App\Support\Schema;

use Illuminate\Support\Facades\DB;

class SchemaComparer
{
    /**
     * @return array{passed: bool, tables: array<string, array{passed: bool, errors: list<string>}>}
     */
    public function verify(bool $strict = false): array
    {
        $manifest = SchemaManifest::load();
        $database = (string) DB::connection()->getDatabaseName();
        $results = ['passed' => true, 'tables' => []];

        foreach ($manifest['tables'] as $table => $expected) {
            $errors = $this->verifyTable($database, $table, $expected, $strict);
            $tablePassed = $errors === [];
            $results['tables'][$table] = [
                'passed' => $tablePassed,
                'errors' => $errors,
            ];
            if (! $tablePassed) {
                $results['passed'] = false;
            }
        }

        $extra = $this->unexpectedTables($database, array_keys($manifest['tables']));
        if ($extra !== []) {
            $results['passed'] = false;
            $results['extra_tables'] = $extra;
        }

        return $results;
    }

    /**
     * @param  array<string, mixed>  $expected
     * @return list<string>
     */
    private function verifyTable(string $database, string $table, array $expected, bool $strict): array
    {
        $errors = [];

        $exists = DB::selectOne(
            'SELECT COUNT(*) AS c FROM information_schema.tables WHERE table_schema = ? AND table_name = ?',
            [$database, $table]
        );
        if ((int) ($exists->c ?? 0) === 0) {
            return ["Table `{$table}` does not exist."];
        }

        $columns = DB::select(
            'SELECT column_name, column_type, is_nullable, column_default, extra
             FROM information_schema.columns
             WHERE table_schema = ? AND table_name = ?
             ORDER BY ordinal_position',
            [$database, $table]
        );

        $expectedColumns = $expected['columns'] ?? [];
        $actualNames = [];
        foreach ($columns as $col) {
            $actualNames[] = $col->column_name;
            if (! isset($expectedColumns[$col->column_name])) {
                $errors[] = "Column `{$table}.{$col->column_name}` exists in DB but not in manifest.";

                continue;
            }
            $def = $this->normalizeDefinition((string) $col->column_type, (string) $col->is_nullable, $col->column_default, (string) $col->extra);
            $expDef = $this->normalizeManifestColumn($expectedColumns[$col->column_name]['definition'] ?? '');
            if ($strict && ! $this->definitionsEquivalent($expDef, $def)) {
                $errors[] = "Column `{$table}.{$col->column_name}` mismatch.\n  expected: {$expDef}\n  actual:   {$def}";
            }
        }

        foreach (array_keys($expectedColumns) as $name) {
            if (! in_array($name, $actualNames, true)) {
                $errors[] = "Column `{$table}.{$name}` missing from database.";
            }
        }

        if ($strict) {
            $create = DB::selectOne("SHOW CREATE TABLE `{$table}`");
            $createSql = (string) ($create->{'Create Table'} ?? '');
            foreach ($expectedColumns as $colName => $colMeta) {
                $manifestDef = (string) ($colMeta['definition'] ?? '');
                if (preg_match('/check\s*\(\s*json_valid\s*\(`/i', $manifestDef)) {
                    if (! preg_match('/`'.preg_quote($colName, '/').'`[^,]+check\s*\(\s*json_valid\s*\(`/i', $createSql)) {
                        $errors[] = "Missing JSON CHECK on `{$table}.{$colName}`.";
                    }
                }
            }
            foreach ($expected['indexes'] ?? [] as $indexName) {
                if ($indexName === 'PRIMARY') {
                    if (! str_contains($createSql, 'PRIMARY KEY')) {
                        $errors[] = "Missing PRIMARY KEY on `{$table}`.";
                    }
                } elseif (! str_contains($createSql, '`'.$indexName.'`')) {
                    $errors[] = "Missing index `{$indexName}` on `{$table}`.";
                }
            }
            foreach ($expected['foreign_keys'] ?? [] as $fkName) {
                if (! str_contains($createSql, '`'.$fkName.'`')) {
                    $errors[] = "Missing foreign key `{$fkName}` on `{$table}`.";
                }
            }
        }

        return $errors;
    }

  /**
     * @param  list<string>  $expectedTables
     * @return list<string>
     */
    private function unexpectedTables(string $database, array $expectedTables): array
    {
        $ignore = ['migrations', 'cache', 'cache_locks', 'jobs', 'job_batches', 'failed_jobs', 'sessions'];
        $rows = DB::select(
            'SELECT table_name FROM information_schema.tables WHERE table_schema = ? AND table_type = ?',
            [$database, 'BASE TABLE']
        );
        $extra = [];
        foreach ($rows as $row) {
            $name = $row->table_name;
            if (in_array($name, $ignore, true)) {
                continue;
            }
            if (! in_array($name, $expectedTables, true)) {
                $extra[] = $name;
            }
        }

        return $extra;
    }

    private function normalizeDefinition(string $type, string $nullable, mixed $default, string $extra): string
    {
        return $this->normalizeColumnDefinitionString($this->buildDefinitionString(
            strtolower($type),
            $nullable === 'YES',
            $default,
            strtolower($extra)
        ));
    }

    private function normalizeManifestColumn(string $definition): string
    {
        return $this->normalizeColumnDefinitionString(strtolower($definition));
    }

    private function buildDefinitionString(string $type, bool $nullable, mixed $default, string $extra): string
    {
        $null = $nullable ? 'null' : 'not null';
        $defaultStr = $default === null ? '' : ' default '.strtolower((string) $default);
        $extra = trim($extra);

        return trim("{$type} {$null}{$defaultStr}".($extra !== '' ? ' '.$extra : ''));
    }

    private function normalizeColumnDefinitionString(string $definition): string
    {
        $definition = preg_replace('/\s+check\s*\(\s*json_valid\s*\(`[^`]+`\)\s*\)/i', '', $definition) ?? $definition;
        $definition = preg_replace('/\s+comment\s+\'[^\']*\'/i', '', $definition) ?? $definition;
        $definition = preg_replace('/character set \w+ collate \w+/i', '', $definition) ?? $definition;
        $definition = str_replace(['current_timestamp()', 'current_timestamp()'], 'current_timestamp', $definition);
        $definition = preg_replace('/\bnull\s+default\b/', 'default', $definition) ?? $definition;
        $definition = preg_replace('/\s+/', ' ', trim($definition)) ?? $definition;

        return $definition;
    }

    private function definitionsEquivalent(string $expected, string $actual): bool
    {
        if ($expected === $actual) {
            return true;
        }

        $stripAi = static fn (string $d): string => trim(preg_replace('/\bauto_increment\b/', '', $d) ?? $d);
        if ($stripAi($expected) === $stripAi($actual)) {
            return true;
        }

        // MariaDB reports nullable+default differently than dump DDL for many columns.
        $expectedParts = $this->parseDefinitionParts($expected);
        $actualParts = $this->parseDefinitionParts($actual);
        if ($expectedParts === null || $actualParts === null) {
            return false;
        }

        return $expectedParts['type'] === $actualParts['type']
            && $expectedParts['nullable'] === $actualParts['nullable']
            && $expectedParts['default'] === $actualParts['default']
            && $stripAi($expectedParts['extra']) === $stripAi($actualParts['extra']);
    }

    /**
     * @return array{type: string, nullable: bool, default: ?string, extra: string}|null
     */
    private function parseDefinitionParts(string $definition): ?array
    {
        if (! preg_match('/^(\S+(?:\s*\([^)]*\))?)\s+(not null|null)(.*)$/i', $definition, $m)) {
            return null;
        }
        $nullable = strtolower($m[2]) === 'null';
        $rest = trim($m[3]);
        $default = null;
        $extra = '';
        if (preg_match('/\bdefault\s+(.+)$/i', $rest, $dm)) {
            $default = trim($dm[1]);
            $rest = trim((string) preg_replace('/\bdefault\s+.+$/i', '', $rest));
        }
        $extra = trim($rest);

        return [
            'type' => strtolower($m[1]),
            'nullable' => $nullable,
            'default' => $default,
            'extra' => $extra,
        ];
    }

    /**
     * @return array<string, string> table => normalized CREATE TABLE
     */
    public function snapshotCreateTables(): array
    {
        $tables = SchemaManifest::tableNames();
        $out = [];
        foreach ($tables as $table) {
            $row = DB::selectOne("SHOW CREATE TABLE `{$table}`");
            $sql = $row->{'Create Table'} ?? '';
            $out[$table] = $this->normalizeCreateSql($sql);
        }
        ksort($out);

        return $out;
    }

    public function checksum(): string
    {
        $snapshot = $this->snapshotCreateTables();

        return hash('sha256', json_encode($snapshot, JSON_THROW_ON_ERROR));
    }

    public function checksumFromExpectedFile(): string
    {
        $path = database_path('schema/expected-snapshot.json');
        if (! file_exists($path)) {
            throw new \RuntimeException("Missing expected snapshot: {$path}");
        }

        /** @var array<string, string> $expected */
        $expected = json_decode((string) file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);

        return hash('sha256', json_encode($expected, JSON_THROW_ON_ERROR));
    }

    /**
     * @return array{passed: bool, errors: list<string>}
     */
    public function compareToExpectedSnapshot(): array
    {
        $path = database_path('schema/expected-snapshot.json');
        if (! file_exists($path)) {
            return ['passed' => false, 'errors' => ['expected-snapshot.json not found. Run generate_baseline.php.']];
        }

        /** @var array<string, string> $expected */
        $expected = json_decode((string) file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);
        $live = $this->snapshotCreateTables();
        $errors = [];

        foreach ($expected as $table => $exp) {
            if (! isset($live[$table])) {
                $errors[] = "Table `{$table}` missing from live database.";

                continue;
            }
            if ($live[$table] !== $exp) {
                $errors[] = "Table `{$table}` DDL drift (normalized SHOW CREATE vs expected snapshot).";
            }
        }

        foreach (array_diff(array_keys($live), array_keys($expected)) as $extra) {
            $errors[] = "Unexpected table in live snapshot: `{$extra}`.";
        }

        return ['passed' => $errors === [], 'errors' => $errors];
    }

    private function normalizeCreateSql(string $sql): string
    {
        $sql = preg_replace('/ AUTO_INCREMENT=\d+/', '', $sql) ?? $sql;

        return preg_replace('/\s+/', ' ', trim($sql)) ?? $sql;
    }
}
