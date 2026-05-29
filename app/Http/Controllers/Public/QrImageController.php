<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Services\QrGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QrImageController extends Controller
{
    public function __invoke(Request $request, QrGeneratorService $generator)
    {
        $restaurantId = null;
        if (Auth::guard('manager')->check()) {
            $restaurantId = (int) $request->attributes->get('restaurant_id', Auth::guard('manager')->user()->restaurant_id ?? 0);
        } elseif (Auth::guard('admin')->check() && $request->filled('restaurant_id')) {
            $restaurantId = (int) $request->query('restaurant_id');
        }

        if (! $restaurantId) {
            abort(403);
        }

        $format = strtolower((string) $request->query('format', 'png'));
        $size = $request->query('size') ? (int) $request->query('size') : null;
        $section = $request->query('section_slug', $request->query('section'));

        $result = $generator->generateImage($restaurantId, $format, $size, is_string($section) ? $section : null);
        if (! $result) {
            abort(404, 'QR template not configured');
        }

        $filename = 'qr-menu.'.($format === 'svg' ? 'svg' : 'png');

        return response($result['body'], 200, [
            'Content-Type' => $result['content_type'],
            'Content-Disposition' => $request->boolean('download') ? 'attachment; filename="'.$filename.'"' : 'inline',
        ]);
    }
}
