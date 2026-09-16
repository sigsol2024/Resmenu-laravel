<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Services\MenuService;
use Illuminate\Http\Request;

class QrRedirectController extends Controller
{
    public function __construct(
        private MenuService $menu,
    ) {}

    public function __invoke(Request $request, string $slug, ?string $section = null)
    {
        $slug = preg_replace('/[^a-z0-9-]/', '', strtolower($slug));
        $restaurant = $this->menu->findActiveRestaurantBySlug($slug);

        if (! $restaurant) {
            abort(404, 'Restaurant not found or inactive.');
        }

        $target = url('/restaurant/'.$slug);
        if ($section !== null) {
            $section = preg_replace('/[^a-z0-9-]/', '', strtolower($section));
            if ($section !== '') {
                $target .= '/'.$section;
            }
        }

        // Menu page records the scan when it sees src=qr.
        $separator = str_contains($target, '?') ? '&' : '?';
        $target .= $separator.'src=qr';

        return redirect()->to($target);
    }
}
