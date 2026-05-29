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
            ->orderBy('id')
            ->limit($limit)
            ->get()
            ->map(fn ($t) => [
                'id' => (int) $t->id,
                'name' => $t->name,
                'slug' => $t->slug,
                'description' => $t->description,
                'cover_image' => $t->preview_image ? $this->uploads->publicUrl('template-previews', $t->preview_image) : null,
                'listing_image' => $t->listing_image ? $this->uploads->publicUrl('template-previews', $t->listing_image) : null,
            ])
            ->values()
            ->all();

        return ApiJsonResponse::success('Templates retrieved successfully', $templates);
    }
}
