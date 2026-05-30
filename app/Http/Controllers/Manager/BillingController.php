<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\Subscription;
use App\Services\ManagerFeatureAccess;
use App\Services\PaymentGatewayService;
use App\Services\RestaurantPaymentSettingsService;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class BillingController extends Controller
{
    public function __construct(private SubscriptionService $subscriptions) {}

    public function index(Request $request)
    {
        $restaurantId = (int) $request->attributes->get('restaurant_id');
        $restaurant = Restaurant::findOrFail($restaurantId);

        if ($request->isMethod('post')) {
            return $this->handleBillingPost($request, $restaurantId);
        }

        if ($request->query('payment_success') === '1') {
            $reference = $request->query('reference', '');
            if ($reference !== '') {
                $payment = DB::table('payments')->where('transaction_reference', $reference)->first();
                if ($payment && $payment->status === 'success') {
                    session()->flash('success', 'Payment successful! Your subscription is now active.');
                } else {
                    session()->flash('info', 'Payment is being processed. Please refresh in a moment.');
                }
            } else {
                session()->flash('success', 'Payment successful! Your subscription is now active.');
            }

            return redirect()->route('manager.billing.index');
        }

        if ($request->query('payment_error') === '1') {
            session()->flash('error', $request->query('message', 'Payment processing error occurred.'));

            return redirect()->route('manager.billing.index');
        }

        if ($request->query('welcome') === '1') {
            session()->flash('success', 'Welcome! Your 7-day Professional trial is active. Choose a paid plan anytime from this page.');
        }

        if ($request->query('upgrade_required') === '1') {
            session()->flash('error', 'Your trial or subscription is no longer active. Please choose a plan to continue using manager features.');
        }

        $subscription = $this->subscriptions->getRestaurantSubscription($restaurantId);
        $subscriptionId = (int) ($subscription['id'] ?? 0);

        return view('manager.billing.index', [
            'restaurant' => $restaurant,
            'subscription' => $subscription,
            'statusInfo' => $this->subscriptions->getSubscriptionStatusInfo($subscription),
            'plans' => $this->subscriptions->getPlans(true),
            'usage' => $this->subscriptions->getUsageSummary($restaurantId),
            'paymentHistory' => $this->subscriptions->getPaymentHistory($restaurantId, 3),
            'scheduledChange' => $subscriptionId > 0
                ? $this->subscriptions->getScheduledSubscriptionChange($subscriptionId)
                : null,
            'formatPrice' => fn ($amount, $currency = 'NGN') => $this->subscriptions->formatSubscriptionPrice($amount, $currency),
            'trialDaysLeft' => $this->subscriptions->getTrialDaysRemaining($subscription),
        ]);
    }

    private function handleBillingPost(Request $request, int $restaurantId)
    {
        $action = $request->input('action', '');
        $subscription = $this->subscriptions->getRestaurantSubscription($restaurantId);

        if (! $subscription) {
            return redirect()->route('manager.billing.index')->with('error', 'No active subscription found to update.');
        }

        if ($action === 'schedule_change') {
            $targetPlanId = (int) $request->input('target_plan_id', 0);
            $targetCycle = strtolower(trim((string) $request->input('target_cycle', 'monthly'))) === 'annual'
                ? 'annual'
                : 'monthly';
            $targetPlan = $this->subscriptions->getPlanById($targetPlanId);

            if (! $targetPlan) {
                return redirect()->route('manager.billing.index')->with('error', 'Selected plan could not be found.');
            }

            $decision = $this->subscriptions->getSubscriptionChangeDecision($subscription, $targetPlan, $targetCycle);

            if ($decision['mode'] === 'immediate') {
                return redirect()->route('manager.billing.checkout', [
                    'plan' => $targetPlan['slug'],
                    'cycle' => $targetCycle,
                ]);
            }

            if ($decision['mode'] === 'none') {
                return redirect()->route('manager.billing.index')->with('info', 'You are already on that plan and billing cycle.');
            }

            $effectiveAt = $subscription['current_period_end']
                ?? $subscription['trial_ends_at']
                ?? now()->toDateTimeString();

            $scheduled = $this->subscriptions->createOrUpdateScheduledSubscriptionChange(
                $restaurantId,
                (int) $subscription['id'],
                (int) $targetPlan['id'],
                $targetCycle,
                $effectiveAt,
                $decision['type'],
            );

            return redirect()->route('manager.billing.index')->with(
                $scheduled ? 'success' : 'error',
                $scheduled
                    ? 'Plan change scheduled successfully. It will take effect at the end of your current billing period.'
                    : 'Unable to schedule the change. Please try again.',
            );
        }

        if ($action === 'cancel_scheduled_change') {
            $cancelled = $this->subscriptions->cancelScheduledSubscriptionChange((int) $subscription['id']);

            return redirect()->route('manager.billing.index')->with(
                $cancelled ? 'success' : 'error',
                $cancelled ? 'Scheduled change cancelled.' : 'Unable to cancel scheduled change.',
            );
        }

        return redirect()->route('manager.billing.index');
    }

    public function checkout(Request $request)
    {
        return view('manager.billing.checkout', [
            'restaurant' => Restaurant::findOrFail((int) $request->attributes->get('restaurant_id')),
            'plans' => $this->subscriptions->getPlans(true),
        ]);
    }

    public function processPayment(Request $request, PaymentGatewayService $gateway)
    {
        $data = $request->validate([
            'plan_id' => 'required|integer',
            'billing_cycle' => 'nullable|in:monthly,annual',
            'gateway' => 'nullable|in:paystack,flutterwave',
        ]);

        $restaurantId = (int) $request->attributes->get('restaurant_id');
        $result = $gateway->initializeSubscriptionPayment(
            $restaurantId,
            (int) $data['plan_id'],
            $data['billing_cycle'] ?? 'monthly',
            $data['gateway'] ?? 'paystack',
        );

        if (! empty($result['redirect_url'])) {
            return redirect()->away($result['redirect_url']);
        }

        return back()->with('error', $result['error'] ?? 'Could not start payment. Check platform gateway settings.');
    }

    public function paymentCallback(Request $request, PaymentGatewayService $gateway)
    {
        $gw = $request->query('gateway', 'paystack');
        $reference = $request->query('reference', $request->query('trxref', $request->query('transaction_id', '')));

        if ($reference !== '') {
            if ($gw === 'paystack') {
                $keys = $gateway->platformKeys('paystack');
                if ($keys['secret_key']) {
                    $verify = Http::withToken($keys['secret_key'])
                        ->get('https://api.paystack.co/transaction/verify/'.urlencode($reference));
                    if ($verify->successful() && $verify->json('data.status') === 'success') {
                        $gateway->processPlatformPaystackSuccess($verify->json('data', []));
                    }
                }
            } else {
                $keys = $gateway->platformKeys('flutterwave');
                if ($keys['secret_key'] && $request->query('transaction_id')) {
                    $verify = Http::withToken($keys['secret_key'])
                        ->get('https://api.flutterwave.com/v3/transactions/'.urlencode($request->query('transaction_id')).'/verify');
                    if ($verify->successful() && $verify->json('data.status') === 'successful') {
                        $gateway->processPlatformFlutterwaveSuccess($verify->json('data', []));
                    }
                }
            }
        }

        return redirect()->route('manager.billing.index')->with('success', 'Payment received. Your subscription is now active.');
    }

    public function transactionHistory(Request $request)
    {
        $restaurantId = (int) $request->attributes->get('restaurant_id');

        return view('manager.billing.transactions', [
            'restaurant' => Restaurant::findOrFail($restaurantId),
            'transactions' => DB::table('payments')
                ->where('restaurant_id', $restaurantId)
                ->orderByDesc('created_at')
                ->limit(100)
                ->get(),
            'formatPrice' => fn ($amount, $currency = 'NGN') => $this->subscriptions->formatSubscriptionPrice($amount, $currency),
        ]);
    }

    public function paymentSettings(
        Request $request,
        RestaurantPaymentSettingsService $payments,
        ManagerFeatureAccess $features,
    ) {
        $restaurantId = (int) $request->attributes->get('restaurant_id');
        $hasAccess = $features->planHasFoodOrdering($restaurantId)
            || $features->planHasTableReservations($restaurantId);

        $upgradePlans = [];
        if (! $hasAccess) {
            foreach ($this->subscriptions->getPlans(true) as $plan) {
                $slug = strtolower((string) ($plan['slug'] ?? ''));
                if (in_array($slug, ['professional', 'enterprise'], true)) {
                    $upgradePlans[] = $plan;
                }
            }
        }

        $allSettings = $payments->allForRestaurant($restaurantId);

        return view('manager.billing.payment-settings', [
            'restaurant' => Restaurant::findOrFail($restaurantId),
            'paystackSettings' => $allSettings['paystack'] ?? null,
            'flutterwaveSettings' => $allSettings['flutterwave'] ?? null,
            'bankTransferSettings' => $allSettings['bank_transfer'] ?? null,
            'showUpgradeOverlay' => ! $hasAccess,
            'upgradePlans' => $upgradePlans,
            'paystackWebhookUrl' => url('/api/webhooks/restaurant/paystack'),
            'flutterwaveWebhookUrl' => url('/api/webhooks/restaurant/flutterwave'),
        ]);
    }

    public function savePaymentSettings(Request $request, RestaurantPaymentSettingsService $payments)
    {
        $restaurantId = (int) $request->attributes->get('restaurant_id');
        $gateway = $request->input('gateway');

        if (! in_array($gateway, ['paystack', 'flutterwave', 'bank_transfer'], true)) {
            return back()->withErrors(['gateway' => 'Invalid gateway.']);
        }

        if ($gateway === 'bank_transfer') {
            $data = $request->validate([
                'is_active' => 'nullable|boolean',
                'bank_name' => 'nullable|string|max:255',
                'account_number' => 'nullable|string|max:100',
                'account_name' => 'nullable|string|max:255',
            ]);
        } else {
            $data = $request->validate([
                'is_active' => 'nullable|boolean',
                'test_mode' => 'nullable|boolean',
                'public_key_test' => 'nullable|string|max:255',
                'secret_key_test' => 'nullable|string',
                'webhook_secret_test' => 'nullable|string|max:255',
                'public_key_live' => 'nullable|string|max:255',
                'secret_key_live' => 'nullable|string',
                'webhook_secret_live' => 'nullable|string|max:255',
            ]);
        }

        $data['is_active'] = $request->boolean('is_active');
        if ($gateway !== 'bank_transfer') {
            $data['test_mode'] = $request->boolean('test_mode');
        }

        $payments->update($restaurantId, $gateway, $data);

        $label = match ($gateway) {
            'paystack' => 'Paystack',
            'flutterwave' => 'Flutterwave',
            default => 'Bank transfer',
        };

        return back()->with('success', "{$label} settings updated successfully!");
    }
}
