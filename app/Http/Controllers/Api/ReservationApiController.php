<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TableReservation;
use App\Services\ReservationBookingService;
use App\Support\ApiJsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservationApiController extends Controller
{
    public function slots(Request $request)
    {
        return ApiJsonResponse::success('Slots retrieved', ['slots' => []]);
    }

    public function availability(Request $request)
    {
        return ApiJsonResponse::success('Availability retrieved', ['dates' => []]);
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

        return ApiJsonResponse::success('Status updated', ['id' => $reservation->id, 'status' => $status]);
    }

    public function analytics(Request $request)
    {
        $restaurantId = (int) $request->attributes->get('restaurant_id');
        $count = TableReservation::where('restaurant_id', $restaurantId)->count();

        return ApiJsonResponse::success('Reservations analytics', ['total' => $count]);
    }

    public function tableInventory(Request $request)
    {
        return ApiJsonResponse::success('Table inventory', []);
    }

    private function authorizeRestaurant(TableReservation $reservation): void
    {
        if ((int) $reservation->restaurant_id !== (int) request()->attributes->get('restaurant_id')) {
            abort(403);
        }
    }
}
