<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Services\QrGeneratorService;
use Illuminate\Http\Request;

class QrTemplatePreviewController extends Controller
{
    public function __invoke(Request $request, QrGeneratorService $generator)
    {
        $templateId = (int) $request->query('template_id', 0);
        $size = max(50, min(400, (int) $request->query('size', 200)));

        if ($templateId < 1) {
            abort(400);
        }

        $result = $generator->generateTemplatePreviewImage($templateId, $size);
        if (! $result) {
            abort(404);
        }

        return response($result['body'], 200, [
            'Content-Type' => $result['content_type'],
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }
}
