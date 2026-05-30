<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QrAnalyticsService
{
    public function trackScan(int $restaurantId, Request $request): void
    {
        if (! DB::getSchemaBuilder()->hasTable('qr_code_scans')) {
            return;
        }

        $ua = (string) $request->userAgent();
        $device = $this->parseDevice($ua);

        DB::table('qr_code_scans')->insert([
            'restaurant_id' => $restaurantId,
            'ip_address' => $request->ip(),
            'user_agent' => $ua ?: null,
            'device_type' => $device['device_type'],
            'browser' => $device['browser'],
            'os' => $device['os'],
            'country' => null,
            'city' => null,
            'latitude' => null,
            'longitude' => null,
            'scanned_at' => now(),
        ]);
    }

    /** @return array{device_type: string, browser: string, os: string} */
    private function parseDevice(string $ua): array
    {
        $ua = strtolower($ua);
        $device = 'desktop';
        if (str_contains($ua, 'mobile') || str_contains($ua, 'android')) {
            $device = 'mobile';
        } elseif (str_contains($ua, 'tablet') || str_contains($ua, 'ipad')) {
            $device = 'tablet';
        }

        $browser = 'unknown';
        foreach (['chrome', 'firefox', 'safari', 'edge', 'opera'] as $b) {
            if (str_contains($ua, $b)) {
                $browser = $b;
                break;
            }
        }

        $os = 'unknown';
        foreach (['windows', 'mac', 'linux', 'android', 'ios'] as $o) {
            if (str_contains($ua, $o)) {
                $os = $o;
                break;
            }
        }

        return ['device_type' => $device, 'browser' => $browser, 'os' => $os];
    }

  /** @return array{total_scans:int,scans_by_device:array,scans_by_browser:array,recent_scans:list}> */
    public function summary(int $restaurantId): array
    {
        if (! DB::getSchemaBuilder()->hasTable('qr_code_scans')) {
            return [
                'total_scans' => 0,
                'scans_by_device' => [],
                'scans_by_browser' => [],
                'scans_by_location' => [],
                'recent_scans' => [],
            ];
        }

        $total = (int) DB::table('qr_code_scans')->where('restaurant_id', $restaurantId)->count();

        $byDevice = DB::table('qr_code_scans')
            ->where('restaurant_id', $restaurantId)
            ->select('device_type', DB::raw('COUNT(*) as cnt'))
            ->groupBy('device_type')
            ->pluck('cnt', 'device_type')
            ->all();

        $byBrowser = DB::table('qr_code_scans')
            ->where('restaurant_id', $restaurantId)
            ->select('browser', DB::raw('COUNT(*) as cnt'))
            ->groupBy('browser')
            ->pluck('cnt', 'browser')
            ->all();

        $byLocation = DB::table('qr_code_scans')
            ->where('restaurant_id', $restaurantId)
            ->whereNotNull('country')
            ->select('country', 'city', DB::raw('COUNT(*) as cnt'))
            ->groupBy('country', 'city')
            ->orderByDesc('cnt')
            ->limit(10)
            ->get()
            ->all();

        $recent = DB::table('qr_code_scans')
            ->where('restaurant_id', $restaurantId)
            ->orderByDesc('scanned_at')
            ->limit(10)
            ->get()
            ->all();

        return [
            'total_scans' => $total,
            'scans_by_device' => $byDevice,
            'scans_by_browser' => $byBrowser,
            'scans_by_location' => $byLocation,
            'recent_scans' => $recent,
        ];
    }
}
