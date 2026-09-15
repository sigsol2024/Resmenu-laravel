<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Services\ManagerFeatureAccess;
use Illuminate\Http\Request;

class TableInventoryController extends Controller
{
    public function __construct(private ManagerFeatureAccess $features) {}

    public function index(Request $request)
    {
        $restaurantId = (int) $request->attributes->get('restaurant_id');
        $restaurant = Restaurant::query()->findOrFail($restaurantId);

        $usable = $this->features->tableReservationsUsable($restaurantId);
        $planOverlay = $this->features->reservationsPageContext($restaurantId);

        $showUpgradeOverlay = ! $usable;
        $upgradeMessage = $planOverlay['show_overlay']
            ? $planOverlay['message']
            : 'Table reservations are turned off for your public menu. Enable them under Menu Designs → Ordering & Reservations.';

        return view('manager.table-inventory.index', [
            'restaurant' => $restaurant,
            'apiBase' => url('/api/table-inventory'),
            'showUpgradeOverlay' => $showUpgradeOverlay,
            'upgradeMessage' => $upgradeMessage,
        ]);
    }
}
