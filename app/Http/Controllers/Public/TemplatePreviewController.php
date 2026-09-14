<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Services\MenuService;
use App\Services\MenuTemplateRenderService;
use App\Support\LegacyMenuViewData;
use App\Support\MenuTemplateResolver;
use Illuminate\Http\Request;

class TemplatePreviewController extends Controller
{
    public function __construct(
        private MenuTemplateResolver $templates,
        private MenuService $menu,
    ) {}

    public function show(Request $request, int $template, ?string $section = null, ?string $category = null)
    {
        abort_unless($this->templates->supportsTemplate($template), 404);

        $needsFullCatalogue = $section !== null && $section !== '';
        $payload = $this->menu->samplePreviewPayload($template, $needsFullCatalogue);

        if ($needsFullCatalogue) {
            $payload = $this->applySectionView($payload, $section, $category);
        }

        if ($blade = $this->templates->bladeViewFor($template)) {
            return view($blade, $payload);
        }

        return app(MenuTemplateRenderService::class)->render($template, $payload);
    }

    /**
     * Demo-only reservation submit — never creates a real reservation.
     */
    public function reservationDemo(Request $request)
    {
        $request->validate([
            'reservation_date' => 'required|date',
            'reservation_time' => 'required|string|max:20',
            'guest_name' => 'required|string|max:120',
            'guest_email' => 'nullable|email|max:255',
            'guest_phone' => 'nullable|string|max:40',
            'party_size' => 'nullable|integer|min:1|max:50',
            'return_url' => 'nullable|string|max:500',
        ]);

        $return = (string) $request->input('return_url', '');
        if ($return === '' || ! str_starts_with($return, url('/templates/'))) {
            $return = url('/templates/1/preview').'#reservation';
        }
        if (! str_contains($return, '#reservation')) {
            $return .= '#reservation';
        }

        return redirect($return)->with('reservation_success', true)->with(
            'success',
            'Demo only — this reservation was not saved. Sign up to take real bookings.'
        );
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    private function applySectionView(array $payload, string $sectionSlug, ?string $categorySlug): array
    {
        $sections = is_array($payload['sections'] ?? null) ? $payload['sections'] : [];
        $active = null;
        foreach ($sections as $section) {
            if (! is_array($section)) {
                continue;
            }
            if (strtolower((string) ($section['slug'] ?? '')) === strtolower($sectionSlug)) {
                $active = $section;
                break;
            }
        }

        abort_if($active === null, 404, 'Section not found.');

        if ($categorySlug !== null && $categorySlug !== '') {
            $cats = [];
            foreach ($active['categories'] ?? [] as $cat) {
                if (! is_array($cat)) {
                    continue;
                }
                if (strtolower((string) ($cat['slug'] ?? '')) === strtolower($categorySlug)) {
                    $cats[] = $cat;
                }
            }
            abort_if($cats === [], 404, 'Category not found.');
            $active['categories'] = $cats;
            $payload['menuViewLevel'] = 'category';
            $payload['activeCategory'] = $cats[0] ?? null;
            $payload['categoryMenuUrl'] = rtrim((string) ($payload['fullMenuUrl'] ?? ''), '/').'/'.$sectionSlug.'/'.$categorySlug;
        } else {
            $payload['menuViewLevel'] = 'section';
            $payload['activeCategory'] = null;
            $payload['categoryMenuUrl'] = null;
        }

        $normalized = LegacyMenuViewData::normalizeSections([$active]);
        $active = $normalized[0] ?? $active;

        $payload['singleSectionView'] = true;
        $payload['activeSection'] = $active;
        $payload['sections'] = [$active];
        $payload['categories'] = LegacyMenuViewData::flattenCategoriesFromSections([$active]);
        $payload['sectionMenuUrl'] = rtrim((string) ($payload['fullMenuUrl'] ?? ''), '/').'/'.$sectionSlug;

        if (($payload['menuViewLevel'] ?? '') !== 'category') {
            $payload['menuViewLevel'] = 'section';
        }

        return $payload;
    }
}
