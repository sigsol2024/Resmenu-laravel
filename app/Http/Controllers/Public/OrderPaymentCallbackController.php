<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Services\PendingOnlinePaymentService;
use App\Services\RestaurantPaymentVerificationService;
use App\Support\OrderConfirmationToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderPaymentCallbackController extends Controller
{
    public function __invoke(
        Request $request,
        RestaurantPaymentVerificationService $verification,
        PendingOnlinePaymentService $pending,
        string $gateway,
    ) {
        $reference = $request->query('reference', $request->query('trxref', $request->query('tx_ref', '')));
        $slug = preg_replace('/[^a-z0-9-]/', '', strtolower((string) $request->query('slug', '')));
        $transactionId = $request->query('transaction_id', $request->query('id'));

        if ($reference === '') {
            return $this->redirectMenu($slug, 'Missing payment reference.');
        }

        $verified = $verification->verifyCallbackPayment($reference, $gateway, $transactionId);
        if (! ($verified['ok'] ?? false)) {
            $error = $verified['error'] ?? 'verification_failed';
            if (($verified['already_fulfilled'] ?? false) === true) {
                return $this->redirectAfterFulfillment($verified, $slug);
            }

            Log::warning('Order payment callback rejected', [
                'reference' => $reference,
                'gateway' => $gateway,
                'reason' => $error,
            ]);

            return $this->redirectMenu($slug, 'Payment could not be confirmed. Contact the restaurant if you were charged.');
        }

        if (($verified['already_fulfilled'] ?? false) === true) {
            return $this->redirectAfterFulfillment($verified, $slug);
        }

        $result = $pending->fulfillFromWebhook($reference, $gateway);
        if (! empty($result['order_id'])) {
            Log::info('Order fulfilled via verified payment callback', [
                'reference' => $reference,
                'gateway' => $gateway,
                'order_id' => $result['order_id'],
            ]);

            return $this->redirectOrderConfirmation((int) $result['order_id'], $result['slug'] ?: $slug);
        }

        if (($result['type'] ?? '') === 'reservation') {
            return redirect()->route('public.menu', $result['slug'] ?: $slug)
                ->with('success', 'Reservation deposit paid successfully.');
        }

        if (! empty($result['already_processed'])) {
            $cached = $verification->verifyCallbackPayment($reference, $gateway, $transactionId);
            if (($cached['already_fulfilled'] ?? false) === true) {
                return $this->redirectAfterFulfillment($cached, $slug);
            }

            return $this->redirectMenu($slug, null, 'Payment already processed.');
        }

        if (! ($result['success'] ?? true)) {
            Log::warning('Payment callback verified but fulfillment failed', [
                'reference' => $reference,
                'gateway' => $gateway,
                'errors' => $result['errors'] ?? [],
            ]);
        }

        return $this->redirectMenu($slug, 'Payment is being confirmed. Refresh shortly or contact the restaurant if you were charged.');
    }

    /** @param  array<string, mixed>  $verified */
    private function redirectAfterFulfillment(array $verified, string $slug): \Illuminate\Http\RedirectResponse
    {
        if (! empty($verified['order_id'])) {
            return $this->redirectOrderConfirmation((int) $verified['order_id'], (string) ($verified['slug'] ?? $slug));
        }

        if (($verified['type'] ?? '') === 'reservation') {
            return redirect()->route('public.menu', $verified['slug'] ?? $slug)
                ->with('success', 'Reservation deposit paid successfully.');
        }

        return $this->redirectMenu($slug, null, 'Payment already processed.');
    }

    private function redirectOrderConfirmation(int $orderId, string $slug): \Illuminate\Http\RedirectResponse
    {
        $url = OrderConfirmationToken::confirmationUrl($orderId, $slug);
        if ($url !== '') {
            return redirect()->to($url);
        }

        abort(404);
    }

    private function redirectMenu(string $slug, ?string $error = null, ?string $success = null): \Illuminate\Http\RedirectResponse
    {
        $route = $slug !== '' ? route('public.menu', $slug) : route('login');
        $redirect = redirect()->to($route);
        if ($error !== null) {
            $redirect->withErrors(['payment' => $error]);
        }
        if ($success !== null) {
            $redirect->with('success', $success);
        }

        return $redirect;
    }
}
