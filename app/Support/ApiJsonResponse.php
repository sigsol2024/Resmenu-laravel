<?php

namespace App\Support;

use Illuminate\Http\JsonResponse;

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

    public static function corsHeaders(): array
    {
        $origins = array_filter(array_map('trim', explode(',', (string) config('resmenu.cors_allowed_origins', '*'))));
        $origin = $origins === [] || in_array('*', $origins, true) ? '*' : ($origins[0] ?? '*');

        return [
            'Access-Control-Allow-Origin' => $origin,
            'Access-Control-Allow-Methods' => 'GET, POST, PATCH, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-Webhook-Secret, x-paystack-signature, verif-hash',
        ];
    }
}
