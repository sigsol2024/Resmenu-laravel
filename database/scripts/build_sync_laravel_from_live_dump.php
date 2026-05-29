<?php

/**
 * Generate slug-based sync SQL from a live production dump for Laravel Resmenu.
 *
 * Usage:
 *   php database/scripts/build_sync_laravel_from_live_dump.php [source.sql] [output-dir]
 *     [--include-scans] [--include-inventory] [--force-passwords] [--dry-run]
 */

declare(strict_types=1);

require_once __DIR__.'/lib/DumpParser.php';
require_once __DIR__.'/lib/SyncSqlGenerator.php';

$args = array_slice($argv, 1);
$flags = [
    'include_scans' => false,
    'include_inventory' => false,
    'force_passwords' => false,
    'dry_run' => false,
];

$positional = [];
foreach ($args as $arg) {
    if ($arg === '--include-scans') {
        $flags['include_scans'] = true;
    } elseif ($arg === '--include-inventory') {
        $flags['include_inventory'] = true;
    } elseif ($arg === '--force-passwords') {
        $flags['force_passwords'] = true;
    } elseif ($arg === '--dry-run') {
        $flags['dry_run'] = true;
    } elseif (str_starts_with($arg, '--')) {
        fwrite(STDERR, "Unknown flag: {$arg}\n");
        exit(1);
    } else {
        $positional[] = $arg;
    }
}

$source = $positional[0] ?? dirname(__DIR__, 3).'/Resmenu/database/sigsolmenu_resmenu.sql';
$outputDir = $positional[1] ?? dirname(__DIR__).'/seed-sql/production';

if (! is_file($source)) {
    fwrite(STDERR, "Source not found: {$source}\n");
    exit(1);
}

if (! is_dir($outputDir) && ! $flags['dry_run']) {
    mkdir($outputDir, 0755, true);
}

$sql = file_get_contents($source);
if ($sql === false) {
    fwrite(STDERR, "Failed to read source file.\n");
    exit(1);
}

$parser = new DumpParser($sql);
$data = $parser->parse();

$generator = new SyncSqlGenerator($data, [
    'source' => $source,
    'source_hash' => md5($sql),
    'source_size' => strlen($sql),
    'include_scans' => $flags['include_scans'],
    'include_inventory' => $flags['include_inventory'],
    'force_passwords' => $flags['force_passwords'],
    'dry_run' => $flags['dry_run'],
]);

$result = $generator->generate();

if ($flags['dry_run']) {
    echo "Dry run — expected counts per restaurant:\n";
    foreach ($result['expected'] as $slug => $counts) {
        echo sprintf(
            "  %s: sections=%d categories=%d menu_items=%d orders=%d reservations=%d payments=%d\n",
            $slug,
            $counts['sections'],
            $counts['categories'],
            $counts['menu_items'],
            $counts['orders'],
            $counts['reservations'],
            $counts['payments']
        );
    }
    if ($result['warnings'] !== []) {
        echo "\nWarnings:\n";
        foreach ($result['warnings'] as $warning) {
            echo "  - {$warning}\n";
        }
    }
    exit($result['warnings'] !== [] ? 1 : 0);
}

$written = [];
foreach ($result['files'] as $filename => $content) {
    $path = rtrim($outputDir, '/\\').DIRECTORY_SEPARATOR.$filename;
    file_put_contents($path, $content);
    $written[] = $path;
}

echo 'Generated '.count($written)." sync file(s) in {$outputDir}:\n";
foreach ($written as $path) {
    echo '  '.basename($path).' ('.number_format(filesize($path)).' bytes)'."\n";
}

if ($result['warnings'] !== []) {
    echo "\nWarnings:\n";
    foreach ($result['warnings'] as $warning) {
        echo "  - {$warning}\n";
    }
}
