<?php

/**
 * Rewrites seed_lusso_drinks.sql: replace LAST_INSERT_ID category vars with SELECT id by slug.
 */

$path = dirname(__DIR__).'/seed-sql/restaurants/seed_lusso_drinks.sql';
$sql = file_get_contents($path);

$slugByCat = [
    1 => 'soft-drinks-water',
    2 => 'juices',
    3 => 'energy-drinks',
    4 => 'beers',
    5 => 'aperitif',
    6 => 'gin',
    7 => 'whisky-regular-blend',
    8 => 'whisky-single-malt',
    9 => 'whisky-premium-blend',
    10 => 'whisky-american-irish',
    11 => 'vodka',
    12 => 'rum',
    13 => 'cognac',
    14 => 'tequila',
    15 => 'liquor',
    16 => 'hot-beverages',
    17 => 'white-wine',
    18 => 'red-wine',
    19 => 'rose-wine',
    20 => 'champagne',
];

foreach ($slugByCat as $n => $slug) {
    $sql = preg_replace(
        '/SET @cat'.$n.' = LAST_INSERT_ID\(\);/',
        "SET @cat{$n} = (SELECT id FROM categories WHERE restaurant_id = @rid AND slug = '{$slug}' LIMIT 1);",
        $sql
    );
}

$sql = preg_replace(
    '/^-- Run this AFTER migration\.sql\. Safe to re-run: categories use NOT EXISTS guards\.$/m',
    '-- Safe to re-run: categories use NOT EXISTS; @catN resolved by slug (mania pattern).',
    $sql
);

$sql = preg_replace(
    '/^-- Menu items: only insert when section was created.*$/m',
    '-- Menu items: require @rid and category id from slug lookup',
    $sql
);

file_put_contents($path, $sql);
echo "Updated {$path}\n";
