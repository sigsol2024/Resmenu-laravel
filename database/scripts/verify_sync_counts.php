<?php

/**
 * Verify generated sync SQL matches dump EXPECTED counts for sample restaurants.
 * Usage: php database/scripts/verify_sync_counts.php [output-dir]
 */

require_once __DIR__.'/lib/DumpParser.php';
require_once __DIR__.'/lib/SyncSqlGenerator.php';

$outputDir = $argv[1] ?? dirname(__DIR__).'/seed-sql/production';
$source = dirname(__DIR__, 3).'/Resmenu/database/sigsolmenu_resmenu.sql';

if (! is_file($source)) {
    fwrite(STDERR, "Source dump not found: {$source}\n");
    exit(1);
}

$sql = file_get_contents($source);
$parser = new DumpParser($sql);
$data = $parser->parse();

$generator = new SyncSqlGenerator($data, [
    'source' => $source,
    'dry_run' => true,
]);
$result = $generator->generate();
$expected = $result['expected'];

$checks = [
    'opal-cafe-menu' => 'sync_part_04_opal-cafe-menu.sql',
    'the-mania-house' => 'sync_part_08_the-mania-house.sql',
];

$failed = false;

foreach ($checks as $slug => $filename) {
    $path = rtrim($outputDir, '/\\').DIRECTORY_SEPARATOR.$filename;
    if (! is_file($path)) {
        fwrite(STDERR, "Missing generated file: {$path}\n");
        $failed = true;
        continue;
    }

    $content = file_get_contents($path);
    preg_match('/-- EXPECTED: sections=(\d+) categories=(\d+) menu_items=(\d+)/', $content, $header);
    $insertSections = preg_match_all('/INSERT INTO `sections`/i', $content);
    $insertCategories = preg_match_all('/INSERT INTO `categories`/i', $content);
    $insertMenuItems = preg_match_all('/INSERT INTO `menu_items`/i', $content);

    $exp = $expected[$slug] ?? null;
    if ($exp === null) {
        fwrite(STDERR, "No dry-run expected data for {$slug}\n");
        $failed = true;
        continue;
    }

    $ok = $exp['sections'] === $insertSections
        && $exp['categories'] === $insertCategories
        && $exp['menu_items'] === $insertMenuItems
        && (int) ($header[1] ?? -1) === $exp['sections']
        && (int) ($header[2] ?? -1) === $exp['categories']
        && (int) ($header[3] ?? -1) === $exp['menu_items'];

    echo ($ok ? 'OK' : 'FAIL')." {$slug}:\n";
    echo "  dump expected: sections={$exp['sections']} categories={$exp['categories']} menu_items={$exp['menu_items']}\n";
    echo "  SQL header:    sections=".($header[1] ?? '?')." categories=".($header[2] ?? '?')." menu_items=".($header[3] ?? '?')."\n";
    echo "  SQL inserts:   sections={$insertSections} categories={$insertCategories} menu_items={$insertMenuItems}\n";

    if (! $ok) {
        $failed = true;
    }
}

exit($failed ? 1 : 0);
