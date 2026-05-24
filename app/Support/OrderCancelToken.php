<?php

namespace App\Support;

class OrderCancelToken
{
    /** @return array{exp:int,sig:string}|null */
    public static function build(int $orderId, string $slug, int $ttlSeconds = 900): ?array
    {
        $secret = config('resmenu.app_hmac_secret');
        if ($secret === '') {
            return null;
        }
        $exp = time() + $ttlSeconds;
        $sig = hash_hmac('sha256', $orderId.'|'.$slug.'|'.$exp, $secret);

        return ['exp' => $exp, 'sig' => $sig];
    }

    public static function verify(array $input, int $orderId): bool
    {
        $secret = config('resmenu.app_hmac_secret');
        if ($secret === '') {
            return false;
        }
        $exp = (int) ($input['exp'] ?? 0);
        $sig = (string) ($input['sig'] ?? '');
        $slug = preg_replace('/[^a-z0-9-]/', '', strtolower((string) ($input['slug'] ?? '')));
        if ($exp < time() || $sig === '') {
            return false;
        }
        $expected = hash_hmac('sha256', $orderId.'|'.$slug.'|'.$exp, $secret);

        return hash_equals($expected, $sig);
    }
}
