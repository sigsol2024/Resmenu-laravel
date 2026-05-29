<?php

namespace App\Services;

use App\Models\Restaurant;
use Illuminate\Support\Facades\DB;

class QrCodeService
{
    public function __construct(private QrGeneratorService $generator) {}

    public function menuUrl(Restaurant $restaurant, ?string $sectionSlug = null): string
    {
        $base = url('/qr/'.$restaurant->slug);
        $sectionSlug = strtolower(trim((string) $sectionSlug));
        $sectionSlug = preg_replace('/[^a-z0-9-]/', '', $sectionSlug) ?? '';

        return $sectionSlug !== '' ? $base.'/'.$sectionSlug : $base;
    }

    /** @return array{success:bool, message?:string, url?:string, image_url?:string} */
    public function generate(int $restaurantId, ?string $sectionSlug = null, ?int $size = null): array
    {
        $restaurant = Restaurant::find($restaurantId);
        if (! $restaurant) {
            return ['success' => false, 'message' => 'Restaurant not found'];
        }

        $qr = DB::table('restaurant_qr_codes')
            ->where('restaurant_id', $restaurantId)
            ->where('is_active', 1)
            ->first();

        if (! $qr || empty($qr->qr_template_id)) {
            return ['success' => false, 'message' => 'No QR code template selected. Please select a template first.'];
        }

        $targetUrl = $this->menuUrl($restaurant, $sectionSlug);
        $pixels = min(1000, max(100, $size ?? 300));
        $imageUrl = route('manager.qr.image', ['format' => 'png', 'size' => $pixels]).($sectionSlug ? '&section_slug='.urlencode($sectionSlug) : '');

        return [
            'success' => true,
            'url' => $targetUrl,
            'image_url' => $imageUrl,
        ];
    }

    /** @return array{body: string, content_type: string}|null */
    public function generateBinary(int $restaurantId, string $format = 'png', ?int $size = null, ?string $sectionSlug = null): ?array
    {
        return $this->generator->generateImage($restaurantId, $format, $size, $sectionSlug);
    }

    public function exportImageUrl(int $restaurantId, string $format = 'png', ?int $size = null, ?string $sectionSlug = null): ?string
    {
        $result = $this->generate($restaurantId, $sectionSlug, $size);
        if (! ($result['success'] ?? false)) {
            return null;
        }

        return $result['image_url'] ?? null;
    }
}
