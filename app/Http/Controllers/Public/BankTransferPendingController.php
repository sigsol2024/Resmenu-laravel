<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\TableReservation;
use App\Services\CustomizationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BankTransferPendingController extends Controller
{
    public function __construct(private CustomizationService $customization) {}

    public function show(Request $request)
    {
        $token = trim((string) $request->query('token', ''));
        if ($token === '') {
            return redirect()->route('login');
        }

        $draft = DB::table('pending_bank_transfers')->where('token', $token)->first();
        if (! $draft) {
            return redirect()->route('login');
        }

        $restaurant = Restaurant::find((int) $draft->restaurant_id);
        if (! $restaurant) {
            return redirect()->route('login');
        }

        $bankTransferMethod = DB::table('restaurant_payment_settings')
            ->where('restaurant_id', $restaurant->id)
            ->where('gateway', 'bank_transfer')
            ->where('is_active', 1)
            ->first();

        if (! $bankTransferMethod || empty($bankTransferMethod->account_number)) {
            return redirect()->route('public.menu', $restaurant->slug);
        }

        $reservation = null;
        if (($draft->payment_type ?? 'order') === 'reservation' && ! empty($draft->reservation_id)) {
            $reservation = TableReservation::query()
                ->where('id', (int) $draft->reservation_id)
                ->where('restaurant_id', $restaurant->id)
                ->first();
        }

        $custom = $this->customization->forRestaurant($restaurant);

        return view('public.bank-transfer-pending', [
            'draft' => $draft,
            'restaurant' => $restaurant,
            'token' => $token,
            'bankTransferMethod' => $bankTransferMethod,
            'reservation' => $reservation,
            'primaryColor' => $custom['primary_color'] ?? '#f20d0d',
        ]);
    }
}
