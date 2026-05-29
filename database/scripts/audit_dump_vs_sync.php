<?php

/**
 * Deep audit: compare sigsolmenu_resmenu.sql parsed data vs sync generator coverage.
 */

declare(strict_types=1);

require_once __DIR__.'/lib/DumpParser.php';
require_once __DIR__.'/lib/SyncSqlGenerator.php';

$source = $argv[1] ?? dirname(__DIR__, 3).'/Resmenu/database/sigsolmenu_resmenu.sql';
$sql = file_get_contents($source);
$parser = new DumpParser($sql);
$data = $parser->parse();

$gen = new SyncSqlGenerator($data, ['source' => $source, 'dry_run' => true]);
$result = $gen->generate();
$expected = $result['expected'];
$warnings = $result['warnings'];

// Active restaurant prod IDs
$activeProdIds = [];
$inactiveRestaurants = [];
foreach ($data['restaurants'] ?? [] as $r) {
    if ((int) ($r['is_active'] ?? 0) === 1) {
        $activeProdIds[(int) $r['id']] = (string) $r['slug'];
    } else {
        $inactiveRestaurants[] = $r;
    }
}

echo "=== DUMP OVERVIEW ===\n";
echo 'Source: '.basename($source).' ('.number_format(strlen($sql))." bytes)\n";
echo 'Tables with INSERT data: '.count($data)."\n";
echo 'Active restaurants: '.count($activeProdIds)."\n";
echo 'Inactive restaurants in dump: '.count($inactiveRestaurants)."\n";
if ($inactiveRestaurants !== []) {
    foreach ($inactiveRestaurants as $r) {
        echo "  - id={$r['id']} slug={$r['slug']} name={$r['name']}\n";
    }
}

// Per-table row counts in dump
echo "\n=== DUMP ROW COUNTS (all restaurants) ===\n";
$restaurantScoped = [
    'sections', 'categories', 'menu_items', 'managers', 'subscriptions',
    'customization_settings', 'restaurant_payment_settings', 'restaurant_qr_codes',
    'restaurant_reservation_settings', 'orders', 'table_reservations', 'payments',
    'pending_bank_transfers', 'pending_online_payments', 'qr_code_scans', 'table_inventory_daily',
];
$platformTables = [
    'admins', 'subscription_plans', 'templates', 'template_customizations', 'template_plans',
    'template_restaurants', 'qr_templates', 'site_settings', 'payment_settings',
];
foreach (array_merge($platformTables, $restaurantScoped) as $table) {
    $n = count($data[$table] ?? []);
    if ($n > 0) {
        echo sprintf("  %-35s %6d\n", $table, $n);
    }
}

// Skipped tables
$skipped = ['login_attempts', 'password_reset_tokens', 'subscription_change_requests', 'subscription_emails'];
echo "\n=== INTENTIONALLY SKIPPED (no INSERT in dump or by design) ===\n";
foreach ($skipped as $t) {
    echo sprintf("  %-35s %6d rows in dump\n", $t, count($data[$t] ?? []));
}

// Category secondary sections
$cssTotal = count($data['category_secondary_sections'] ?? []);
echo "\n=== category_secondary_sections ===\n";
echo "  Total in dump: {$cssTotal}\n";
$cssByRest = [];
foreach ($data['category_secondary_sections'] ?? [] as $row) {
    $catId = (int) $row['category_id'];
    $restId = null;
    foreach ($data['categories'] ?? [] as $c) {
        if ((int) $c['id'] === $catId) {
            $restId = (int) $c['restaurant_id'];
            break;
        }
    }
    if ($restId !== null) {
        $cssByRest[$restId] = ($cssByRest[$restId] ?? 0) + 1;
    }
}

// Per active restaurant detailed comparison
echo "\n=== PER ACTIVE RESTAURANT (dump vs generator EXPECTED) ===\n";
printf("%-22s | %3s %3s %4s | %3s %3s %4s | ord res pay | css | mgr\n",
    'slug', 'S', 'C', 'MI', 'S', 'C', 'MI');
echo str_repeat('-', 95)."\n";

function countForRest(array $data, string $table, int $prodId): int
{
    return count(array_filter(
        $data[$table] ?? [],
        static fn ($r) => (int) ($r['restaurant_id'] ?? 0) === $prodId
    ));
}

foreach ($activeProdIds as $prodId => $slug) {
    $dump = [
        'sections' => countForRest($data, 'sections', $prodId),
        'categories' => countForRest($data, 'categories', $prodId),
        'menu_items' => countForRest($data, 'menu_items', $prodId),
        'orders' => countForRest($data, 'orders', $prodId),
        'reservations' => countForRest($data, 'table_reservations', $prodId),
        'payments' => countForRest($data, 'payments', $prodId),
    ];
    $exp = $expected[$slug] ?? [];
    $match = ($dump['sections'] === ($exp['sections'] ?? -1))
        && ($dump['categories'] === ($exp['categories'] ?? -1))
        && ($dump['menu_items'] === ($exp['menu_items'] ?? -1))
        && ($dump['orders'] === ($exp['orders'] ?? -1))
        && ($dump['reservations'] === ($exp['reservations'] ?? -1))
        && ($dump['payments'] === ($exp['payments'] ?? -1));
    $flag = $match ? 'OK' : 'MISMATCH';

    printf("%-22s | %3d %3d %4d | %3d %3d %4d | %3d %3d %3d | %3d | %2d | %s\n",
        $slug,
        $dump['sections'], $dump['categories'], $dump['menu_items'],
        $exp['sections'] ?? 0, $exp['categories'] ?? 0, $exp['menu_items'] ?? 0,
        $dump['orders'], $dump['reservations'], $dump['payments'],
        $cssByRest[$prodId] ?? 0,
        countForRest($data, 'managers', $prodId),
        $flag
    );
}

