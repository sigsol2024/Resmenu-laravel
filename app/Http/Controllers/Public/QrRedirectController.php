<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Services\MenuService;
use App\Services\QrAnalyticsService;
use Illuminate\Http\Request;

class QrRedirectController extends Controller
{
    public function __construct(
        private MenuService $menu,
        private QrAnalyticsService $qr,
    ) {}

    public function __invoke(Request $request, string $slug, ?string $section = null)
    {
        $slug = preg_replace('/[^a-z0-9-]/', '', strtolower($slug));
        $restaurant = $this->menu->findActiveRestaurantBySlug($slug);

        if (! $restaurant) {
            abort(404, 'Restaurant not found or inactive.');
        }

        $this->qr->trackScan((int) $restaurant->id, $request);

        $target = url('/restaurant/'.$slug);
        if ($section !== null) {
            $section = preg_replace('/[^a-z0-9-]/', '', strtolower($section));
            if ($section !== '') {
                $target .= '/'.$section;
            }
        }

        return redirect()->to($target);
    }
}
