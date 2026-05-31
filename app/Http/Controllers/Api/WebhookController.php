<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PaymentGatewayService;
use App\Services\PendingOnlinePaymentService;
use App\Services\RestaurantPaymentVerificationService;
use App\Support\ApiJsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function paystack(Request $request, PaymentGatewayService $gateway)
    {
        $payload = $request->getContent();
        $signature = $request->header('x-paystack-signature', '');
        if (! $gateway->verifyPaystackSignature($payload, $signature)) {
            return ApiJsonResponse::error('Invalid signature', null, 401);
        }

        $event = $request->input('event');
        if ($event === 'charge.success') {
            $gateway->processPlatformPaystackSuccess($request->input('data', []));
        }

        return ApiJsonResponse::success('OK');
    }

    public function flutterwave(Request $request, PaymentGatewayService $gateway)
    {
        $signature = $request->header('verif-hash', '');
        if (! $gateway->verifyFlutterwaveSignature($signature)) {
            return ApiJsonResponse::error('Invalid signature', null, 401);
        }

        if ($request->input('event') === 'charge.completed') {
            $gateway->processPlatformFlutterwaveSuccess($request->input('data', []));
        }

        return ApiJsonResponse::success('OK');
    }

    public function restaurantPaystack(
        Request $request,
        PaymentGatewayService $gateway,
        PendingOnlinePaymentService $pending,
        RestaurantPaymentVerificationService $verification,
    ) {
        $payload = $request->getContent();
        $signature = $request->header('x-paystack-signature', '');
        $reference = $request->input('data.reference', '');
        $restaurantId = (int) ($request->input('data.metadata.restaurant_id') ?? 0);

        if ($restaurantId === 0 && $reference !== '') {
            $restaurantId = (int) DB::table('pending_online_payments')->where('reference', $reference)->value('restaurant_id');
        }

        if ($restaurantId <= 0) {
            return ApiJsonResponse::error('Unknown restaurant', null, 400);
        }

        if (! $gateway->verifyRestaurantPaystackSignature($payload, $signature, $restaurantId)) {
            return ApiJsonResponse::error('Invalid signature', null, 401);
        }

        if ($request->input('event') === 'charge.success' && $reference !== '') {
            $verified = $verification->verifyWebhookPayment($reference, 'paystack', $request->input('data', []));
            if ($verified['ok'] ?? false) {
                $pending->fulfillFromWebhook($reference, 'paystack');
            } else {
                Log::warning('Restaurant Paystack webhook verification failed', [
                    'reference' => $reference,
                    'reason' => $verified['error'] ?? 'unknown',
                ]);
            }
        } elseif ($request->input('event') === 'charge.failed' && $reference !== '') {
            $pending->discardFailed($reference, 'paystack');
        }

        return ApiJsonResponse::success('OK');
    }

    public function restaurantFlutterwave(
        Request $request,
        PaymentGatewayService $gateway,
        PendingOnlinePaymentService $pending,
        RestaurantPaymentVerificationService $verification,
    ) {
        $signature = $request->header('verif-hash', '');
        $reference = $request->input('data.tx_ref', $request->input('data.reference', ''));
        $restaurantId = (int) ($request->input('data.meta.restaurant_id') ?? 0);

        if ($restaurantId === 0 && $reference !== '') {
            $restaurantId = (int) DB::table('pending_online_payments')->where('reference', $reference)->value('restaurant_id');
        }

        if ($restaurantId <= 0) {
            return ApiJsonResponse::error('Unknown restaurant', null, 400);
        }

        if (! $gateway->verifyRestaurantFlutterwaveSignature($signature, $restaurantId)) {
            return ApiJsonResponse::error('Invalid signature', null, 401);
        }

        if (in_array($request->input('event'), ['charge.completed', 'complete'], true) && $reference !== '') {
            $verified = $verification->verifyWebhookPayment($reference, 'flutterwave', $request->input('data', []));
            if ($verified['ok'] ?? false) {
                $pending->fulfillFromWebhook($reference, 'flutterwave');
            } else {
                Log::warning('Restaurant Flutterwave webhook verification failed', [
                    'reference' => $reference,
                    'reason' => $verified['error'] ?? 'unknown',
                ]);
            }
        }

        return ApiJsonResponse::success('OK');
    }

    public function emailSuppression(Request $request, \App\Services\EmailSuppressionService $suppression)
    {
        $secret = config('resmenu.reg_otp_bounce_webhook_secret');
        if ($secret === '' || ! hash_equals($secret, (string) $request->header('X-Webhook-Secret', ''))) {
            Log::warning('Rejected email suppression webhook: missing or invalid secret');

            return ApiJsonResponse::error('Unauthorized', null, 401);
        }

        $email = (string) ($request->input('email') ?? $request->input('data.email', ''));
        $reason = (string) ($request->input('reason') ?? $request->input('data.reason', 'hard_bounce'));

        if ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $suppression->addSuppression($email, $reason, 'webhook');
            Log::info('Email suppression added', ['email_hash' => hash('sha256', strtolower(trim($email))), 'reason' => $reason]);
        } else {
            Log::warning('Email suppression webhook missing valid email');
        }

        return ApiJsonResponse::success('OK');
    }
}
