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

    $stats = [
      'restaurants' => (int) DB::table('restaurants')->count(),
      'categories' => (int) DB::table('categories')->count(),
      'menu_items' => (int) DB::table('menu_items')->count(),
      'managers' => (int) DB::table('managers')->count(),
      'active_restaurants' => (int) DB::table('restaurants')->where('is_active', 1)->count(),
      'active_categories' => (int) DB::table('categories')->where('is_active', 1)->count(),
      'total_orders' => $totalOrders,
      'total_scans' => $totalScans,
      'total_revenue' => $totalRevenue,
      'subscription_revenue' => $subscriptionRevenue,
      'orders_revenue' => $ordersRevenue,
    ];

    // Count-only chart — never mix ₦ amounts with counts (that made every bar look identical).
    $chartData = [
      ['label' => 'Restaurants', 'value' => $stats['restaurants'], 'color' => '#5EB344'],
      ['label' => 'Active Restaurants', 'value' => $stats['active_restaurants'], 'color' => '#963D97'],
      ['label' => 'Categories', 'value' => $stats['categories'], 'color' => '#FCB72A'],
      ['label' => 'Menu Items', 'value' => $stats['menu_items'], 'color' => '#F8821A'],
      ['label' => 'Managers', 'value' => $stats['managers'], 'color' => '#E0393E'],
      ['label' => 'Orders', 'value' => $stats['total_orders'], 'color' => '#4f46e5'],
      ['label' => 'QR Scans', 'value' => $stats['total_scans'], 'color' => '#069CDB'],
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
