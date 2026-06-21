<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Services\CustomizationService;
use App\Services\MenuService;
use App\Services\MenuTemplateRenderService;
use App\Services\ReservationSlotService;
use App\Services\SubscriptionService;
use App\Services\UploadService;
use App\Support\LegacyMenuViewData;
use App\Support\MenuTemplateResolver;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class MenuController extends Controller
{
    private const TEMPLATE_SIX_ID = 6;

    public function __construct(
        private MenuService $menu,
        private SubscriptionService $subscriptions,
        private CustomizationService $customization,
        private UploadService $uploads,
        private MenuTemplateResolver $templates,
        private MenuTemplateRenderService $templateRenderer,
        private ReservationSlotService $reservationSlots,
    ) {}

    public function show(Request $request, string $slug, ?string $section = null, ?string $category = null): Response|RedirectResponse
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

        $categorySlug = $category !== null ? preg_replace('/[^a-z0-9-]/', '', strtolower($category)) : null;
        $hasCategorySlug = $categorySlug !== null && $categorySlug !== '';

        if ($hasCategorySlug && $templateId !== self::TEMPLATE_SIX_ID) {
            abort(404);
        }

        $sectionSlug = $section !== null ? preg_replace('/[^a-z0-9-]/', '', strtolower($section)) : null;
        $singleSection = $sectionSlug !== null && $sectionSlug !== '';

        if ($templateId === self::TEMPLATE_SIX_ID) {
            return $this->renderTemplateSix($request, $restaurant, $slug, $sectionSlug, $categorySlug, $templateId);
        }

        if ($hasCategorySlug) {
            abort(404);
        }

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

        $sections = LegacyMenuViewData::normalizeSections($sections);
        $categories = LegacyMenuViewData::flattenCategoriesFromSections($sections);

        $viewData = $this->baseViewData($restaurant, $slug, $templateId, $sections, $categories, $sectionsForNav, [
            'singleSectionView' => $singleSection,
            'menuViewLevel' => $singleSection ? 'section' : 'home',
            'activeSection' => $singleSection ? ($sections[0] ?? null) : null,
            'activeCategory' => null,
        ]);

        return $this->renderMenu($templateId, $viewData);
    }

    private function renderTemplateSix(
        Request $request,
        $restaurant,
        string $slug,
        ?string $sectionSlug,
        ?string $categorySlug,
        int $templateId,
    ): Response|RedirectResponse {
        $baseUrl = url('/restaurant/'.$slug);
        $menuViewLevel = 'home';
        $sections = [];
        $sectionsForNav = $this->menu->sectionsForNav((int) $restaurant->id);
        $activeSection = null;
        $activeCategory = null;
        $singleSectionView = false;
        $popularItems = [];

        if ($categorySlug !== null && $categorySlug !== '' && $sectionSlug !== null && $sectionSlug !== '') {
            $result = $this->menu->categoryWithMenuInSection($restaurant, $sectionSlug, $categorySlug);
            if ($result === null) {
                abort(404, 'Category not found.');
            }
            $activeSection = LegacyMenuViewData::normalizeSections([$result['section']])[0];
            $activeCategory = LegacyMenuViewData::normalizeCategories([$result['category']])[0];
            $sections = [$activeSection];
            $menuViewLevel = 'category';
            $singleSectionView = true;
        } elseif ($sectionSlug !== null && $sectionSlug !== '') {
            $sectionRowFull = $this->menu->sectionWithMenuBySlug($restaurant, $sectionSlug);
            if ($sectionRowFull === null) {
                abort(404, 'Section not found.');
            }
            $visibleCategories = array_values(array_filter(
                $sectionRowFull['categories'] ?? [],
                static fn (array $cat): bool => ! empty($cat['is_active']) && ! empty($cat['menu_items'])
            ));
            if (count($visibleCategories) === 1) {
                $only = $visibleCategories[0];
                $catSlug = $only['slug'] ?? '';

                return redirect()->to($baseUrl.'/'.$sectionSlug.'/'.$catSlug);
            }
            $sectionRow = $this->menu->sectionWithCategoriesOnlyBySlug($restaurant, $sectionSlug);
            if ($sectionRow === null) {
                abort(404, 'Section not found.');
            }
            $activeSection = LegacyMenuViewData::normalizeSections([$sectionRow])[0];
            $sections = [$activeSection];
            $menuViewLevel = 'section';
            $singleSectionView = true;
        } else {
            $sections = LegacyMenuViewData::normalizeSections($this->menu->sectionsForHome($restaurant));
            $popularItems = LegacyMenuViewData::normalizeMenuItems($this->menu->popularMenuItems($restaurant, 3));
            if (count($sections) === 1) {
                $onlySection = $sections[0];
                $secSlug = $onlySection['slug'] ?? 'menu';

                return redirect()->to($baseUrl.'/'.$secSlug);
            }
            $menuViewLevel = 'home';
        }

        $extraPopular = ($menuViewLevel === 'home') ? ['popularItems' => $popularItems ?? []] : [];

        $categories = LegacyMenuViewData::flattenCategoriesFromSections($sections);

        $sectionMenuUrl = ($activeSection && ! empty($activeSection['slug']))
            ? $baseUrl.'/'.$activeSection['slug']
            : null;
        $categoryMenuUrl = ($sectionMenuUrl && $activeCategory && ! empty($activeCategory['slug']))
            ? $sectionMenuUrl.'/'.$activeCategory['slug']
            : null;

        return $this->templateRenderer->render($templateId, $this->baseViewData($restaurant, $slug, $templateId, $sections, $categories, $sectionsForNav, array_merge([
            'singleSectionView' => $singleSectionView,
            'menuViewLevel' => $menuViewLevel,
            'activeSection' => $activeSection,
            'activeCategory' => $activeCategory,
            'sectionMenuUrl' => $sectionMenuUrl,
            'categoryMenuUrl' => $categoryMenuUrl,
        ], $extraPopular, (in_array($menuViewLevel, ['home', 'section'], true) ? $this->reservationFormPayload($restaurant, $slug) : []))));
    }

    /** @return array<string, mixed> */
    private function reservationFormPayload($restaurant, string $slug): array
    {
        if (! ($restaurant->enable_table_reservations ?? false)) {
            return [];
        }

        $selectedDate = date('Y-m-d');
        $slotPayload = $this->reservationSlots->slotsForDate((int) $restaurant->id, $selectedDate);
        $depositAmount = (float) (DB::table('restaurant_reservation_settings')
            ->where('restaurant_id', $restaurant->id)
            ->value('deposit_amount') ?? 0);

        return [
            'reservationFormData' => [
                'csrfToken' => csrf_token(),
                'actionUrl' => url('/restaurant/'.$slug.'/reservation'),
                'slug' => $slug,
                'depositAmount' => $depositAmount,
                'selectedDate' => $selectedDate,
                'minDate' => date('Y-m-d'),
                'timeSlots' => $slotPayload['slots'] ?? [],
                'primaryColor' => '#f0be78',
                'siteBase' => rtrim(url('/'), '/'),
            ],
        ];
    }

    /**
     * @param  list<array<string, mixed>>  $sections
     * @param  list<array<string, mixed>>  $categories
     * @param  list<array<string, mixed>>  $sectionsForNav
     * @param  array<string, mixed>  $extra
     * @return array<string, mixed>
     */
    private function baseViewData(
        $restaurant,
        string $slug,
        int $templateId,
        array $sections,
        array $categories,
        array $sectionsForNav,
        array $extra = [],
    ): array {
        return array_merge([
            'restaurant' => LegacyMenuViewData::normalizeRestaurant(
                $restaurant->toArray(),
                rtrim(config('resmenu.canonical_upload_url') ?: config('resmenu.upload_url'), '/')
            ),
            'sections' => $sections,
            'categories' => $categories,
            'customization' => $this->customization->forRestaurant($restaurant),
            'headerMenuItems' => $restaurant->header_menu_items ?? [],
            'fullMenuUrl' => url('/restaurant/'.$slug),
            'sectionsForNav' => $sectionsForNav,
            'uploadBaseUrl' => rtrim(config('resmenu.canonical_upload_url') ?: config('resmenu.upload_url'), '/'),
            'templateAssetBaseUrl' => url('/templates/template'.$templateId),
            'template4BaseUrl' => url('/templates/template4'),
            'supportsOrdering' => (bool) ($restaurant->enable_food_ordering ?? true),
            'supportsReservations' => (bool) ($restaurant->enable_table_reservations ?? false),
            'reservationUrl' => url('/restaurant/'.$slug.'/reservation'),
            'menuViewLevel' => 'home',
            'activeSection' => null,
            'activeCategory' => null,
            'sectionMenuUrl' => null,
            'categoryMenuUrl' => null,
            'singleSectionView' => false,
            'popularItems' => [],
        ], $extra);
    }

    /** @param  array<string, mixed>  $viewData */
    private function renderMenu(int $templateId, array $viewData): Response
    {
        if ($this->templates->hasBladeView($templateId)) {
            return view($this->templates->bladeViewFor($templateId), $viewData);
        }

        return $this->templateRenderer->render($templateId, $viewData);
    }
}
