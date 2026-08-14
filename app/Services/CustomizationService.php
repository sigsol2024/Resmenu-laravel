<?php

namespace App\Services;

use App\Models\Restaurant;
use Illuminate\Support\Facades\DB;

class CustomizationService
{
    public function forRestaurant(Restaurant $restaurant): array
    {
        $templateId = (int) ($restaurant->template_id ?? 1);
        $defaults = $this->templateDefaults($templateId);

        $row = DB::table('customization_settings')
            ->where('restaurant_id', $restaurant->id)
            ->where('template_id', $templateId)
            ->first();

        if (! $row) {
            return $defaults;
        }

        $merged = array_merge($defaults, (array) $row);
        unset($merged['id'], $merged['restaurant_id'], $merged['template_id'], $merged['created_at'], $merged['updated_at']);

        if ($templateId === 4 && $this->isNearBlack((string) ($merged['category_title_color'] ?? ''))) {
            $merged['category_title_color'] = '#ffffff';
        }

        return $merged;
    }

    /** Defaults for marketing template preview pages. */
    public function templateDefaultsForPreview(int $templateId): array
    {
        return $this->templateDefaults($templateId);
    }

    private function templateDefaults(int $templateId): array
    {
        $hardcoded = $this->hardcodedDefaults($templateId);

        $row = DB::table('template_customizations')->where('template_id', $templateId)->first();
        if (! $row) {
            return $hardcoded;
        }

        $merged = $hardcoded;
        foreach (array_keys($hardcoded) as $key) {
            $value = $row->{$key} ?? null;
            if ($value !== null && $value !== '') {
                $merged[$key] = is_int($hardcoded[$key]) ? (int) $value : $value;
            }
        }

        // Template 4 category chips are charcoal; the seeded default (#121212) made text invisible.
        if ($templateId === 4 && $this->isNearBlack((string) ($merged['category_title_color'] ?? ''))) {
            $merged['category_title_color'] = '#ffffff';
        }

        return $merged;
    }

    /** @return array<string, mixed> */
    private function hardcodedDefaults(int $templateId): array
    {
        $fallbacks = [
            4 => [
                'menu_title_color' => '#121212',
                'menu_title_size' => 24,
                'menu_title_font' => 'Epilogue',
                'price_color' => '#f20d0d',
                'price_size' => 18,
                'price_font' => 'Epilogue',
                'description_color' => '#666666',
                'description_size' => 14,
                'description_font' => 'Epilogue',
                'category_title_color' => '#ffffff',
                'category_title_size' => 20,
                'category_title_font' => 'Epilogue',
                'background_color' => '#f8f5f5',
                'header_background_color' => '#121212',
                'primary_color' => '#f20d0d',
                'secondary_color' => '#FFFFFF',
            ],
        ];

        return $fallbacks[$templateId] ?? [
            'menu_title_color' => '#000000',
            'menu_title_size' => 24,
            'menu_title_font' => 'Inter',
            'price_color' => '#000000',
            'price_size' => 18,
            'price_font' => 'Inter',
            'description_color' => '#666666',
            'description_size' => 14,
            'description_font' => 'Inter',
            'category_title_color' => '#000000',
            'category_title_size' => 20,
            'category_title_font' => 'Inter',
            'background_color' => '#FFFFFF',
            'header_background_color' => '#FFFFFF',
            'primary_color' => '#111111',
            'secondary_color' => '#FFFFFF',
        ];
    }

    private function isNearBlack(string $hex): bool
    {
        $hex = ltrim(trim($hex), '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }
        if (strlen($hex) !== 6 || ! ctype_xdigit($hex)) {
            return false;
        }

        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        // Relative luminance threshold — charcoal/black text on charcoal chips.
        return (($r * 299) + ($g * 587) + ($b * 114)) / 1000 < 40;
    }

    /** @param  array<string, mixed>  $data */
    public function saveForRestaurant(int $restaurantId, array $data): void
    {
        $restaurant = Restaurant::findOrFail($restaurantId);
        $templateId = isset($data['template_id'])
            ? (int) $data['template_id']
            : (int) ($restaurant->template_id ?? 1);
        unset($data['template_id']);

        $allowed = [
            'menu_title_color', 'menu_title_size', 'menu_title_font',
            'price_color', 'price_size', 'price_font',
            'description_color', 'description_size', 'description_font',
            'category_title_color', 'category_title_size', 'category_title_font',
            'background_color', 'header_background_color', 'primary_color', 'secondary_color',
        ];

        $payload = [];
        foreach ($allowed as $key) {
            if (array_key_exists($key, $data) && $data[$key] !== null && $data[$key] !== '') {
                $payload[$key] = $data[$key];
            }
        }

        if ($payload === []) {
            return;
        }

        $payload['updated_at'] = now();

        $exists = DB::table('customization_settings')
            ->where('restaurant_id', $restaurantId)
            ->where('template_id', $templateId)
            ->exists();

        if ($exists) {
            DB::table('customization_settings')
                ->where('restaurant_id', $restaurantId)
                ->where('template_id', $templateId)
                ->update($payload);
        } else {
            $payload['restaurant_id'] = $restaurantId;
            $payload['template_id'] = $templateId;
            $payload['created_at'] = now();
            DB::table('customization_settings')->insert($payload);
        }
    }
}
