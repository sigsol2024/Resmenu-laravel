<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Services\CustomizationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomizationController extends Controller
{
    public function __construct(private CustomizationService $customization) {}

    public function index(Request $request)
    {
        $restaurantId = (int) $request->attributes->get('restaurant_id');
        $restaurant = Restaurant::findOrFail($restaurantId);

        if ($request->isMethod('post')) {
            $data = $request->validate([
                'template_id' => 'nullable|integer|min:1|max:99',
                'primary_color' => 'nullable|string|max:20',
                'background_color' => 'nullable|string|max:20',
                'menu_title_color' => 'nullable|string|max:20',
                'price_color' => 'nullable|string|max:20',
            ]);

            if (isset($data['template_id'])) {
                $restaurant->update(['template_id' => $data['template_id']]);
            }

            $this->customization->saveForRestaurant($restaurantId, $data);

            return back()->with('success', 'Template settings saved.');
        }

        $templates = DB::table('templates')->where('is_active', 1)->orderBy('display_order')->get();

        return view('manager.customization.index', [
            'restaurant' => $restaurant,
            'customization' => $this->customization->forRestaurant($restaurant),
            'templates' => $templates,
        ]);
    }
}
