<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\TableReservation;
use App\Services\CustomizationService;
use App\Support\ReservationConfirmationAccess;
use App\Support\ReservationConfirmationToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservationConfirmationController extends Controller
{
    public function __construct(private CustomizationService $customization) {}

    public function show(Request $request, TableReservation $reservation)
    {
        $reservation->load('restaurant');
        $restaurant = $reservation->restaurant;
        abort_unless($restaurant instanceof \App\Models\Restaurant, 404);

        $reservationId = (int) $reservation->id;
        if (ReservationConfirmationToken::verify($request->query(), $reservationId)) {
            ReservationConfirmationAccess::grant($reservationId);
        } elseif (! ReservationConfirmationAccess::granted($reservationId)) {
            abort(404);
        }

        $custom = $this->customization->forRestaurant($restaurant);
        $state = self::stateFor($reservation, self::depositPaymentInFlight($reservationId));

        return view('public.reservation-confirmation', [
            'reservation' => $reservation,
            'restaurant' => $restaurant,
            'primaryColor' => $custom['primary_color'] ?? '#f20d0d',
            'state' => $state,
            'payDepositUrl' => $state === 'deposit_due'
                ? route('public.checkout', ['slug' => $restaurant->slug, 'reservation_id' => $reservationId])
                : null,
        ]);
    }

    /** @return 'closed'|'awaiting_payment'|'deposit_due'|'received' */
    public static function stateFor(TableReservation $reservation, bool $paymentInFlight = true): string
    {
        if (in_array((string) $reservation->status, ['rejected', 'cancelled'], true)) {
            return 'closed';
        }

        if ((float) ($reservation->deposit_amount ?? 0) > 0 && ! $reservation->deposit_paid) {
            return $paymentInFlight ? 'awaiting_payment' : 'deposit_due';
        }

        return 'received';
    }

    /** A gateway checkout started recently, or a bank transfer the restaurant has yet to review. */
    private static function depositPaymentInFlight(int $reservationId): bool
    {
        try {
            $bankTransfer = DB::table('pending_bank_transfers')
                ->where('reservation_id', $reservationId)
                ->where(function ($q) {
                    $q->where('status', 'customer_claimed')
                        ->orWhere(fn ($p) => $p->where('status', 'pending')->where('created_at', '>=', now()->subMinutes(15)));
                })
                ->exists();

            return $bankTransfer || DB::table('pending_online_payments')
                ->where('reservation_id', $reservationId)
                ->where('payment_type', 'reservation')
                ->where('created_at', '>=', now()->subMinutes(30))
                ->exists();
        } catch (\Throwable $e) {
            report($e);

            return true;
        }
    }
}
