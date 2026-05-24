<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Services\MenuService;
use App\Services\MenuTemplateRenderService;
use App\Support\MenuTemplateResolver;
use Illuminate\Http\Request;

class TemplatePreviewController extends Controller
{
    public function __construct(
        private MenuTemplateResolver $templates,
        private MenuService $menu,
    ) {}

    public function show(Request $request, int $template)
    {
        abort_unless($this->templates->supportsTemplate($template), 404);

        $payload = $this->menu->samplePreviewPayload($template);

        if ($blade = $this->templates->bladeViewFor($template)) {
            return view($blade, $payload);
        }

        return app(MenuTemplateRenderService::class)->render($template, $payload);
    }
}
