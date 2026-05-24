<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Services\UploadService;
use App\Support\ApiJsonResponse;

class RestaurantApiController extends Controller
{
    public function __construct(private UploadService $uploads) {}

    public function index()
    {
        $rows = Restaurant::query()
            ->where('is_active', 1)
            ->orderBy('name')
            ->get()
            ->map(fn (Restaurant $r) => [
                'id' => (int) $r->id,
                'name' => $r->name,
                'slug' => $r->slug,
                'logo' => $this->uploads->publicUrl('logos', $r->logo),
                'description' => $r->description,
                'phone' => $r->phone,
                'address' => $r->address,
                'email' => $r->email,
            ])
            ->values()
            ->all();

        return ApiJsonResponse::success('Restaurants retrieved successfully', $rows);
    }
}
