<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\BankTransferService;
use App\Support\ApiJsonResponse;
use Illuminate\Http\Request;

class BankTransferApiController extends Controller
{
    public function confirm(Request $request, BankTransferService $bankTransfer)
    {
        $token = trim((string) ($request->input('token') ?? $request->post('token', '')));
        $result = $bankTransfer->confirm($token);

        if (! ($result['success'] ?? false)) {
            return ApiJsonResponse::error($result['message'] ?? 'Unable to confirm payment', null, 400);
        }

        return ApiJsonResponse::success('Payment confirmed', ['redirect' => $result['redirect'] ?? null]);
    }

    public function expire(Request $request, BankTransferService $bankTransfer)
    {
        $token = trim((string) $request->input('token', ''));
        if ($token === '') {
            return ApiJsonResponse::error('Invalid token', null, 400);
        }

        $bankTransfer->expireDraft($token);

        return ApiJsonResponse::success('Draft expired');
    }

    public function cancelOrder(Request $request, BankTransferService $bankTransfer)
    {
        $token = trim((string) $request->input('token', ''));
        if ($token === '') {
            return ApiJsonResponse::error('Invalid token', null, 400);
        }

        $bankTransfer->cancelOrderDraft($token);

        return ApiJsonResponse::success('Order draft cancelled');
    }
}
