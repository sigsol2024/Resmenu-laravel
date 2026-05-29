<?php

/**
 * Compare production dump (Resmenu/database/sigsolmenu_resmenu.sql) against seed-sql coverage.
 * Run: php database/scripts/audit_production_data.php
 */

$dumpPath = dirname(__DIR__, 3).'/Resmenu/database/sigsolmenu_resmenu.sql';
if (! is_file($dumpPath)) {
    fwrite(STDERR, "Dump not found: {$dumpPath}\n");
    exit(1);
}

$sql = file_get_contents($dumpPath);

preg_match_all(
    "/INSERT INTO `restaurants`[^;]+VALUES\s*(.+);/s",
    $sql,
    $restaurantBlock,
);

$restaurants = [];
if (! empty($restaurantBlock[1][0])) {
    preg_match_all(
        "/\((\d+),\s*'((?:[^'\\\\]|\\\\.)*)',\s*'((?:[^'\\\\]|\\\\.)*)'/",
        $restaurantBlock[1][0],
        $rows,
        PREG_SET_ORDER,
    );
    foreach ($rows as $row) {
        $restaurants[] = [
            'id' => (int) $row[1],
            'name' => stripcslashes($row[2]),
            'slug' => stripcslashes($row[3]),
        ];
    }
}

$seedSlugs = [
    'the-lusso-restaurant',
    'the-mania-house', // fixed from mania-house
    'opal-cafe-menu',  // fixed from opal-lagos
    'salt-and-social',
    'swiss-the-vistana',
    'vendome-cafe-s-menu',
];

$seedEmails = [
    'restaurant@lussohotelsabuja.com',
    'admin@maniahouse.our-menu.online',
    'opallagos1@gmail.com',
    'admin@saltandsocial.our-menu.online',
    'it.vistana@swissinternationalhotels.com',
    'admin@vendomecafe.our-menu.online',
];

echo "=== Production restaurants (".count($restaurants).") ===\n\n";
foreach ($restaurants as $r) {
    $hasSeed = in_array($r['slug'], $seedSlugs, true);
    echo sprintf(
        "id=%d slug=%-22s %s %s\n",
        $r['id'],
        $r['slug'],
        $hasSeed ? '[seed-sql menu]' : '[NO menu seed]',
        $r['name'],
    );
}

echo "\n=== Tables with INSERT row counts (approx) ===\n";
preg_match_all('/INSERT INTO `([^`]+)`/', $sql, $insertTables);
$counts = array_count_values($insertTables[1]);
ksort($counts);
foreach ($counts as $table => $n) {
    echo "  {$table}: {$n} insert statement(s)\n";
}

echo "\n=== Laravel-only schema (post-migrate) ===\n";
echo "  subscription_email_log — empty until reminder cron runs\n";
