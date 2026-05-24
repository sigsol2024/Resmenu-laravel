<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class SiteSettingsService
{
    /** @var array<string, string>|null */
    private static ?array $cache = null;

    /** @return array<string, string> */
    public function all(): array
    {
        if (self::$cache !== null) {
            return self::$cache;
        }

        try {
            self::$cache = DB::table('site_settings')->pluck('value', 'key')->all();
        } catch (\Throwable) {
            self::$cache = [];
        }

        return self::$cache;
    }

    public function get(string $key, ?string $default = null): ?string
    {
        $all = $this->all();

        return isset($all[$key]) ? (string) $all[$key] : $default;
    }

    public function siteName(): string
    {
        return $this->get('site_name', config('app.name', 'Resmenu')) ?? 'Resmenu';
    }

    public function siteLogoUrl(): ?string
    {
        $logo = $this->get('site_logo');
        if ($logo === null || $logo === '') {
            return null;
        }

        $base = rtrim(config('resmenu.canonical_upload_url') ?: config('resmenu.upload_url'), '/');

        return $base.'/site/'.rawurlencode($logo);
    }

    /** @return list<string> */
    public function showcaseRestaurantLogos(): array
    {
        return [
            'https://our-menu.online/uploads/logos/698ee78360beb.jpg',
            'https://our-menu.online/uploads/logos/69459eb555362.jpg',
            'https://our-menu.online/uploads/logos/69a76f2ad31b1.png',
        ];
    }
}
