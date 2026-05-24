<?php

/**
 * One-shot generator: baseline migrations + MIGRATION_MANIFEST.json from canonical dump.
 *
 * Usage: php database/scripts/generate_baseline.php
 */

declare(strict_types=1);

$basePath = dirname(__DIR__, 2);
$dumpPath = $basePath.'/database/schema/sigsolmenu_laravel.sql';
$baselineDir = $basePath.'/database/migrations/baseline';
$manifestPath = $basePath.'/database/schema/MIGRATION_MANIFEST.json';
$structurePath = $basePath.'/database/schema/sigsolmenu_laravel.structure.sql';

if (! is_file($dumpPath)) {
    fwrite(STDERR, "Dump not found: {$dumpPath}\n");
    exit(1);
}

$dump = file_get_contents($dumpPath);

// --- Extract CREATE TABLE blocks ---
$createTables = [];
if (preg_match_all('/CREATE TABLE `([^`]+)` \((.*?)\) ENGINE=InnoDB/s', $dump, $matches, PREG_SET_ORDER)) {
    foreach ($matches as $m) {
        $createTables[$m[1]] = "CREATE TABLE `{$m[1]}` ({$m[2]}) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    }
}

// --- Extract index / auto_increment / FK sections ---
$indexSection = extractSection($dump, '-- Indexes for dumped tables', '-- AUTO_INCREMENT for dumped tables');
$autoSection = extractSection($dump, '-- AUTO_INCREMENT for dumped tables', '-- Constraints for dumped tables');
$fkSection = extractSection($dump, '-- Constraints for dumped tables', 'COMMIT;');

$indexAlters = parseAlterBlocks($indexSection);
$autoAlters = [];
foreach (parseAlterBlocks($autoSection) as $table => $sql) {
    $autoAlters[$table] = [preg_replace('/, AUTO_INCREMENT=\d+/', '', $sql) ?? $sql];
}
$fkAlters = parseAlterBlocks($fkSection);

$tableOrder = array_keys($createTables);
sort($tableOrder, SORT_STRING);
// Preserve dump dependency-friendly order (as appeared in file)
$dumpOrder = array_keys($createTables);

// --- Build structure-only SQL file ---
$structureSql = "-- Resmenu baseline structure (generated). Do not edit by hand; regenerate via generate_baseline.php\n";
$structureSql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";
foreach ($dumpOrder as $table) {
    $structureSql .= $createTables[$table]."\n\n";
}
$structureSql .= "-- Indexes and keys\n\n";
foreach ($dumpOrder as $table) {
    if (isset($indexAlters[$table])) {
        $structureSql .= $indexAlters[$table]."\n\n";
    }
}
$structureSql .= "-- AUTO_INCREMENT columns\n\n";
foreach ($dumpOrder as $table) {
    if (! empty($autoAlters[$table])) {
        foreach ($autoAlters[$table] as $line) {
            $structureSql .= $line."\n";
        }
        $structureSql .= "\n";
    }
}
$structureSql .= "-- Foreign keys\n\n";
foreach (array_keys($fkAlters) as $table) {
    $structureSql .= $fkAlters[$table]."\n\n";
}
$structureSql .= "SET FOREIGN_KEY_CHECKS=1;\n";
file_put_contents($structurePath, $structureSql);

// --- Build manifest from information_schema-style parsing of CREATE ---
$manifest = [
    'version' => 'schema-baseline-v1',
    'generated_at' => gmdate('c'),
    'source_dump' => 'database/schema/sigsolmenu_laravel.sql',
    'tables' => [],
];

foreach ($dumpOrder as $table) {
    $ddl = $createTables[$table];
    $columns = parseColumnsFromCreate($ddl);
    $manifest['tables'][$table] = [
        'columns' => $columns,
        'indexes' => extractIndexNames($indexAlters[$table] ?? ''),
        'foreign_keys' => extractFkNames($fkAlters[$table] ?? ''),
    ];
}

file_put_contents($manifestPath, json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)."\n");

// --- Generate per-table CREATE migrations ---
if (! is_dir($baselineDir)) {
    mkdir($baselineDir, 0755, true);
}

// Clear old baseline migrations
foreach (glob($baselineDir.'/*.php') ?: [] as $old) {
    unlink($old);
}

$seq = 1;
foreach ($dumpOrder as $table) {
    $stamp = sprintf('2026_05_19_%06d', $seq);
    $sql = var_export($createTables[$table], true);
    $content = <<<PHP
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Baseline migration (schema-baseline-v1). IMMUTABLE after freeze — fix forward only.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared({$sql});
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `{$table}`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};

PHP;
    file_put_contents("{$baselineDir}/{$stamp}_create_{$table}_table.php", $content);
    $seq++;
}

// Indexes migration
$indexSql = implode("\n\n", array_values($indexAlters));
writeBatchMigration($baselineDir, sprintf('2026_05_19_%06d', $seq), $indexSql, 'baseline_indexes');
$seq++;

// Auto increment migration
$autoLines = [];
foreach ($dumpOrder as $table) {
    if (! empty($autoAlters[$table])) {
        foreach ($autoAlters[$table] as $line) {
            $autoLines[] = $line;
        }
    }
}
writeBatchMigration($baselineDir, sprintf('2026_05_19_%06d', $seq), implode("\n", $autoLines), 'baseline_auto_increment');
$seq++;

