<?php
/**
 * Extract legacy admin/manager page body (after </style>, before footer) for Blade conversion.
 *
 * Usage:
 *   php database/scripts/extract_legacy_page_body.php Resmenu/admin/dashboard.php
 */

declare(strict_types=1);

if ($argc < 2) {
    fwrite(STDERR, "Usage: php extract_legacy_page_body.php <legacy-php-file>\n");
    exit(1);
}

$path = $argv[1];
if (!is_file($path)) {
    fwrite(STDERR, "File not found: {$path}\n");
    exit(1);
}

$content = file_get_contents($path);
if ($content === false) {
    fwrite(STDERR, "Unable to read file\n");
    exit(1);
}

if (!preg_match('/<\/style>\s*(.*)/s', $content, $m)) {
    fwrite(STDERR, "No </style> block found\n");
    exit(1);
}

$body = $m[1];
$body = preg_replace('/<\?php\s+include\s+__DIR__\s*\.\s*[\'"].*admin-footer\.php[\'"].*\?>\s*/s', '', $body) ?? $body;
$body = preg_replace('/<\?php\s+include\s+__DIR__\s*\.\s*[\'"].*manager-footer\.php[\'"].*\?>\s*/s', '', $body) ?? $body;
$body = preg_replace('/<\?php\s+include\s+__DIR__\s*\.\s*[\'"].*\/includes\/admin-layout\.php[\'"].*\?>\s*/s', '', $body) ?? $body;

echo trim($body) . PHP_EOL;
