<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\Subscription;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return view('admin.dashboard', [
            'restaurantCount' => Restaurant::count(),
            'subscriptionCount' => Subscription::count(),
        ]);
    }
}
