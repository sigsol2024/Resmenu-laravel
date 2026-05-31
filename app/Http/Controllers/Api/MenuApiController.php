<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CustomizationService;
use App\Services\MenuService;
use App\Services\SubscriptionService;
use App\Support\ApiJsonResponse;

class MenuApiController extends Controller
{
    public function __construct(
        private MenuService $menu,
        private CustomizationService $customization,
        private SubscriptionService $subscriptions,
    ) {}

    public function show(string $slug)
    {
        $slug = preg_replace('/[^a-z0-9-]/', '', strtolower($slug));
        $restaurant = $this->menu->findActiveRestaurantBySlug($slug);

        if (! $restaurant) {
            return ApiJsonResponse::error('Restaurant not found', null, 404);
        }

        $access = $this->subscriptions->checkAccess((int) $restaurant->id);
        if (! $access['valid']) {
            return ApiJsonResponse::error($access['message'] ?: 'Subscription inactive', null, 402);
        }

        return ApiJsonResponse::success('Menu retrieved successfully', [
            'restaurant' => [
                'id' => (int) $restaurant->id,
                'name' => $restaurant->name,
                'slug' => $restaurant->slug,
                'description' => $restaurant->description,
                'phone' => $restaurant->phone,
                'address' => $restaurant->address,
                'logo' => $restaurant->logo,
                'template_id' => (int) $restaurant->template_id,
            ],
            'sections' => $this->menu->sectionsWithMenu($restaurant),
            'customization' => $this->customization->forRestaurant($restaurant),
        ]);
    }
}
