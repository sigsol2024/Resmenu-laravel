<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\TableReservation;
use Illuminate\Http\Request;

class ReservationConfirmationController extends Controller
{
  public function show(Request $request, TableReservation $reservation)
  {
    $reservation->load('restaurant');

    return view('public.reservation-confirmation', [
      'reservation' => $reservation,
      'restaurant' => $reservation->restaurant,
    ]);
  }
}
