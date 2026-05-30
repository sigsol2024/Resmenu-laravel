<?php

/**
 * Compare image filenames referenced in the production SQL dump against files on disk.
 *
 * Usage (from Resmenu-laravel root):
 *   php database/scripts/audit_upload_assets.php
 *   php database/scripts/audit_upload_assets.php ../backup/resmenu_server_original/database/sigsolmenu_resmenu.sql
 */

$root = dirname(__DIR__, 2);
$dump = $argv[1] ?? $root.'/../backup/resmenu_server_original/database/sigsolmenu_resmenu.sql';
$uploadRoot = $root.'/storage/app/public/uploads';

if (! is_file($dump)) {
    fwrite(STDERR, "Dump not found: {$dump}\n");
    exit(1);
}

$sql = file_get_contents($dump);

$map = [
    'restaurants.logo' => ['restaurants', 'logo', 'logos'],
    'restaurants.hero_image' => ['restaurants', 'hero_image', 'heroes'],
    'categories.image' => ['categories', 'image', 'categories'],
    'menu_items.image' => ['menu_items', 'image', 'menu-items'],
    'sections.image' => ['sections', 'image', 'sections'],
];

$missing = [];
$found = 0;

foreach ($map as $label => [$table, $column, $folder]) {
    if (! preg_match('/INSERT INTO `'.$table.'`/s', $sql, $m, PREG_OFFSET_CAPTURE)) {
        continue;
    }
    $start = $m[0][1];
    $chunk = substr($sql, $start, 800000);
    if (! preg_match('/VALUES\s*(.+?);\s*(?:--|\n\n|INSERT INTO|$)/s', $chunk, $valuesMatch)) {
        continue;
    }
    $valuesBlock = $valuesMatch[1];

    // Parse column order from INSERT header in this chunk.
    if (! preg_match('/INSERT INTO `'.$table.'`\s*\(([^)]+)\)/s', $chunk, $colMatch)) {
        continue;
    }
    $columns = array_map(static fn ($c) => trim($c, " `\n\r\t"), explode(',', $colMatch[1]));
    $colIndex = array_search($column, $columns, true);
    if ($colIndex === false) {
        continue;
    }

    preg_match_all('/\(([^)]*(?:\([^)]*\)[^)]*)*)\)/s', $valuesBlock, $rowMatches);
    foreach ($rowMatches[1] as $row) {
        $parts = str_getcsv($row, ',', "'", '\\');
        if (! isset($parts[$colIndex])) {
            continue;
        }
        $file = trim($parts[$colIndex], " '");
        if ($file === '' || strtoupper($file) === 'NULL') {
            continue;
        }
        if (! preg_match('/\.(jpg|jpeg|png|webp|gif)$/i', $file)) {
            continue;
        }
        $path = $uploadRoot.'/'.$folder.'/'.ltrim($file, '/');
        if (is_file($path)) {
            $found++;
        } else {
            $missing[] = [$label, $file, $folder];
        }
    }
}

echo "Upload root: {$uploadRoot}\n";
echo "Found on disk: {$found}\n";
echo 'Missing: '.count($missing)."\n\n";

if ($missing !== []) {
    echo "Missing files (first 40):\n";
    foreach (array_slice($missing, 0, 40) as [$label, $file, $folder]) {
        echo "  [{$label}] {$folder}/{$file}\n";
    }
    if (count($missing) > 40) {
        echo '  ... and '.(count($missing) - 40)." more\n";
    }
    exit(2);
}

echo "All referenced image files are present.\n";
exit(0);
