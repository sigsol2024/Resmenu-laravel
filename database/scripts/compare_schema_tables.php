<?php

$legacyPath = dirname(__DIR__, 3).'/Resmenu/database/sigsolmenu_resmenu.sql';
$laravelPath = dirname(__DIR__).'/schema/sigsolmenu_laravel.structure.sql';

function extractTables(string $sql): array
{
    preg_match_all('/CREATE TABLE `([^`]+)`/', $sql, $m);

    return array_values(array_unique($m[1] ?? []));
}

$legacy = extractTables(file_get_contents($legacyPath));
$laravel = extractTables(file_get_contents($laravelPath));
sort($legacy);
sort($laravel);

echo "Legacy tables: ".count($legacy)."\n";
echo "Laravel tables: ".count($laravel)."\n";
echo "Legacy only: ".implode(', ', array_diff($legacy, $laravel))."\n";
echo "Laravel only: ".implode(', ', array_diff($laravel, $legacy))."\n";
