<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class SiteSettingsService
{
  private static ?object $cache = null;

  public function row(): object
  {
    if (self::$cache !== null) {
      return self::$cache;
    }

    try {
      $row = DB::table('site_settings')->where('id', 1)->first();
      if (! $row) {
        $row = (object) [
          'id' => 1,
          'site_name' => 'Resmenu',
          'site_logo' => null,
          'favicon' => null,
        ];
      }
      self::$cache = $row;
    } catch (\Throwable) {
      self::$cache = (object) ['site_name' => 'Resmenu', 'site_logo' => null, 'favicon' => null];
    }

    return self::$cache;
  }

  /** @return array<string, mixed> */
  public function all(): array
  {
    return (array) $this->row();
  }

  public function get(string $key, ?string $default = null): ?string
  {
    $row = $this->row();
    $value = $row->{$key} ?? null;

    return $value !== null && $value !== '' ? (string) $value : $default;
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

  public function faviconUrl(): ?string
  {
    $favicon = $this->get('favicon');
    if ($favicon === null || $favicon === '') {
      return null;
    }

    $base = rtrim(config('resmenu.canonical_upload_url') ?: config('resmenu.upload_url'), '/');

    return $base.'/site/'.rawurlencode($favicon);
  }

  /** @param array<string, mixed> $data */
  public function update(array $data): bool
  {
    $allowed = [
      'site_name', 'site_logo', 'favicon',
      'contact_sales_email', 'contact_sales_phone',
      'contact_support_email', 'contact_support_phone',
      'contact_partners_email', 'contact_form_recipient',
      'contact_hq_title', 'contact_hq_address', 'contact_map_embed',
      'contact_social_facebook', 'contact_social_twitter', 'contact_social_instagram',
    ];

    $payload = ['updated_at' => now()];
    foreach ($allowed as $key) {
      if (array_key_exists($key, $data)) {
        $payload[$key] = $data[$key];
      }
    }

    if (count($payload) <= 1) {
      return false;
    }

    $exists = DB::table('site_settings')->where('id', 1)->exists();
    if ($exists) {
      DB::table('site_settings')->where('id', 1)->update($payload);
    } else {
      $payload['id'] = 1;
      $payload['site_name'] = $payload['site_name'] ?? 'Resmenu';
      $payload['created_at'] = now();
      DB::table('site_settings')->insert($payload);
    }

    self::$cache = null;

    return true;
  }

  public static function clearCache(): void
  {
    self::$cache = null;
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
