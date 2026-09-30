<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Services\PendingOnlinePaymentService;
use App\Services\RestaurantPaymentVerificationService;
use App\Support\OrderConfirmationToken;
use App\Support\ReservationConfirmationAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderPaymentCallbackController extends Controller
{
    /** Gateway reports the charge as not successful (cancelled, failed, or still pending at the bank). */
    private const PAYMENT_NOT_COMPLETED = ['payment_not_successful', 'missing_transaction_id'];

    /** Could not reach a verdict yet; the webhook may still confirm the payment. */
    private const TRANSIENT_ERRORS = ['replay_throttled', 'verify_http_failed'];

    public function __invoke(
        Request $request,
        RestaurantPaymentVerificationService $verification,
        PendingOnlinePaymentService $pending,
        string $gateway,
    ) {
        $gateway = strtolower($gateway);
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

            $cached = $pending->fulfilledPayload($gateway, $reference);
            if ($cached !== null) {
                return $this->redirectAfterFulfillment($cached, $slug);
            }

            Log::warning('Order payment callback rejected', [
                'reference' => $reference,
                'gateway' => $gateway,
                'reason' => $error,
            ]);

            if (in_array($error, self::PAYMENT_NOT_COMPLETED, true)) {
                return $this->redirectReservationRetry($pending, $gateway, $reference, $slug)
                    ?? $this->redirectMenu($slug, 'Payment was not completed. Please try again.');
            }

            if (in_array($error, self::TRANSIENT_ERRORS, true)) {
                $pendingPage = $this->redirectPendingReservation($pending, $gateway, $reference, $slug);
                if ($pendingPage !== null) {
                    return $pendingPage;
                }
            }

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
            return $this->redirectAfterFulfillment($result, $slug);
        }

        if (! empty($result['already_processed'])) {
            $cached = $pending->fulfilledPayload($gateway, $reference);
            if ($cached !== null) {
                return $this->redirectAfterFulfillment($cached, $slug);
            }

            return $this->redirectPendingReservation($pending, $gateway, $reference, $slug)
                ?? $this->redirectMenu($slug, null, 'Payment already processed.');
        }

        if (! ($result['success'] ?? true)) {
            Log::warning('Payment callback verified but fulfillment failed', [
                'reference' => $reference,
                'gateway' => $gateway,
                'errors' => $result['errors'] ?? [],
            ]);
        }

        return $this->redirectPendingReservation($pending, $gateway, $reference, $slug)
            ?? $this->redirectMenu($slug, 'Payment is being confirmed. Refresh shortly or contact the restaurant if you were charged.');
    }

    /** @param  array<string, mixed>  $fulfilled */
    private function redirectAfterFulfillment(array $fulfilled, string $slug): \Illuminate\Http\RedirectResponse
    {
        if (! empty($fulfilled['order_id'])) {
            return $this->redirectOrderConfirmation((int) $fulfilled['order_id'], (string) ($fulfilled['slug'] ?? $slug));
        }

        if (($fulfilled['type'] ?? '') === 'reservation') {
            $reservationSlug = (string) (($fulfilled['slug'] ?? '') ?: $slug);
            if (! empty($fulfilled['reservation_id'])) {
                return redirect()->to(ReservationConfirmationAccess::url((int) $fulfilled['reservation_id'], $reservationSlug));
            }

            $url = (string) ($fulfilled['confirmation_url'] ?? '');
            if ($url !== '') {
                return redirect()->to($url);
            }

            return $this->redirectMenu($reservationSlug, null, "We've received your reservation and our team will get back to you.");
        }

        return $this->redirectMenu($slug, null, 'Payment already processed.');
    }

    /**
     * Payment not (yet) confirmed for a reservation deposit: show the "awaiting confirmation" page, but only
     * to the browser that made the booking, since the reference alone must not reveal guest details.
     */
    private function redirectPendingReservation(
        PendingOnlinePaymentService $pending,
        string $gateway,
        string $reference,
        string $slug,
    ): ?\Illuminate\Http\RedirectResponse {
        $reservationId = $pending->reservationIdForReference($gateway, $reference);
        if ($reservationId === null || ! ReservationConfirmationAccess::granted($reservationId)) {
            return null;
        }

        return redirect()->route('public.reservation.confirmation', ['reservation' => $reservationId]);
    }

    private function redirectReservationRetry(
        PendingOnlinePaymentService $pending,
        string $gateway,
        string $reference,
        string $slug,
    ): ?\Illuminate\Http\RedirectResponse {
        $reservationId = $pending->reservationIdForReference($gateway, $reference);
        if ($reservationId === null || $slug === '') {
            return null;
        }

        return redirect()->route('public.checkout', ['slug' => $slug, 'reservation_id' => $reservationId])
            ->withErrors(['payment' => 'Your deposit payment was not completed. If you did complete it, you will receive a confirmation email shortly; otherwise please try again.']);
    }

    private function redirectOrderConfirmation(int $orderId, string $slug): \Illuminate\Http\RedirectResponse
    {
        $url = OrderConfirmationToken::confirmationUrl($orderId, $slug);
        if ($url !== '') {
            return redirect()->to($url);
        }

        return $this->redirectMenu($slug, null, 'Payment received. Your order has been placed.');
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
