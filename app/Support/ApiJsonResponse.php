<?php

namespace App\Support;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiJsonResponse
{
    public static function success(string $message, mixed $data = null, int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $status)->withHeaders(self::corsHeaders());
    }

    public static function error(string $message, mixed $data = null, int $status = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => $data,
        ], $status)->withHeaders(self::corsHeaders());
    }

    public static function corsHeaders(?Request $request = null): array
    {
        $request ??= request();
        $allowed = self::allowedOrigins();
        $headers = [
            'Access-Control-Allow-Methods' => 'GET, POST, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Accept, X-Requested-With',
            'Vary' => 'Origin',
        ];

        $origin = (string) $request->headers->get('Origin', '');
        if ($origin !== '' && in_array($origin, $allowed, true)) {
            $headers['Access-Control-Allow-Origin'] = $origin;
        }

        // Never emit * for lead/public-config when an allowlist is configured.
        // If allowlist empty (misconfigured prod), omit Allow-Origin entirely.
        return $headers;
    }

    /** @return list<string> */
    public static function allowedOrigins(): array
    {
        $raw = (string) config('resmenu.cors_allowed_origins', '');
        $parts = array_values(array_filter(array_map('trim', explode(',', $raw))));

        // Strip accidental wildcards — explicit origins only.
        return array_values(array_filter($parts, fn ($o) => $o !== '*' && $o !== ''));
    }
}
