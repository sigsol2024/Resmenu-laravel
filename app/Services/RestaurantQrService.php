<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class RestaurantQrService
{
    public function settings(int $restaurantId): ?object
    {
        $row = DB::table('restaurant_qr_codes')->where('restaurant_id', $restaurantId)->first();
        if ($row) {
            return $row;
        }

        DB::table('restaurant_qr_codes')->insert([
            'restaurant_id' => $restaurantId,
            'qr_template_id' => null,
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return DB::table('restaurant_qr_codes')->where('restaurant_id', $restaurantId)->first();
    }

    public function selectTemplate(int $restaurantId, int $templateId): bool
    {
        $template = DB::table('qr_templates')
            ->where('id', $templateId)
            ->where('is_active', 1)
            ->first();

        if (! $template) {
            return false;
        }

        $this->settings($restaurantId);
        $config = $template->config_json;

        return DB::table('restaurant_qr_codes')
            ->where('restaurant_id', $restaurantId)
            ->update([
                'qr_template_id' => $templateId,
                'final_config_json' => is_string($config) ? $config : json_encode($config),
                'updated_at' => now(),
            ]) >= 0;
    }

    /** @return list<object> */
    public function activeTemplates(): array
    {
        return DB::table('qr_templates')
            ->where('is_active', 1)
            ->orderBy('name')
            ->get()
            ->all();
    }
}
