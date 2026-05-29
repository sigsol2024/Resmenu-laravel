<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\TableReservation;
use App\Services\ManagerFeatureAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    public function __construct(private ManagerFeatureAccess $features) {}

    public function index(Request $request)
    {
        $restaurantId = (int) $request->attributes->get('restaurant_id');
        $restaurant = Restaurant::findOrFail($restaurantId);
        $overlay = $this->features->reservationsPageContext($restaurantId);

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

        $depositRow = DB::table('restaurant_reservation_settings')
            ->where('restaurant_id', $restaurantId)
            ->first();

        return view('manager.reservations.index', [
            'restaurant' => $restaurant,
            'stats' => $stats,
            'todayCount' => $todayCount,
            'recent' => $recent,
            'depositAmount' => (float) ($depositRow->deposit_amount ?? 0),
            'showUpgradeOverlay' => $overlay['show_overlay'],
            'upgradeMessage' => $overlay['message'],
        ]);
    }

    public function updateDeposit(Request $request)
    {
        $restaurantId = (int) $request->attributes->get('restaurant_id');
        if (! app(ManagerFeatureAccess::class)->planHasTableReservations($restaurantId)) {
            return back()->withErrors(['deposit' => 'Reservations not available on your plan.']);
        }

        $amount = max(0, (float) $request->validate(['deposit_amount' => 'required|numeric|min:0'])['deposit_amount']);

        $exists = DB::table('restaurant_reservation_settings')->where('restaurant_id', $restaurantId)->exists();
        if ($exists) {
            DB::table('restaurant_reservation_settings')
                ->where('restaurant_id', $restaurantId)
                ->update(['deposit_amount' => $amount, 'updated_at' => now()]);
        } else {
            DB::table('restaurant_reservation_settings')->insert([
                'restaurant_id' => $restaurantId,
                'deposit_amount' => $amount,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return back()->with('success', 'Deposit amount updated.');
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

        try {
            app(\App\Services\RestaurantTransactionalMailService::class)
                ->sendReservationStatusChange($reservation->id, $restaurantId, $data['status']);
        } catch (\Throwable $e) {
            report($e);
        }

        return redirect()
            ->route($request->input('return_to') === 'list' ? 'manager.reservations.list' : 'manager.reservations.index')
            ->with('success', 'Reservation status updated.');
    }
}
