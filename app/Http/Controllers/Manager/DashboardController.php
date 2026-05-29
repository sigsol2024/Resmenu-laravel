<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\Restaurant;
use App\Services\OrderService;
use App\Services\QrAnalyticsService;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function __invoke(
        Request $request,
        SubscriptionService $subscriptions,
        OrderService $orders,
        QrAnalyticsService $qr,
    ) {
        $restaurantId = (int) $request->attributes->get('restaurant_id');
        $restaurant = Restaurant::findOrFail($restaurantId);
        $access = $subscriptions->checkAccess($restaurantId);

        $stats = [
            'categories' => Category::where('restaurant_id', $restaurantId)->count(),
            'menu_items' => MenuItem::where('restaurant_id', $restaurantId)->count(),
            'available_items' => MenuItem::where('restaurant_id', $restaurantId)->where('is_available', 1)->count(),
            'unavailable_items' => MenuItem::where('restaurant_id', $restaurantId)->where('is_available', 0)->count(),
            'total_orders' => Order::where('restaurant_id', $restaurantId)->count(),
            'total_orders_amount' => $orders->revenueTotal($restaurantId),
        ];

        $qrStats = $qr->summary($restaurantId);
        $subscription = $access['subscription'] ?? null;
        $trialDaysRemaining = 0;
        if (is_array($subscription) && ($subscription['status'] ?? '') === 'trial' && ! empty($subscription['trial_ends_at'])) {
            $trialEnd = Carbon::parse($subscription['trial_ends_at']);
            if ($trialEnd->isFuture()) {
                $trialDaysRemaining = (int) now()->diffInDays($trialEnd);
            }
        }

        return view('manager.dashboard', [
            'restaurant' => $restaurant,
            'access' => $access,
            'subscription' => $subscription,
            'trialDaysRemaining' => $trialDaysRemaining,
            'showOrdersQuickAction' => (int) ($restaurant->enable_food_ordering ?? 1) === 1,
            'stats' => $stats,
            'qrAnalytics' => $qrStats,
            'recentOrders' => $orders->recent($restaurantId, 5),
            'usageCategories' => $subscriptions->getRemainingUsage($restaurantId, 'categories'),
            'usageMenuItems' => $subscriptions->getRemainingUsage($restaurantId, 'menu_items'),
            'usageTemplates' => $subscriptions->getRemainingUsage($restaurantId, 'templates'),
            'usageQrStyles' => $subscriptions->getRemainingUsage($restaurantId, 'qr_styles'),
        ]);
    }
}
