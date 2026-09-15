<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Services\ActivityLogService;
use App\Services\PaymentGatewayService;
use App\Services\SubscriptionPaymentLifecycleService;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
  public function index(Request $request, SubscriptionService $subscriptions)
  {
    $statusFilter = $request->query('status', '');
    $gatewayFilter = $request->query('gateway', '');
    $restaurantFilter = (int) $request->query('restaurant_id', 0);
    $dateFrom = $request->query('date_from', '');
    $dateTo = $request->query('date_to', '');
    $openManual = $request->query('manual') === '1';

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
    $plans = $subscriptions->getPlans(true);

    return view('admin.payments.index', compact(
      'payments',
      'totalSuccess',
      'totalPending',
      'totalCount',
      'restaurants',
      'plans',
      'statusFilter',
      'gatewayFilter',
      'restaurantFilter',
      'dateFrom',
      'dateTo',
      'openManual',
    ));
  }

  public function quote(Request $request, SubscriptionService $subscriptions)
  {
    $data = $request->validate([
      'restaurant_id' => 'required|integer|exists:restaurants,id',
      'plan_id' => 'required|integer|exists:subscription_plans,id',
      'billing_cycle' => 'required|in:monthly,annual',
    ]);

    $quote = $subscriptions->quotePlanChange(
      (int) $data['restaurant_id'],
      (int) $data['plan_id'],
      $data['billing_cycle'],
    );

    $current = $quote['current_subscription'] ?? null;
    $statusInfo = $subscriptions->getSubscriptionStatusInfo(is_array($current) ? $current : null);

    return response()->json([
      'outcome' => $quote['outcome'] ?? null,
      'message' => $quote['message'] ?? null,
      'amount' => $quote['amount'] ?? 0,
      'pricing_mode' => $quote['pricing_mode'] ?? null,
      'remaining_fraction' => $quote['remaining_fraction'] ?? null,
      'list_price' => $quote['list_price'] ?? null,
      'formatted_amount' => $subscriptions->formatSubscriptionPrice((float) ($quote['amount'] ?? 0)),
      'can_record_payment' => ($quote['outcome'] ?? '') === 'charge' && (float) ($quote['amount'] ?? 0) > 0,
      'can_schedule' => ($quote['outcome'] ?? '') === 'schedule_downgrade',
      'current' => $current ? [
        'plan_name' => $current['plan_name'] ?? null,
        'billing_cycle' => $current['billing_cycle'] ?? null,
        'status' => $statusInfo['effective_status'] ?? ($current['status'] ?? null),
        'status_label' => $statusInfo['label'] ?? null,
        'period_end' => ! empty($current['current_period_end'])
          ? \Illuminate\Support\Carbon::parse($current['current_period_end'])->format('M j, Y')
          : null,
      ] : null,
    ]);
  }

  public function store(
    Request $request,
    PaymentGatewayService $payments,
    ActivityLogService $activityLog,
    SubscriptionService $subscriptions,
    SubscriptionPaymentLifecycleService $paymentLifecycle,
  ) {
    $action = $request->input('action');
    $adminId = (int) $request->user('admin')?->id;

    if ($action === 'update_status') {
      $data = $request->validate([
        'payment_id' => 'required|integer|exists:payments,id',
        'new_status' => 'required|in:pending,success,failed,refunded',
        'note' => 'required_if:new_status,success|nullable|string|min:10',
      ]);

      $payment = DB::table('payments')->where('id', $data['payment_id'])->first();
      if (! $payment) {
        return back()->with('error', 'Payment not found.');
      }

      $oldStatus = $payment->status ?? null;

      // Already-success is idempotent: re-fulfill safely (already_on_plan / reconcile) without double mutation.
      if ($data['new_status'] === 'success' && $oldStatus === 'success') {
        $payments->activateSubscriptionForPayment((int) $data['payment_id']);

        return back()->with('success', 'Payment already marked successful.');
      }

      if ($payments->updatePaymentStatus((int) $data['payment_id'], $data['new_status'], [
        'admin_note' => $data['note'] ?? null,
        'updated_by_admin_id' => $adminId,
      ])) {
        if ($data['new_status'] === 'success') {
          $payments->activateSubscriptionForPayment((int) $data['payment_id']);
        }

        $activityLog->record('admin', $adminId, 'payment.status_changed', (int) ($payment->restaurant_id ?? 0), 'payment', (int) $data['payment_id'], ['status' => $oldStatus], ['status' => $data['new_status'], 'note' => $data['note'] ?? null], $request->ip(), $request->userAgent());

        return back()->with('success', 'Payment status updated.');
      }

      return back()->with('error', 'Failed to update payment status.');
    }

    if ($action === 'create_manual') {
      $data = $request->validate([
        'restaurant_id' => 'required|integer|exists:restaurants,id',
        'plan_id' => 'required|integer|exists:subscription_plans,id',
        'billing_cycle' => 'required|in:monthly,annual',
        'status' => 'required|in:pending,success',
      ]);

      $restaurantId = (int) $data['restaurant_id'];
      $planId = (int) $data['plan_id'];
      $cycle = $data['billing_cycle'] === 'annual' ? 'annual' : 'monthly';
      $status = $data['status'];

      $quote = $subscriptions->quotePlanChange($restaurantId, $planId, $cycle);
      $outcome = (string) ($quote['outcome'] ?? '');

      if ($outcome === 'already_on_plan' || $outcome === 'blocked') {
        return back()->with('error', $quote['message'] ?? 'This plan change is not allowed.');
      }

      if ($outcome === 'schedule_downgrade') {
        $scheduled = $subscriptions->applyScheduledDowngradeQuote($quote, 'admin');

        return back()->with(
          $scheduled ? 'success' : 'error',
          $scheduled
            ? ($quote['message'] ?? 'Downgrade scheduled for the end of the current billing period.')
            : 'Unable to schedule the plan change.'
        );
      }

      if ($outcome !== 'charge') {
        return back()->with('error', 'Unable to calculate payment for this plan change.');
      }

      $amount = (float) ($quote['amount'] ?? 0);
      if ($amount <= 0) {
        return back()->with('error', 'Quoted amount must be greater than zero. Free upgrades are not allowed.');
      }

      try {
        $paymentId = DB::transaction(function () use ($restaurantId, $planId, $cycle, $status, $amount, $quote, $payments, $paymentLifecycle) {
          $subscriptionId = (int) ($quote['subscription_id'] ?? 0);
          $subscription = $subscriptionId > 0
            ? Subscription::query()->where('id', $subscriptionId)->where('restaurant_id', $restaurantId)->lockForUpdate()->first()
            : null;

          if (! $subscription) {
            $subscription = Subscription::query()->where('restaurant_id', $restaurantId)->orderByDesc('id')->lockForUpdate()->first();
          }

          if (! $subscription) {
            $subscription = Subscription::forceCreate([
              'restaurant_id' => $restaurantId,
              'plan_id' => $planId,
              'billing_cycle' => $cycle,
              'status' => 'pending',
            ]);
          }

          $billingIntent = $paymentLifecycle->buildBillingIntent(
            $restaurantId,
            (int) $subscription->id,
            $planId,
            $cycle,
            $amount,
            (string) ($quote['pricing_mode'] ?? ''),
            (string) ($quote['apply_mode'] ?? ''),
          );

          return $payments->createPayment([
            'restaurant_id' => $restaurantId,
            'subscription_id' => (int) $subscription->id,
            'plan_id' => $planId,
            'billing_cycle' => $cycle,
            'amount' => $amount,
            'payment_gateway' => 'manual',
            'transaction_reference' => 'MANUAL-'.time().'-'.strtolower(substr(md5(uniqid('', true)), 0, 6)),
            'status' => $status,
            'billing_intent' => $billingIntent,
          ]);
        });
      } catch (\Throwable $e) {
        report($e);

        return back()->with('error', 'Failed to record manual payment.');
      }

      if ($paymentId && $status === 'success') {
        $payments->activateSubscriptionForPayment((int) $paymentId);
      }

      if ($paymentId) {
        $activityLog->record(
          'admin',
          $adminId,
          'payment.manual_created',
          $restaurantId,
          'payment',
          (int) $paymentId,
          null,
          [
            'plan_id' => $planId,
            'billing_cycle' => $cycle,
            'status' => $status,
            'amount' => $amount,
            'pricing_mode' => $quote['pricing_mode'] ?? null,
            'apply_mode' => $quote['apply_mode'] ?? null,
          ],
          $request->ip(),
          $request->userAgent()
        );
      }

      return back()->with('success', $paymentId ? 'Manual payment recorded.' : 'Failed to record payment.');
    }

    return back()->with('error', 'Invalid action.');
  }
}
