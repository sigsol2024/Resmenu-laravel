<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentFailedController extends Controller
{
  public function show(Request $request)
  {
    return view('public.payment-failed', [
      'message' => $request->query('message', 'Payment could not be completed.'),
    ]);
  }
}
