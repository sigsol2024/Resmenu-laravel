<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\Subscription;
use App\Services\PaymentGatewayService;
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

    public function paymentSettings(Request $request)
    {
        $restaurantId = (int) $request->attributes->get('restaurant_id');

        return view('manager.billing.payment-settings', [
            'restaurant' => Restaurant::findOrFail($restaurantId),
        ]);
    }
}
