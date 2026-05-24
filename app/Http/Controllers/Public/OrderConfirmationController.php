<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\CustomizationService;
use App\Support\OrderConfirmationToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderConfirmationController extends Controller
{
    public function __construct(private CustomizationService $customization) {}

    public function show(Request $request, Order $order)
    {
        $order->load('restaurant');
        $restaurant = $order->restaurant;
        abort_unless($restaurant instanceof \App\Models\Restaurant, 404);

        if (! OrderConfirmationToken::verify($request->query(), (int) $order->id)) {
            abort(404);
        }

        $items = DB::table('order_items')
            ->where('order_id', $order->id)
            ->get();

        return view('public.order-confirmation', [
            'order' => $order,
            'restaurant' => $restaurant,
            'items' => $items,
            'customization' => $this->customization->forRestaurant($restaurant),
        ]);
    }
}
