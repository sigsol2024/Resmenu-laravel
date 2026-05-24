<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Restaurant;
use App\Services\OrderService;
use App\Support\PriceFormatter;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(private OrderService $orders) {}

    public function index(Request $request)
    {
        $restaurantId = (int) $request->attributes->get('restaurant_id');
        $restaurant = Restaurant::findOrFail($restaurantId);
        $stats = $this->orders->countByStatus($restaurantId);

        return view('manager.orders.index', [
            'restaurant' => $restaurant,
            'stats' => $stats,
            'totalCount' => array_sum($stats),
            'revenue' => $this->orders->revenueTotal($restaurantId),
            'recent' => $this->orders->recent($restaurantId),
            'price' => PriceFormatter::class,
        ]);
    }

    public function list(Request $request)
    {
        $restaurantId = (int) $request->attributes->get('restaurant_id');
        $restaurant = Restaurant::findOrFail($restaurantId);

        $query = Order::query()->where('restaurant_id', $restaurantId)->orderByDesc('created_at');

        if ($status = $request->query('status')) {
            if ($status !== 'all' && in_array($status, Order::STATUSES, true)) {
                $query->where('status', $status);
            }
        }

        if ($from = $request->query('start_date')) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to = $request->query('end_date')) {
            $query->whereDate('created_at', '<=', $to);
        }

        return view('manager.orders.list', [
            'restaurant' => $restaurant,
            'orders' => $query->paginate(30)->withQueryString(),
            'statuses' => Order::STATUSES,
            'filters' => $request->only(['status', 'start_date', 'end_date']),
            'price' => PriceFormatter::class,
        ]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $restaurantId = (int) $request->attributes->get('restaurant_id');
        if ((int) $order->restaurant_id !== $restaurantId) {
            abort(403);
        }

        $data = $request->validate([
            'status' => ['required', 'in:'.implode(',', Order::STATUSES)],
        ]);

        $order->status = $data['status'];
        $order->save();

        return redirect()
            ->route($request->input('return_to') === 'list' ? 'manager.orders.list' : 'manager.orders.index')
            ->with('success', 'Order status updated.');
    }
}