// Slug uniqueness issues
echo "\n=== SLUG UNIQUENESS (per restaurant) ===\n";
foreach ($activeProdIds as $prodId => $slug) {
    $catSlugs = [];
    $dupCats = [];
    foreach ($data['categories'] ?? [] as $c) {
        if ((int) $c['restaurant_id'] !== $prodId) {
            continue;
        }
        $s = (string) $c['slug'];
        if (isset($catSlugs[$s])) {
            $dupCats[] = $s;
        }
        $catSlugs[$s] = true;
    }
    $miSlugs = [];
    $dupMi = [];
    foreach ($data['menu_items'] ?? [] as $m) {
        if ((int) $m['restaurant_id'] !== $prodId) {
            continue;
        }
        $catId = (int) $m['category_id'];
        $key = $catId.'|'.(string) $m['slug'];
        if (isset($miSlugs[$key])) {
            $dupMi[] = (string) $m['slug'];
        }
        $miSlugs[$key] = true;
    }
    if ($dupCats !== [] || $dupMi !== []) {
        echo "  {$slug}: dup category slugs=".count($dupCats).' dup menu slugs (same category)='.count($dupMi)."\n";
    }
}

// Order items orphan check
echo "\n=== ORDER_ITEMS menu_item_id MAPPING ===\n";
$orphanItems = 0;
$mappedItems = 0;
$menuItemIds = [];
foreach ($data['menu_items'] ?? [] as $m) {
    $menuItemIds[(int) $m['id']] = (string) $m['slug'];
}
foreach ($data['order_items'] ?? [] as $oi) {
    $mid = (int) $oi['menu_item_id'];
    if (! isset($menuItemIds[$mid])) {
        $orphanItems++;
    } else {
        $mappedItems++;
    }
}
echo "  order_items total: ".count($data['order_items'] ?? [])."\n";
echo "  mappable to menu_item slug: {$mappedItems}\n";
echo "  orphan menu_item_id: {$orphanItems}\n";

// Orders for inactive restaurants
echo "\n=== ORDERS / DATA FOR INACTIVE RESTAURANTS (excluded from sync) ===\n";
foreach ($inactiveRestaurants as $r) {
    $pid = (int) $r['id'];
    $o = countForRest($data, 'orders', $pid);
    $mi = countForRest($data, 'menu_items', $pid);
    if ($o > 0 || $mi > 0) {
        echo "  slug={$r['slug']}: menu_items={$mi} orders={$o}\n";
    }
}

// LAVA note: restaurant_id=2 in dump has menu tied to old structure
echo "\n=== LAVA (prod id=2) — cross-check ===\n";
$lavaId = 2;
foreach (['sections', 'categories', 'menu_items'] as $t) {
    echo "  {$t}: ".countForRest($data, $t, $lavaId)."\n";
}

// Platform totals
echo "\n=== PLATFORM TABLES (full sync in part_00) ===\n";
foreach ($platformTables as $t) {
    echo sprintf("  %-30s %d\n", $t, count($data[$t] ?? []));
}

// template_restaurants mapping
echo "\n=== template_restaurants (prod restaurant_id → slug) ===\n";
foreach ($data['template_restaurants'] ?? [] as $tr) {
    $rid = (int) $tr['restaurant_id'];
    $slug = $activeProdIds[$rid] ?? ('INACTIVE/UNKNOWN id='.$rid);
    echo "  template_id={$tr['template_id']} restaurant_id={$rid} ({$slug})\n";
}

// restaurant_payment_settings coverage
echo "\n=== restaurant_payment_settings by active restaurant ===\n";
foreach ($activeProdIds as $prodId => $slug) {
    $n = countForRest($data, 'restaurant_payment_settings', $prodId);
    if ($n > 0) {
        echo "  {$slug}: {$n} gateway row(s)\n";
    }
}

// qr_code_scans volume
$scans = count($data['qr_code_scans'] ?? []);
echo "\n=== qr_code_scans (excluded unless --include-scans) ===\n";
echo "  Total rows in dump: {$scans}\n";

// table_inventory_daily
$inv = count($data['table_inventory_daily'] ?? []);
echo "\n=== table_inventory_daily (excluded unless --include-inventory) ===\n";
echo "  Total rows in dump: {$inv}\n";
foreach ($activeProdIds as $prodId => $slug) {
    $n = countForRest($data, 'table_inventory_daily', $prodId);
    if ($n > 0) {
        echo "  {$slug}: {$n}\n";
    }
}

// Generator warnings
if ($warnings !== []) {
    echo "\n=== GENERATOR WARNINGS ===\n";
    foreach ($warnings as $w) {
        echo "  - {$w}\n";
    }
} else {
    echo "\n=== GENERATOR WARNINGS: none ===\n";
}

// Compare generated SQL if exists
$outputDir = dirname(__DIR__).'/seed-sql/production';
if (is_dir($outputDir)) {
    echo "\n=== GENERATED SQL FILES ===\n";
    foreach (glob($outputDir.'/sync_part_*.sql') ?: [] as $f) {
        echo '  '.basename($f).' — '.number_format(filesize($f))." bytes\n";
    }
}

echo "\nDone.\n";
