<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Services\CustomizationService;
use App\Services\ManagerFeatureAccess;
use App\Services\TemplateAvailabilityService;
use Illuminate\Http\Request;

class CustomizationController extends Controller
{
    public function __construct(
        private CustomizationService $customization,
        private TemplateAvailabilityService $templates,
        private ManagerFeatureAccess $features,
    ) {}

    public function index(Request $request)
    {
        $restaurantId = (int) $request->attributes->get('restaurant_id');
        $restaurant = Restaurant::findOrFail($restaurantId);

        if ($request->isMethod('post')) {
            $action = $request->input('action', 'save_customization');

            if ($action === 'save_template') {
                return $this->saveTemplate($request, $restaurant);
            }
            if ($action === 'save_feature_toggles') {
                return $this->saveFeatureToggles($request, $restaurant);
            }

            return $this->saveCustomization($request, $restaurant);
        }

        $available = $this->templates->availableForRestaurant($restaurantId);
        $templatesCanUse = array_values(array_filter($available, fn ($t) => ! empty($t['can_use'])));
        $templatesUpgrade = array_values(array_filter(
            $available,
            fn ($t) => ! empty($t['can_see']) && empty($t['can_use']),
        ));
        $currentTemplateId = (int) ($restaurant->template_id ?? 1);
        $currentTemplate = collect($available)->firstWhere('id', $currentTemplateId);

        return view('manager.customization.index', [
            'restaurant' => $restaurant,
            'customization' => $this->customization->forRestaurant($restaurant),
            'templates' => $available,
            'templatesCanUse' => $templatesCanUse,
            'templatesUpgrade' => $templatesUpgrade,
            'currentTemplateId' => $currentTemplateId,
            'currentTemplateName' => $currentTemplate['name'] ?? null,
            'currentInCanUse' => collect($templatesCanUse)->contains('id', $currentTemplateId),
            'planHasOrdering' => $this->features->planHasFoodOrdering($restaurantId),
            'planHasReservations' => $this->features->planHasTableReservations($restaurantId),
            'enableFoodOrdering' => (int) ($restaurant->enable_food_ordering ?? 1),
            'enableTableReservations' => (int) ($restaurant->enable_table_reservations ?? 1),
            'flashMessage' => $this->flashMessage($request->query('message')),
        ]);
    }

    private function saveTemplate(Request $request, Restaurant $restaurant)
    {
        $data = $request->validate([
            'template_id' => 'required|integer|min:1|max:99',
        ]);

        $templateId = (int) $data['template_id'];
        $available = $this->templates->availableForRestaurant($restaurant->id);
        $canSee = array_column($available, 'id');
        $canUse = array_column(array_filter($available, fn ($t) => ! empty($t['can_use'])), 'id');

        if (! in_array($templateId, $canSee, true)) {
            return back()->withErrors(['template_id' => 'You do not have access to this template.']);
        }
        if (! in_array($templateId, $canUse, true)) {
            return back()->withErrors(['template_id' => 'This template requires a higher subscription plan.']);
        }

        $restaurant->update(['template_id' => $templateId]);

        return redirect()->route('manager.customization', ['message' => 'template_updated']);
    }

    private function saveFeatureToggles(Request $request, Restaurant $restaurant)
    {
        $restaurantId = (int) $restaurant->id;
        $enableOrdering = $this->features->planHasFoodOrdering($restaurantId)
            ? $request->boolean('enable_food_ordering')
            : false;
        $enableReservations = $this->features->planHasTableReservations($restaurantId)
            ? $request->boolean('enable_table_reservations')
            : false;

        $restaurant->update([
            'enable_food_ordering' => $enableOrdering ? 1 : 0,
            'enable_table_reservations' => $enableReservations ? 1 : 0,
        ]);

        return redirect()->route('manager.customization', ['message' => 'features_updated']);
    }

    private function saveCustomization(Request $request, Restaurant $restaurant)
    {
        $data = $request->validate([
            'template_id' => 'nullable|integer|min:1|max:99',
            'menu_title_color' => 'nullable|string|max:20',
            'menu_title_size' => 'nullable|integer|min:12|max:72',
            'menu_title_font' => 'nullable|string|max:50',
            'price_color' => 'nullable|string|max:20',
            'price_size' => 'nullable|integer|min:12|max:48',
            'price_font' => 'nullable|string|max:50',
            'description_color' => 'nullable|string|max:20',
            'description_size' => 'nullable|integer|min:10|max:24',
            'description_font' => 'nullable|string|max:50',
            'category_title_color' => 'nullable|string|max:20',
            'category_title_size' => 'nullable|integer|min:12|max:48',
            'category_title_font' => 'nullable|string|max:50',
            'background_color' => 'nullable|string|max:20',
            'header_background_color' => 'nullable|string|max:20',
            'primary_color' => 'nullable|string|max:20',
            'secondary_color' => 'nullable|string|max:20',
        ]);

        $this->customization->saveForRestaurant($restaurant->id, $data);

        return redirect()->route('manager.customization', ['message' => 'customization_updated']);
    }

    private function flashMessage(?string $key): ?string
    {
        return match ($key) {
            'template_updated' => 'Template updated successfully',
            'customization_updated' => 'Template colors and styles saved. Each template keeps its own settings when you switch.',
            'features_updated' => 'Ordering & reservation settings updated for your menu.',
            'ordering_disabled' => 'Food ordering is turned off for your public menu. Turn it back on under Ordering & reservations below.',
            'reservations_disabled' => 'Table reservations are turned off for your public menu. Turn them back on under Ordering & reservations below.',
            default => null,
        };
    }
}
