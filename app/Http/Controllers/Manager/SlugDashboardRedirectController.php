<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SlugDashboardRedirectController extends Controller
{
    public function __invoke(Request $request, string $slug)
    {
        $slug = preg_replace('/[^a-z0-9-]/', '', strtolower($slug));
        $manager = auth('manager')->user();
        if ($manager?->restaurant && $manager->restaurant->slug === $slug) {
            return redirect()->route('manager.dashboard');
        }

        abort(404);
    }
}
