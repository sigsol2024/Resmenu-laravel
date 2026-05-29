<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PaymentGatewayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
  public function index(Request $request)
  {
    $statusFilter = $request->query('status', '');
    $gatewayFilter = $request->query('gateway', '');
    $restaurantFilter = (int) $request->query('restaurant_id', 0);
    $dateFrom = $request->query('date_from', '');
    $dateTo = $request->query('date_to', '');

    $query = DB::table('payments as p')
      ->join('restaurants as r', 'r.id', '=', 'p.restaurant_id')
      ->leftJoin('subscriptions as s', 's.id', '=', 'p.subscription_id')
      ->leftJoin('subscription_plans as sp', 'sp.id', '=', 's.plan_id')
      ->select('p.*', 'r.name as restaurant_name', 'r.slug as restaurant_slug', 'sp.name as plan_name');

    if ($statusFilter !== '') {
      $query->where('p.status', $statusFilter);
    }
    if ($gatewayFilter !== '') {
      $query->where('p.payment_gateway', $gatewayFilter);
    }
    if ($restaurantFilter > 0) {
      $query->where('p.restaurant_id', $restaurantFilter);
    }
    if ($dateFrom !== '') {
      $query->whereDate('p.created_at', '>=', $dateFrom);
    }
    if ($dateTo !== '') {
      $query->whereDate('p.created_at', '<=', $dateTo);
    }

    $payments = $query->orderByDesc('p.created_at')->paginate(20)->withQueryString();

    $totalSuccess = (float) DB::table('payments')->where('status', 'success')->sum('amount');
    $totalPending = (float) DB::table('payments')->where('status', 'pending')->sum('amount');
    $totalCount = (int) DB::table('payments')->count();

    $restaurants = DB::table('restaurants')->orderBy('name')->get(['id', 'name']);

    return view('admin.payments.index', compact(
      'payments',
      'totalSuccess',
      'totalPending',
      'totalCount',
      'restaurants',
      'statusFilter',
      'gatewayFilter',
      'restaurantFilter',
      'dateFrom',
      'dateTo',
    ));
  }

  public function store(Request $request, PaymentGatewayService $payments)
  {
    $action = $request->input('action');

    if ($action === 'update_status') {
      $data = $request->validate([
        'payment_id' => 'required|integer|exists:payments,id',
        'new_status' => 'required|in:pending,success,failed,refunded',
      ]);

      if ($payments->updatePaymentStatus((int) $data['payment_id'], $data['new_status'])) {
        if ($data['new_status'] === 'success') {
          $payments->activateSubscriptionForPayment((int) $data['payment_id']);
        }

        return back()->with('success', 'Payment status updated.');
      }

      return back()->with('error', 'Failed to update payment status.');
    }

    if ($action === 'create_manual') {
      $data = $request->validate([
        'restaurant_id' => 'required|integer|exists:restaurants,id',
        'subscription_id' => 'required|integer|exists:subscriptions,id',
        'amount' => 'required|numeric|min:0.01',
        'status' => 'required|in:pending,success,failed,refunded',
      ]);

      $paymentId = $payments->createPayment([
        'restaurant_id' => (int) $data['restaurant_id'],
        'subscription_id' => (int) $data['subscription_id'],
        'amount' => (float) $data['amount'],
        'payment_gateway' => 'manual',
        'transaction_reference' => 'MANUAL-'.time(),
        'status' => $data['status'],
      ]);

      if ($paymentId && $data['status'] === 'success') {
        $payments->activateSubscriptionForPayment($paymentId);
      }

      return back()->with('success', $paymentId ? 'Manual payment recorded.' : 'Failed to record payment.');
    }

    return back()->with('error', 'Invalid action.');
  }
}
