<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\QrCodeService;
use App\Support\ApiJsonResponse;
use Illuminate\Http\Request;

class QrApiController extends Controller
{
    public function generate(Request $request, QrCodeService $qr)
    {
        $restaurantId = (int) $request->attributes->get('restaurant_id');
        $sectionSlug = $request->query('section_slug', $request->query('section'));
        $size = $request->query('size') ? (int) $request->query('size') : null;

        $result = $qr->generate($restaurantId, is_string($sectionSlug) ? $sectionSlug : null, $size);
        if (! ($result['success'] ?? false)) {
            return ApiJsonResponse::error($result['message'] ?? 'Unable to generate QR', null, 404);
        }

        return ApiJsonResponse::success('QR generated', $result);
    }

    public function export(Request $request, QrCodeService $qr)
    {
        $restaurantId = (int) $request->attributes->get('restaurant_id');
        $format = strtolower((string) $request->query('format', 'png'));
        $size = $request->query('size') ? (int) $request->query('size') : 512;
        $sectionSlug = $request->query('section_slug', $request->query('section'));
        $section = is_string($sectionSlug) ? $sectionSlug : null;

        $binary = $qr->generateBinary($restaurantId, $format, $size, $section);
        if (! $binary) {
            return ApiJsonResponse::error('No QR template selected', null, 404);
        }

        $filename = 'qr-menu.'.($format === 'svg' ? 'svg' : 'png');

        return response($binary['body'], 200, [
            'Content-Type' => $binary['content_type'],
            'Content-Disposition' => $request->boolean('download')
                ? 'attachment; filename="'.$filename.'"'
                : 'inline',
        ]);
    }
}
