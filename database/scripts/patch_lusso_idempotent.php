<?php

$path = dirname(__DIR__).'/seed-sql/restaurants/seed_lusso_drinks.sql';
$sql = file_get_contents($path);
$sql = preg_replace_callback(
    "/SELECT @rid, @sid, '([^']+)', '([^']+)', (\d+), 1 FROM DUAL WHERE @sid IS NOT NULL LIMIT 1;/",
    static fn (array $m): string => "SELECT @rid, @sid, '{$m[1]}', '{$m[2]}', {$m[3]}, 1 FROM DUAL WHERE @sid IS NOT NULL "
        ."AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = '{$m[2]}') LIMIT 1;",
    $sql
);
$sql = str_replace('Run ONCE only; re-running will create duplicate categories and items.', 'Safe to re-run: categories use NOT EXISTS guards.', $sql);
file_put_contents($path, $sql);
echo "Patched {$path}\n";
