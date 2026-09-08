<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Throwable;

class RestaurantQrService
{
    public function settings(int $restaurantId): object
    {
        if (! Schema::hasTable('restaurant_qr_codes')) {
            return (object) [
                'restaurant_id' => $restaurantId,
                'qr_template_id' => null,
            ];
        }

        try {
            $row = DB::table('restaurant_qr_codes')->where('restaurant_id', $restaurantId)->first();
            if ($row) {
                return $row;
            }

            DB::table('restaurant_qr_codes')->insert([
                'restaurant_id' => $restaurantId,
                'qr_template_id' => null,
                'background_color' => '#FFFFFF',
                'qr_color' => '#000000',
                'text_content' => 'Scan to view menu',
                'text_color' => '#000000',
                'text_size' => 16,
                'qr_size' => 300,
                'margin' => 20,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return DB::table('restaurant_qr_codes')->where('restaurant_id', $restaurantId)->first()
                ?? (object) ['restaurant_id' => $restaurantId, 'qr_template_id' => null];
        } catch (Throwable $e) {
            report($e);

            return (object) [
                'restaurant_id' => $restaurantId,
                'qr_template_id' => null,
            ];
        }
    }

    public function selectTemplate(int $restaurantId, int $templateId): bool
    {
        if (! Schema::hasTable('qr_templates') || ! Schema::hasTable('restaurant_qr_codes')) {
            return false;
        }

        try {
            $template = DB::table('qr_templates')
                ->where('id', $templateId)
                ->where('is_active', 1)
                ->first();

            if (! $template) {
                return false;
            }

            $configJson = $this->normalizeConfigJson($template->config_json ?? null);

            $existing = DB::table('restaurant_qr_codes')->where('restaurant_id', $restaurantId)->first();
            $payload = [
                'qr_template_id' => $templateId,
                'final_config_json' => $configJson,
                'updated_at' => now(),
            ];

            if ($existing) {
                DB::table('restaurant_qr_codes')
                    ->where('restaurant_id', $restaurantId)
                    ->update($payload);
            } else {
                DB::table('restaurant_qr_codes')->insert(array_merge([
                    'restaurant_id' => $restaurantId,
                    'background_color' => '#FFFFFF',
                    'qr_color' => '#000000',
                    'text_content' => 'Scan to view menu',
                    'text_color' => '#000000',
                    'text_size' => 16,
                    'qr_size' => 300,
                    'margin' => 20,
                    'is_active' => 1,
                    'created_at' => now(),
                ], $payload));
            }

            $saved = DB::table('restaurant_qr_codes')->where('restaurant_id', $restaurantId)->first();

            return $saved !== null && (int) ($saved->qr_template_id ?? 0) === $templateId;
        } catch (Throwable $e) {
            report($e);

            return false;
        }
    }

    /** @return list<object> */
    public function activeTemplates(): array
    {
        if (! Schema::hasTable('qr_templates')) {
            return [];
        }

        try {
            return DB::table('qr_templates')
                ->where('is_active', 1)
                ->orderBy('name')
                ->get()
                ->all();
        } catch (Throwable $e) {
            report($e);

            return [];
        }
    }

    private function normalizeConfigJson(mixed $config): string
    {
        if (is_array($config) || is_object($config)) {
            $encoded = json_encode($config);

            return ($encoded !== false && $encoded !== '') ? $encoded : '{}';
        }

        if (is_string($config) && trim($config) !== '') {
            $decoded = json_decode($config, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $encoded = json_encode($decoded);

                return ($encoded !== false && $encoded !== '') ? $encoded : '{}';
            }
        }

        return '{}';
    }
}
