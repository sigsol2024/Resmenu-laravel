<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index()
    {
        return view('admin.payments.index', [
            'transactions' => DB::table('subscription_transactions')
                ->orderByDesc('created_at')
                ->limit(200)
                ->get(),
        ]);
    }
}
