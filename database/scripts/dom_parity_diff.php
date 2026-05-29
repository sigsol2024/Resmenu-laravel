<?php
/**
 * Compare legacy PHP page HTML vs Laravel route HTML (normalized DOM diff helper).
 *
 * Usage:
 *   php database/scripts/dom_parity_diff.php --legacy-url=http://localhost/Resmenu/admin/dashboard.php --laravel-url=http://localhost:8000/admin/dashboard
 */

declare(strict_types=1);

function usage(): void
{
    fwrite(STDERR, "Usage: php dom_parity_diff.php --legacy-url=URL --laravel-url=URL [--out=path]\n");
    exit(1);
}

function fetch(string $url): string
{
    $ctx = stream_context_create([
        'http' => [
            'timeout' => 30,
            'header' => "User-Agent: ResmenuParityDiff/1.0\r\n",
        ],
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
        ],
    ]);
    $html = @file_get_contents($url, false, $ctx);
    if ($html === false) {
        throw new RuntimeException("Failed to fetch: {$url}");
    }
    return $html;
}

function normalize(string $html): string
{
    $html = preg_replace('/<!--.*?-->/s', '', $html) ?? $html;
    $html = preg_replace('/\s+/u', ' ', $html) ?? $html;
    $html = preg_replace('/>\s+</u', '><', $html) ?? $html;
    return trim($html);
}

function tokenizeClasses(string $html): array
{
    preg_match_all('/class="([^"]*)"/', $html, $m);
    $classes = [];
    foreach ($m[1] as $group) {
        foreach (preg_split('/\s+/', trim($group)) as $c) {
            if ($c !== '') {
                $classes[$c] = ($classes[$c] ?? 0) + 1;
            }
        }
    }
    ksort($classes);
    return $classes;
}

$opts = getopt('', ['legacy-url:', 'laravel-url:', 'out::']);
if (empty($opts['legacy-url']) || empty($opts['laravel-url'])) {
    usage();
}

try {
    $legacyRaw = fetch((string) $opts['legacy-url']);
    $laravelRaw = fetch((string) $opts['laravel-url']);
} catch (Throwable $e) {
    fwrite(STDERR, $e->getMessage() . PHP_EOL);
    exit(2);
}

$legacy = normalize($legacyRaw);
$laravel = normalize($laravelRaw);

$legacyClasses = tokenizeClasses($legacy);
$laravelClasses = tokenizeClasses($laravel);

$missingInLaravel = array_diff_key($legacyClasses, $laravelClasses);
$extraInLaravel = array_diff_key($laravelClasses, $legacyClasses);

$report = [
    'legacy_url' => $opts['legacy-url'],
    'laravel_url' => $opts['laravel-url'],
    'legacy_length' => strlen($legacy),
    'laravel_length' => strlen($laravel),
    'length_delta' => strlen($laravel) - strlen($legacy),
    'missing_classes_in_laravel' => $missingInLaravel,
    'extra_classes_in_laravel' => $extraInLaravel,
    'identical_normalized' => $legacy === $laravel,
];

$json = json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
echo $json . PHP_EOL;

if (!empty($opts['out'])) {
    file_put_contents((string) $opts['out'], $json);
}

exit($report['identical_normalized'] ? 0 : 1);
