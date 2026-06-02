<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\UploadService;
use App\Support\ApiJsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TemplateApiController extends Controller
{
    public function __construct(private UploadService $uploads) {}

    public function index(Request $request)
    {
        $limit = min(50, max(1, (int) $request->query('limit', 20)));

        $templates = DB::table('templates')
            ->where('is_active', 1)
            ->whereRaw('COALESCE(is_private, 0) = 0')
            ->orderByDesc('id')
            ->limit($limit)
            ->get()
            ->map(function ($t) {
                $previewUrl = $t->preview_image
                    ? $this->uploads->publicUrl('template-previews', $t->preview_image)
                    : null;
                $listingUrl = $t->listing_image
                    ? $this->uploads->publicUrl('template-previews', $t->listing_image)
                    : null;

                return [
                    'id' => (int) $t->id,
                    'name' => $t->name,
                    'slug' => $t->slug,
                    'description' => $t->description,
                    'preview_image' => $previewUrl,
                    'listing_image' => $listingUrl,
                    'cover_image' => $previewUrl,
                ];
            })
            ->values()
            ->all();

        return ApiJsonResponse::success('Templates retrieved successfully', $templates);
    }
}
