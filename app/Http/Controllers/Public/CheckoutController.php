<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\TableReservation;
use App\Services\CustomizationService;
use App\Services\OrderSubmissionService;
use App\Services\RestaurantPaymentService;
use App\Services\SubscriptionService;
use App\Support\OrderConfirmationToken;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __construct(
        private SubscriptionService $subscriptions,
        private CustomizationService $customization,
        private RestaurantPaymentService $payments,
        private OrderSubmissionService $orders,
    ) {}

    public function show(Request $request, string $slug)
    {
        $restaurant = Restaurant::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $access = $this->subscriptions->checkAccess($restaurant->id);
        if (! $access['valid']) {
            return view('public.subscription-blocked', [
                'restaurant' => $restaurant,
                'access' => $access,
                'context' => 'Checkout',
            ]);
        }

        $reservation = null;
        $reservationId = (int) $request->query('reservation_id', 0);
        if ($reservationId > 0) {
            $reservation = TableReservation::query()
                ->where('id', $reservationId)
                ->where('restaurant_id', $restaurant->id)
                ->whereIn('status', ['pending', 'confirmed'])
                ->where('deposit_amount', '>', 0)
                ->where('deposit_paid', false)
                ->first();
        }

        $custom = $this->customization->forRestaurant($restaurant);

        $uploadBase = rtrim(config('resmenu.canonical_upload_url') ?: config('resmenu.upload_url'), '/');

        return view('public.checkout', [
            'restaurant' => $restaurant,
            'customization' => $custom,
            'paymentMethods' => $this->payments->activeMethods($restaurant->id),
            'reservation' => $reservation,
            'isReservationCheckout' => $reservation !== null,
            'cartJson' => $request->query('cart', '[]'),
            'primaryColor' => $custom['primary_color'] ?? '#f20d0d',
            'menuUrl' => route('public.menu', $restaurant->slug),
            'reservationUrl' => route('public.reservation', $restaurant->slug),
            'uploadBaseUrl' => $uploadBase,
            'currencySymbol' => '₦',
            'deliveryFee' => (float) ($custom['delivery_fee'] ?? 0),
            'taxRate' => (float) ($custom['tax_rate'] ?? 0),
        ]);
    }

    public function submit(Request $request, string $slug)
    {
        $restaurant = Restaurant::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $access = $this->subscriptions->checkAccess($restaurant->id);
        if (! $access['valid']) {
            return redirect()->route('public.menu', $slug)->withErrors(['checkout' => $access['message'] ?? 'Subscription required.']);
        }

        $reservationId = (int) $request->input('reservation_id', 0);
        if ($reservationId > 0) {
            return $this->submitReservationDeposit($request, $restaurant, $reservationId);
        }

        $cart = json_decode($request->input('cart_json', '[]'), true) ?: [];
        $customer = $request->only(['customer_name', 'customer_phone', 'customer_email', 'delivery_address', 'payment_method']);
        $method = $customer['payment_method'] ?? 'cash';

        if (in_array($method, ['paystack', 'flutterwave'], true)) {
            $payment = $this->payments->initiateOrderPayment($restaurant, $cart, $customer, $method);
            if (! $payment['success']) {
                return back()->withErrors(['checkout' => implode(' ', $payment['errors'] ?? [])])->withInput();
            }

            return redirect()->away($payment['redirect_url']);
        }

        $result = $this->orders->createFromCart($restaurant->id, $cart, $customer);
        if (! $result['success']) {
            return back()->withErrors(['checkout' => implode(' ', $result['errors'])])->withInput();
        }

        $url = OrderConfirmationToken::confirmationUrl((int) $result['order_id'], $slug);
        if ($url === '') {
            abort(503, 'Order confirmation is temporarily unavailable.');
        }

        return redirect()->to($url);
    }

    private function submitReservationDeposit(Request $request, Restaurant $restaurant, int $reservationId)
    {
        $reservation = TableReservation::query()
            ->where('id', $reservationId)
            ->where('restaurant_id', $restaurant->id)
            ->firstOrFail();

        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:50',
            'customer_email' => 'required|email',
            'payment_method' => 'required|string',
        ]);

        $payment = $this->payments->initiateReservationDeposit($restaurant, $reservation, $request->all());
        if (! empty($payment['redirect_url'])) {
            return redirect()->away($payment['redirect_url']);
        }

        return redirect()->route('public.reservation', $restaurant->slug)
            ->with('success', 'Deposit payment initiated.');
    }
}