// FK migration
$fkSql = "SET FOREIGN_KEY_CHECKS=0;\n\n".implode("\n\n", array_values($fkAlters))."\n\nSET FOREIGN_KEY_CHECKS=1;";
writeBatchMigration($baselineDir, sprintf('2026_05_19_%06d', $seq), $fkSql, 'baseline_foreign_keys');

$expectedSnapshot = [];
foreach ($dumpOrder as $table) {
    $blob = $createTables[$table];
    if (isset($indexAlters[$table])) {
        $blob .= "\n".$indexAlters[$table];
    }
    if (! empty($autoAlters[$table])) {
        $blob .= "\n".implode("\n", $autoAlters[$table]);
    }
    if (isset($fkAlters[$table])) {
        $blob .= "\n".$fkAlters[$table];
    }
    $expectedSnapshot[$table] = normalizeSnapshotBlob($blob);
}
ksort($expectedSnapshot);
$checksum = hash('sha256', json_encode($expectedSnapshot, JSON_THROW_ON_ERROR));
file_put_contents($basePath.'/database/schema/schema-snapshot.checksum', $checksum.PHP_EOL);
file_put_contents(
    $basePath.'/database/schema/expected-snapshot.json',
    json_encode($expectedSnapshot, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)."\n"
);

echo "Generated ".count($dumpOrder)." table migrations + 3 batch migrations\n";
echo "Manifest: {$manifestPath}\n";
echo "Structure SQL: {$structurePath}\n";
echo "Checksum: {$checksum}\n";

function writeBatchMigration(string $dir, string $stamp, string $sql, string $suffix): void
{
    $file = "{$dir}/{$stamp}_{$suffix}.php";
    $chunks = preg_split('/;\s*(?=\r?\n(?:ALTER TABLE|SET FOREIGN_KEY_CHECKS))/i', trim($sql)) ?: [];
    $statements = [];
    foreach ($chunks as $chunk) {
        $chunk = trim($chunk);
        if ($chunk === '') {
            continue;
        }
        $statements[] = str_ends_with($chunk, ';') ? $chunk : $chunk.';';
    }
    $exported = var_export($statements, true);
    $content = <<<PHP
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Baseline migration (schema-baseline-v1). IMMUTABLE after freeze — fix forward only.
 */
return new class extends Migration
{
    public function up(): void
    {
        foreach ({$exported} as \$statement) {
            if (preg_match('/^SET FOREIGN_KEY_CHECKS=(0|1);?\$/i', \$statement)) {
                DB::statement(rtrim(\$statement, ';'));
            } else {
                DB::unprepared(\$statement);
            }
        }
    }

    public function down(): void
    {
        // Baseline batch rollback not supported; use migrate:fresh only on empty dev DBs.
    }
};

PHP;
    file_put_contents($file, $content);
}

function parseColumnsFromCreate(string $ddl): array
{
    if (! preg_match('/CREATE TABLE `[^`]+` \((.*)\) ENGINE/s', $ddl, $m)) {
        return [];
    }
    $body = $m[1];
    $parts = preg_split('/,\n/', $body);
    $columns = [];
    foreach ($parts as $part) {
        $part = trim($part);
        if ($part === '' || preg_match('/^(PRIMARY KEY|UNIQUE KEY|KEY|CONSTRAINT)/i', $part)) {
            continue;
        }
        if (preg_match('/^`([^`]+)`\s+(.+)$/s', $part, $col)) {
            $columns[$col[1]] = [
                'definition' => normalizeWhitespace($col[2]),
            ];
        }
    }

    return $columns;
}

function normalizeWhitespace(string $s): string
{
    return preg_replace('/\s+/', ' ', trim($s)) ?? $s;
}

function extractSection(string $dump, string $startMarker, string $endMarker): string
{
    $start = strpos($dump, $startMarker);
    $end = strpos($dump, $endMarker, $start !== false ? $start : 0);
    if ($start === false || $end === false) {
        return '';
    }

    return substr($dump, $start, $end - $start);
}

/**
 * @return array<string, string>
 */
function parseAlterBlocks(string $section): array
{
    $blocks = [];
    if (preg_match_all('/ALTER TABLE `([^`]+)`\s+((?:[^;]|(?:\([^)]*\)))+);/s', $section, $matches, PREG_SET_ORDER)) {
        foreach ($matches as $m) {
            $blocks[$m[1]] = 'ALTER TABLE `'.$m[1]."`\n".rtrim($m[2]).';';
        }
    }

    return $blocks;
}

function extractIndexNames(string $alter): array
{
    $names = [];
    if (str_contains($alter, 'PRIMARY KEY')) {
        $names[] = 'PRIMARY';
    }
    if (preg_match_all('/ADD UNIQUE KEY `([^`]+)`/', $alter, $unique)) {
        $names = array_merge($names, $unique[1]);
    }
    if (preg_match_all('/ADD KEY `([^`]+)`/', $alter, $keys)) {
        $names = array_merge($names, $keys[1]);
    }

    return $names;
}

function normalizeSnapshotBlob(string $sql): string
{
    $sql = preg_replace('/ AUTO_INCREMENT=\d+/', '', $sql) ?? $sql;

    return preg_replace('/\s+/', ' ', trim($sql)) ?? $sql;
}

function extractFkNames(string $alter): array
{
    $names = [];
    if (preg_match_all('/ADD CONSTRAINT `([^`]+)`/', $alter, $m)) {
        $names = $m[1];
    }

    return $names;
}
