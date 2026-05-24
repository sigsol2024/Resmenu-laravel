<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Services\QrAnalyticsService;
use Illuminate\Http\Request;

class QrController extends Controller
{
    public function __construct(private QrAnalyticsService $qr) {}

    public function code(Request $request)
    {
        $restaurant = Restaurant::findOrFail((int) $request->attributes->get('restaurant_id'));

        return view('manager.qr.code', [
            'restaurant' => $restaurant,
            'menuUrl' => url('/restaurant/'.$restaurant->slug),
            'qrUrl' => url('/qr/'.$restaurant->slug),
        ]);
    }

    public function analytics(Request $request)
    {
        $restaurantId = (int) $request->attributes->get('restaurant_id');

        return view('manager.qr.analytics', [
            'restaurant' => Restaurant::findOrFail($restaurantId),
            'analytics' => $this->qr->summary($restaurantId),
        ]);
    }
}
