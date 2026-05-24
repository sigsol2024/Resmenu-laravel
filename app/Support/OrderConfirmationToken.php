<?php

namespace App\Support;

class OrderConfirmationToken
{
    private const PURPOSE = 'confirm';

    private const DEFAULT_TTL = 604800;

    /** @return array{exp:int,sig:string,slug:string}|null */
    public static function build(int $orderId, string $slug, ?int $ttlSeconds = null): ?array
    {
        $secret = config('resmenu.app_hmac_secret');
        if ($secret === '') {
            return null;
        }

        $slug = self::normalizeSlug($slug);
        $exp = time() + ($ttlSeconds ?? self::DEFAULT_TTL);
        $sig = self::sign($orderId, $slug, $exp, $secret);

        return ['exp' => $exp, 'sig' => $sig, 'slug' => $slug];
    }

    public static function verify(array $input, int $orderId): bool
    {
        $secret = config('resmenu.app_hmac_secret');
        if ($secret === '') {
            return false;
        }

        $exp = (int) ($input['exp'] ?? 0);
        $sig = (string) ($input['sig'] ?? '');
        $slug = self::normalizeSlug((string) ($input['slug'] ?? ''));
        if ($exp < time() || $sig === '' || $slug === '') {
            return false;
        }

        return hash_equals(self::sign($orderId, $slug, $exp, $secret), $sig);
    }

    /** @return array<string, int|string> */
    public static function queryParams(int $orderId, string $slug, ?int $ttlSeconds = null): array
    {
        $built = self::build($orderId, $slug, $ttlSeconds);

        return $built ?? [];
    }

    public static function confirmationUrl(int $orderId, string $slug, ?int $ttlSeconds = null): string
    {
        $params = self::queryParams($orderId, $slug, $ttlSeconds);
        if ($params === []) {
            return '';
        }

        return route('public.order.confirmation', ['order' => $orderId] + $params);
    }

    private static function sign(int $orderId, string $slug, int $exp, string $secret): string
    {
        return hash_hmac('sha256', self::PURPOSE.'|'.$orderId.'|'.$slug.'|'.$exp, $secret);
    }

    private static function normalizeSlug(string $slug): string
    {
        return preg_replace('/[^a-z0-9-]/', '', strtolower($slug)) ?? '';
    }
}
