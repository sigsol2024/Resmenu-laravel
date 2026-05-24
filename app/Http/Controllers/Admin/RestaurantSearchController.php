<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class RestaurantSearchController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $results = Restaurant::query()
            ->when($q !== '', fn ($query) => $query->where('name', 'like', '%'.$q.'%')->orWhere('slug', 'like', '%'.$q.'%'))
            ->orderBy('name')
            ->limit(20)
            ->get(['id', 'name', 'slug']);

        return response()->json(['results' => $results]);
    }
}
