<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TableReservation;
use App\Services\MenuService;
use App\Services\ReservationBookingService;
use App\Services\ReservationSlotService;
use App\Services\TableInventoryService;
use App\Support\ApiJsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservationApiController extends Controller
{
    public function slots(Request $request, MenuService $menu, ReservationSlotService $slots)
    {
        $data = $request->validate([
            'slug' => 'required|string|max:255',
            'date' => 'required|date_format:Y-m-d',
        ]);

        $restaurant = $menu->findActiveRestaurantBySlug($data['slug']);
        if (! $restaurant) {
            return ApiJsonResponse::error('Restaurant not found', null, 404);
        }

        if ($message = $slots->restaurantCanBook((int) $restaurant->id)) {
            return ApiJsonResponse::error($message, null, 403);
        }

        $payload = $slots->slotsForDate((int) $restaurant->id, $data['date']);

        return ApiJsonResponse::success('Slots retrieved', $payload);
    }

    public function availability(Request $request, MenuService $menu, ReservationSlotService $slots)
    {
        $data = $request->validate([
            'slug' => 'required|string|max:255',
            'year' => 'nullable|integer|min:2020|max:2100',
            'month' => 'nullable|integer|min:1|max:12',
            'start' => 'nullable|date_format:Y-m-d',
            'end' => 'nullable|date_format:Y-m-d',
        ]);

        $restaurant = $menu->findActiveRestaurantBySlug($data['slug']);
        if (! $restaurant) {
            return ApiJsonResponse::error('Restaurant not found', null, 404);
        }

        if ($message = $slots->restaurantCanBook((int) $restaurant->id)) {
            return ApiJsonResponse::error($message, null, 403);
        }

        if (! empty($data['start']) && ! empty($data['end'])) {
            return ApiJsonResponse::success('Availability retrieved', [
                'dates' => $slots->publicCalendar((int) $restaurant->id, $data['start'], $data['end']),
            ]);
        }

        $year = (int) ($data['year'] ?? date('Y'));
        $month = (int) ($data['month'] ?? date('n'));

        return ApiJsonResponse::success('Availability retrieved', [
            'dates' => $slots->monthAvailability((int) $restaurant->id, $year, $month),
        ]);
    }

    public function store(Request $request, ReservationBookingService $booking)
    {
        $data = $request->validate([
            'restaurant_id' => 'required|integer',
            'guest_name' => 'required|string|max:255',
            'guest_email' => 'required|email',
            'guest_phone' => 'required|string|max:50',
            'reservation_date' => 'required|date',
            'reservation_time' => 'required|string',
            'party_size' => 'required|integer|min:1|max:50',
            'special_occasion' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:1000',
        ]);

        $result = $booking->create((int) $data['restaurant_id'], $data);
        if (! $result['success']) {
            return ApiJsonResponse::error(implode(' ', $result['errors'] ?? ['Booking failed']), null, 422);
        }

        return ApiJsonResponse::success($result['message'] ?? 'Reservation created', [
            'checkout_url' => $result['checkout_url'] ?? null,
        ]);
    }

    public function show(TableReservation $reservation)
    {
        $this->authorizeRestaurant($reservation);

        return ApiJsonResponse::success('Reservation details', $reservation);
    }

    public function updateStatus(Request $request, TableReservation $reservation)
    {
        $this->authorizeRestaurant($reservation);
        $status = $request->validate(['status' => 'required|string|max:50'])['status'];
        $reservation->update(['status' => $status]);

        try {
            app(\App\Services\RestaurantTransactionalMailService::class)
                ->sendReservationStatusChange($reservation->id, (int) $reservation->restaurant_id, $status);
        } catch (\Throwable $e) {
            report($e);
        }

        return ApiJsonResponse::success('Status updated', ['id' => $reservation->id, 'status' => $status]);
    }

    public function analytics(Request $request)
    {
        $restaurantId = (int) $request->attributes->get('restaurant_id');
        $count = TableReservation::where('restaurant_id', $restaurantId)->count();

        return ApiJsonResponse::success('Reservations analytics', ['total' => $count]);
    }

    public function updateDeposit(Request $request)
    {
        $restaurantId = (int) $request->attributes->get('restaurant_id');
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

        return ApiJsonResponse::success('Deposit updated', ['deposit_amount' => $amount]);
    }

    public function tableInventory(Request $request, TableInventoryService $inventory)
    {
        $restaurantId = (int) $request->attributes->get('restaurant_id');
        $action = trim((string) $request->input('action', $request->query('action', 'month')));

        if ($request->isMethod('post')) {
            return $this->tableInventoryPost($request, $inventory, $restaurantId, $action);
        }

        return $this->tableInventoryGet($request, $inventory, $restaurantId, $action);
    }

    private function tableInventoryGet(Request $request, TableInventoryService $inventory, int $restaurantId, string $action): \Illuminate\Http\JsonResponse
    {
        if ($action === 'availability') {
            $date = $request->validate(['date' => 'required|date_format:Y-m-d'])['date'];

            return ApiJsonResponse::success('Availability', [
                'availability' => $inventory->availabilityForDate($restaurantId, $date),
            ]);
        }

        if ($action === 'day_detail') {
            $date = $request->validate(['date' => 'required|date_format:Y-m-d'])['date'];
            $detail = $inventory->dayDetail($restaurantId, $date);

            return ApiJsonResponse::success('Day detail', $detail);
        }

        if ($action === 'month') {
            $year = (int) $request->query('year', date('Y'));
            $month = (int) $request->query('month', date('n'));
            $start = sprintf('%04d-%02d-01', $year, $month);
            $end = date('Y-m-t', strtotime($start));

            return ApiJsonResponse::success('Month inventory', [
                'dates' => $inventory->dateRange($restaurantId, $start, $end),
            ]);
        }

        $year = (int) $request->query('year', date('Y'));
        $month = (int) $request->query('month', date('n'));

        return ApiJsonResponse::success('Month inventory', [
            'dates' => $inventory->monthSummary($restaurantId, $year, $month),
        ]);
    }

    private function tableInventoryPost(Request $request, TableInventoryService $inventory, int $restaurantId, string $action): \Illuminate\Http\JsonResponse
    {
        if ($action === 'set_total' || $action === 'set_day') {
            $data = $request->validate([
                'date' => 'required|date_format:Y-m-d',
                'total_tables' => 'required|integer|min:1|max:999',
            ]);
            $inventory->setDailyTotal($restaurantId, $data['date'], (int) $data['total_tables']);

            return ApiJsonResponse::success('Day updated', [
                'availability' => $inventory->availabilityForDate($restaurantId, $data['date']),
            ]);
        }

        if ($action === 'bulk_set_total' || $action === 'bulk_set') {
            $data = $request->validate([
                'start_date' => 'required|date_format:Y-m-d',
                'end_date' => 'required|date_format:Y-m-d',
                'total_tables' => 'required|integer|min:1|max:999',
            ]);
            $count = $inventory->bulkSet($restaurantId, $data['start_date'], $data['end_date'], (int) $data['total_tables']);

            return ApiJsonResponse::success('Bulk updated', ['updated_count' => $count]);
        }

        if ($action === 'add_walkin') {
            $data = $request->validate([
                'date' => 'required|date_format:Y-m-d',
                'guest_name' => 'nullable|string|max:255',
                'time' => 'nullable|string|max:20',
            ]);
            if (! $inventory->addWalkIn($restaurantId, $data['date'], $data['guest_name'] ?? 'Walk-in', $data['time'] ?? '18:00:00')) {
                return ApiJsonResponse::error('No tables available for this date', null, 422);
            }

            return ApiJsonResponse::success('Walk-in added', [
                'availability' => $inventory->availabilityForDate($restaurantId, $data['date']),
            ]);
        }

        return ApiJsonResponse::error('Invalid action', null, 400);
    }

    private function authorizeRestaurant(TableReservation $reservation): void
    {
        if ((int) $reservation->restaurant_id !== (int) request()->attributes->get('restaurant_id')) {
            abort(403);
        }
    }
}
