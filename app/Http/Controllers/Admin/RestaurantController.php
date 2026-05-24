<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;

class RestaurantController extends Controller
{
    public function index()
    {
        $restaurants = Restaurant::query()->orderByDesc('id')->paginate(25);

        return view('admin.restaurants.index', compact('restaurants'));
    }

    public function show(Restaurant $restaurant)
    {
        $restaurant->load(['sections', 'categories']);

        return view('admin.restaurants.show', compact('restaurant'));
    }
}
