<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\TableReservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $restaurantId = (int) $request->attributes->get('restaurant_id');
        $restaurant = Restaurant::findOrFail($restaurantId);

        $stats = TableReservation::query()
            ->where('restaurant_id', $restaurantId)
            ->select('status', DB::raw('COUNT(*) as cnt'))
            ->groupBy('status')
            ->pluck('cnt', 'status')
            ->all();

        $todayCount = TableReservation::query()
            ->where('restaurant_id', $restaurantId)
            ->whereDate('reservation_date', today())
            ->count();

        $recent = TableReservation::query()
            ->where('restaurant_id', $restaurantId)
            ->orderByDesc('reservation_date')
            ->orderByDesc('reservation_time')
            ->limit(5)
            ->get();

        return view('manager.reservations.index', [
            'restaurant' => $restaurant,
            'stats' => $stats,
            'todayCount' => $todayCount,
            'recent' => $recent,
        ]);
    }

    public function list(Request $request)
    {
        $restaurantId = (int) $request->attributes->get('restaurant_id');
        $restaurant = Restaurant::findOrFail($restaurantId);

        $query = TableReservation::query()
            ->where('restaurant_id', $restaurantId)
            ->orderByDesc('reservation_date')
            ->orderByDesc('reservation_time');

        if ($status = $request->query('status')) {
            if ($status !== 'all' && in_array($status, TableReservation::STATUSES, true)) {
                $query->where('status', $status);
            }
        }

        return view('manager.reservations.list', [
            'restaurant' => $restaurant,
            'reservations' => $query->paginate(30)->withQueryString(),
            'statuses' => TableReservation::STATUSES,
            'filters' => $request->only(['status']),
        ]);
    }

    public function updateStatus(Request $request, TableReservation $reservation)
    {
        $restaurantId = (int) $request->attributes->get('restaurant_id');
        if ((int) $reservation->restaurant_id !== $restaurantId) {
            abort(403);
        }

        $data = $request->validate([
            'status' => ['required', 'in:'.implode(',', TableReservation::STATUSES)],
        ]);

        $reservation->status = $data['status'];
        $reservation->save();

        return redirect()
            ->route($request->input('return_to') === 'list' ? 'manager.reservations.list' : 'manager.reservations.index')
            ->with('success', 'Reservation status updated.');
    }
}
