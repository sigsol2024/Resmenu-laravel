<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BankTransferPendingController extends Controller
{
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

    $restaurant = DB::table('restaurants')->where('id', $draft->restaurant_id)->first();

    return view('public.bank-transfer-pending', compact('draft', 'restaurant', 'token'));
  }
}
