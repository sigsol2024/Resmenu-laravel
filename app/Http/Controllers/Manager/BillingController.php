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

        return view('manager.billing.index', [
            'restaurant' => $restaurant,
            'subscription' => Subscription::query()
                ->where('restaurant_id', $restaurantId)
                ->orderByDesc('created_at')
                ->with('plan')
                ->first(),
            'access' => $this->subscriptions->checkAccess($restaurantId),
            'plans' => $this->subscriptions->getPlans(true),
        ]);
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

        return view('manager.billing.payment-settings', [
            'restaurant' => Restaurant::findOrFail($restaurantId),
            'settings' => $payments->allForRestaurant($restaurantId),
            'showUpgradeOverlay' => ! $hasAccess,
            'webhookBase' => url('/api/webhooks/restaurant'),
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

        return back()->with('success', ucfirst(str_replace('_', ' ', $gateway)).' settings saved.');
    }
}
