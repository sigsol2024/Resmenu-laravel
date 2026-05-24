<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Services\CustomizationService;
use App\Services\MenuService;
use App\Services\MenuTemplateRenderService;
use App\Services\SubscriptionService;
use App\Services\UploadService;
use App\Support\MenuTemplateResolver;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function __construct(
        private MenuService $menu,
        private SubscriptionService $subscriptions,
        private CustomizationService $customization,
        private UploadService $uploads,
        private MenuTemplateResolver $templates,
        private MenuTemplateRenderService $templateRenderer,
    ) {}

    public function show(Request $request, string $slug, ?string $section = null)
    {
        $slug = preg_replace('/[^a-z0-9-]/', '', strtolower($slug));

        if (in_array($section, ['checkout', 'reservation'], true)) {
            abort(404);
        }

        $restaurant = $this->menu->findActiveRestaurantBySlug($slug);

        if (! $restaurant) {
            abort(404, 'Restaurant not found.');
        }

        $access = $this->subscriptions->checkAccess((int) $restaurant->id);
        if (! $access['valid']) {
            return view('public.subscription-blocked', [
                'restaurant' => $restaurant,
                'access' => $access,
                'uploads' => $this->uploads,
            ]);
        }

        $templateId = (int) ($restaurant->template_id ?? 4);
        if (! $this->templates->supportsTemplate($templateId)) {
            abort(500, 'Menu template not available.');
        }

        $sectionSlug = $section !== null ? preg_replace('/[^a-z0-9-]/', '', strtolower($section)) : null;
        $singleSection = $sectionSlug !== null && $sectionSlug !== '';

        if ($singleSection) {
            $sectionRow = $this->menu->sectionWithMenuBySlug($restaurant, $sectionSlug);
            if ($sectionRow === null) {
                abort(404, 'Section not found.');
            }
            $sections = [$sectionRow];
            $sectionsForNav = $this->menu->sectionsForNav((int) $restaurant->id);
        } else {
            $sections = $this->menu->sectionsWithMenu($restaurant);
            $sectionsForNav = $sections;
        }

        $viewData = [
            'restaurant' => $restaurant->toArray(),
            'sections' => $sections,
            'customization' => $this->customization->forRestaurant($restaurant),
            'headerMenuItems' => $restaurant->header_menu_items ?? [],
            'singleSectionView' => $singleSection,
            'fullMenuUrl' => url('/restaurant/'.$slug),
            'sectionsForNav' => $sectionsForNav,
            'uploadBaseUrl' => rtrim(config('resmenu.canonical_upload_url') ?: config('resmenu.upload_url'), '/'),
            'templateAssetBaseUrl' => url('/templates/template'.$templateId),
            'template4BaseUrl' => url('/templates/template4'),
            'supportsOrdering' => (bool) ($restaurant->enable_food_ordering ?? true),
            'supportsReservations' => (bool) ($restaurant->enable_table_reservations ?? false),
            'reservationUrl' => url('/restaurant/'.$slug.'/reservation'),
        ];

        if ($this->templates->hasBladeView($templateId)) {
            $view = $this->templates->bladeViewFor($templateId);

            return view($view, $viewData);
        }

        return $this->templateRenderer->render($templateId, $viewData);
    }
}
