<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
  public function __invoke()
  {
    $ordersRevenue = (float) DB::table('orders')
      ->whereIn('status', ['confirmed', 'on_hold', 'completed'])
      ->sum('total');

    $subscriptionRevenue = Schema::hasTable('payments')
      ? (float) DB::table('payments')->where('status', 'success')->sum('amount')
      : 0.0;

    // Platform “Total Revenue” = successful subscription payments (what admin Payments uses).
    $totalRevenue = $subscriptionRevenue;

    $totalScans = Schema::hasTable('qr_code_scans')
      ? (int) DB::table('qr_code_scans')->count()
      : 0;

    $totalOrders = (int) DB::table('orders')->count();

    $activeSubscriptions = 0;
    $expiredSubscriptions = 0;
    $activeTrials = 0;
    if (Schema::hasTable('subscriptions')) {
      // Paying / ongoing only — excludes trials.
      $activeSubscriptions = (int) DB::table('subscriptions')->where('status', 'active')->count();
      $expiredSubscriptions = (int) DB::table('subscriptions')->where('status', 'expired')->count();
      $activeTrials = (int) DB::table('subscriptions')->where('status', 'trial')->count();
    }

    $stats = [
      'restaurants' => (int) DB::table('restaurants')->count(),
      'active_subscriptions' => $activeSubscriptions,
      'expired_subscriptions' => $expiredSubscriptions,
      'active_trials' => $activeTrials,
      'categories' => (int) DB::table('categories')->count(),
      'menu_items' => (int) DB::table('menu_items')->count(),
      'total_orders' => $totalOrders,
      'total_scans' => $totalScans,
      'total_revenue' => $totalRevenue,
      'subscription_revenue' => $subscriptionRevenue,
      'orders_revenue' => $ordersRevenue,
    ];

    // Count-only chart — never mix ₦ amounts with counts (that made every bar look identical).
    $chartData = [
      ['label' => 'Restaurants', 'value' => $stats['restaurants'], 'color' => '#5EB344'],
      ['label' => 'Active Subscriptions', 'value' => $stats['active_subscriptions'], 'color' => '#963D97'],
      ['label' => 'Expired Subscriptions', 'value' => $stats['expired_subscriptions'], 'color' => '#E0393E'],
      ['label' => 'Active Trials', 'value' => $stats['active_trials'], 'color' => '#FCB72A'],
      ['label' => 'Categories', 'value' => $stats['categories'], 'color' => '#F8821A'],
      ['label' => 'Menu Items', 'value' => $stats['menu_items'], 'color' => '#069CDB'],
      ['label' => 'Orders', 'value' => $stats['total_orders'], 'color' => '#4f46e5'],
      ['label' => 'QR Scans', 'value' => $stats['total_scans'], 'color' => '#0d9488'],
    ];

    $maxValue = max(array_column($chartData, 'value')) ?: 1;
    foreach ($chartData as &$item) {
      $pct = ($item['value'] / $maxValue) * 100;
      $item['percentage'] = $item['value'] > 0 ? max($pct, 6) : 0;
    }
    unset($item);

    $recentRestaurants = Restaurant::query()
      ->orderByDesc('created_at')
      ->limit(7)
      ->get();

    $managerRestaurantIds = \App\Models\Manager::query()
      ->whereIn('restaurant_id', $recentRestaurants->pluck('id')->all())
      ->pluck('restaurant_id')
      ->flip();

    return view('admin.dashboard', compact('stats', 'chartData', 'recentRestaurants', 'managerRestaurantIds'));
  }
}
