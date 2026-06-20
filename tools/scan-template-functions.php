<?php

$dir = __DIR__.'/../resources/views/menu/php-templates';
$knownHelpers = [
    'formatPrice', 'getTemplateAssetBaseUrl', 'templateSupportsOrdering', 'e_menu',
    'resmenu_icon', 'resmenu_icon_paths', 'resmenu_resolve_icon_name', 'resmenu_password_toggle_icons',
    'resmenu_get_category_icon', 'resmenu_get_category_icon_map',
];

$builtinPrefix = ['str_', 'mb_', 'is_', 'array_', 'preg_', 'htmlspecialchars', 'json_', 'url', 'date', 'count', 'empty', 'isset', 'defined', 'rtrim', 'ltrim', 'trim', 'max', 'min', 'intval', 'floatval', 'nl2br', 'number_format', 'pathinfo', 'file_', 'ob_', 'extract', 'include', 'require'];

$calls = [];

foreach (glob($dir.'/**/index.php') as $file) {
    $code = file_get_contents($file);
    preg_match_all('/\b([a-zA-Z_][a-zA-Z0-9_]*)\s*\(/', $code, $matches);
    $template = basename(dirname($file));

    foreach ($matches[1] as $fn) {
        if (in_array($fn, $knownHelpers, true)) {
            continue;
        }
        if (preg_match('/^(formatPrice|t4_|t3_|nmc_|template\d)/', $fn)) {
            continue;
        }
        foreach ($builtinPrefix as $prefix) {
            if (str_starts_with($fn, $prefix) || $fn === rtrim($prefix, '_')) {
                continue 2;
            }
        }
        if (in_array($fn, ['if', 'for', 'foreach', 'while', 'switch', 'echo', 'print', 'function', 'return', 'elseif', 'else', 'endif', 'endforeach', 'endfor', 'endwhile', 'endswitch', 'break', 'continue', 'new', 'true', 'false', 'null', 'static', 'public', 'private', 'protected', 'array', 'list', 'unset', 'throw', 'try', 'catch', 'finally', 'do', 'match', 'fn'], true)) {
            continue;
        }
        $calls[$fn][$template] = true;
    }
}

ksort($calls);
foreach ($calls as $fn => $templates) {
    echo $fn.' => '.implode(', ', array_keys($templates)).PHP_EOL;
}
