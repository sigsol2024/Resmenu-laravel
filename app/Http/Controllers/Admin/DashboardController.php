<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
  public function __invoke()
  {
    $stats = [
      'restaurants' => (int) DB::table('restaurants')->count(),
      'categories' => (int) DB::table('categories')->count(),
      'menu_items' => (int) DB::table('menu_items')->count(),
      'managers' => (int) DB::table('managers')->count(),
      'active_restaurants' => (int) DB::table('restaurants')->where('is_active', 1)->count(),
      'active_categories' => (int) DB::table('categories')->where('is_active', 1)->count(),
      'total_revenue' => (float) DB::table('orders')
        ->whereIn('status', ['pending', 'confirmed', 'on_hold', 'completed'])
        ->sum('total'),
    ];

    $chartData = [
      ['label' => 'Restaurants', 'value' => $stats['restaurants'], 'color' => '#5EB344'],
      ['label' => 'Categories', 'value' => $stats['categories'], 'color' => '#FCB72A'],
      ['label' => 'Menu Items', 'value' => $stats['menu_items'], 'color' => '#F8821A'],
      ['label' => 'Managers', 'value' => $stats['managers'], 'color' => '#E0393E'],
      ['label' => 'Active Restaurants', 'value' => $stats['active_restaurants'], 'color' => '#963D97'],
      ['label' => 'Active Categories', 'value' => $stats['active_categories'], 'color' => '#069CDB'],
      ['label' => 'Total Revenue (₦)', 'value' => (int) $stats['total_revenue'], 'color' => '#10b981'],
    ];

    $maxValue = max(array_column($chartData, 'value')) ?: 1;
    foreach ($chartData as &$item) {
      $item['percentage'] = ($item['value'] / $maxValue) * 100;
    }
    unset($item);

    $recentRestaurants = Restaurant::query()
      ->orderByDesc('created_at')
      ->limit(7)
      ->get();

    return view('admin.dashboard', compact('stats', 'chartData', 'recentRestaurants'));
  }
}
