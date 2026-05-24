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

        return $next;
    }
}
