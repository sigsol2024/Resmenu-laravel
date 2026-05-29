<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class TableInventoryController extends Controller
{
  public function index(Request $request)
  {
    $restaurant = Restaurant::query()->findOrFail((int) $request->attributes->get('restaurant_id'));

    return view('manager.table-inventory.index', [
      'restaurant' => $restaurant,
      'apiBase' => url('/api/table-inventory'),
    ]);
  }
}
