<?php

/**
 * Strip CREATE TABLE / ALTER from a phpMyAdmin dump; keep INSERTs for data import after migrate.
 * Usage: php database/scripts/build_data_only_dump.php [source.sql] [output.sql]
 */

$source = $argv[1] ?? dirname(__DIR__, 3).'/Resmenu/database/sigsolmenu_resmenu.sql';
$output = $argv[2] ?? dirname(__DIR__).'/seed-sql/production/sigsolmenu_data_only.sql';

if (! is_file($source)) {
    fwrite(STDERR, "Source not found: {$source}\n");
    exit(1);
}

$sql = file_get_contents($source);
$lines = preg_split('/\r\n|\n|\r/', $sql);

$out = [
    '-- Data-only import for Resmenu Laravel (run AFTER php artisan migrate)',
    '-- Source: '.basename($source),
    '-- Generated: '.date('c'),
    'SET NAMES utf8mb4;',
    'SET FOREIGN_KEY_CHECKS=0;',
    'SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";',
    '',
];

$inCreate = false;
$buffer = '';

foreach ($lines as $line) {
    $trim = trim($line);

    if (preg_match('/^CREATE TABLE/i', $trim)) {
        $inCreate = true;
        continue;
    }
    if ($inCreate) {
        if (str_contains($trim, ';') && ! str_starts_with($trim, 'CREATE')) {
            $inCreate = false;
        }
        continue;
    }

    if (preg_match('/^(ALTER TABLE|DROP TABLE|CREATE INDEX|LOCK TABLES|UNLOCK TABLES|\/\*!40)/i', $trim)) {
        continue;
    }

    if (preg_match('/^INSERT INTO `/i', $trim)) {
        $buffer = $line;
        if (str_ends_with(rtrim($line), ';')) {
            $out[] = $buffer;
            $buffer = '';
        }
        continue;
    }

    if ($buffer !== '') {
        $buffer .= "\n".$line;
        if (str_ends_with(rtrim($line), ';')) {
            $out[] = $buffer;
            $buffer = '';
        }
    }
}

$out[] = '';
$out[] = 'SET FOREIGN_KEY_CHECKS=1;';

$dir = dirname($output);
if (! is_dir($dir)) {
    mkdir($dir, 0755, true);
}

file_put_contents($output, implode("\n", $out));
echo 'Wrote '.count(array_filter($out, fn ($l) => str_starts_with($l, 'INSERT')))." INSERT blocks to {$output}\n";
