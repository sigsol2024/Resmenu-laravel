<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\TableReservation;
use App\Services\CustomizationService;
use App\Support\ReservationConfirmationToken;
use Illuminate\Http\Request;

class ReservationConfirmationController extends Controller
{
    public function __construct(private CustomizationService $customization) {}

    public function show(Request $request, TableReservation $reservation)
    {
        $reservation->load('restaurant');
        $restaurant = $reservation->restaurant;
        abort_unless($restaurant instanceof \App\Models\Restaurant, 404);

        if (! ReservationConfirmationToken::verify($request->query(), (int) $reservation->id)) {
            abort(404);
        }

        $custom = $this->customization->forRestaurant($restaurant);

        return view('public.reservation-confirmation', [
            'reservation' => $reservation,
            'restaurant' => $restaurant,
            'primaryColor' => $custom['primary_color'] ?? '#f20d0d',
        ]);
    }
}
