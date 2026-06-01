<?php

namespace App\Support;

class SafeRedirect
{
    public static function localPath(?string $raw, string $default = ''): string
    {
        $next = trim((string) $raw);
        if ($next === '') {
            return $default;
        }
        if ($next[0] !== '/') {
            return $default;
        }
        if (str_starts_with($next, '//')) {
            return $default;
        }
        if (preg_match('/[\r\n]/', $next)) {
            return $default;
        }

        return self::normalizeLegacyPath($next);
    }

    /** Map pre-Laravel *.php manager/admin paths to current routes. */
    public static function normalizeLegacyPath(string $path): string
    {
        $parsed = parse_url($path);
        $pathname = $parsed['path'] ?? $path;

        if (preg_match('#^/(manager|admin)/(.+)$#', $pathname, $matches) && str_ends_with($pathname, '.php')) {
            $pathname = '/'.$matches[1].'/'.preg_replace('/\.php$/', '', $matches[2]);
        }

        $query = isset($parsed['query']) && $parsed['query'] !== '' ? '?'.$parsed['query'] : '';

        return $pathname.$query;
    }
}
