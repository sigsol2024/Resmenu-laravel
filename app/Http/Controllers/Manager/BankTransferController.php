<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogService;
use App\Services\BankTransferService;
use Illuminate\Http\Request;

class BankTransferController extends Controller
{
    public function index(Request $request, BankTransferService $bankTransfers)
    {
        $restaurantId = (int) $request->attributes->get('restaurant_id');
        $drafts = $bankTransfers->listPendingForRestaurant($restaurantId);

        return view('manager.bank-transfers.index', [
            'drafts' => $drafts,
        ]);
    }

    public function approve(Request $request, int $draft, BankTransferService $bankTransfers, ActivityLogService $activityLog)
    {
        $restaurantId = (int) $request->attributes->get('restaurant_id');
        $managerId = (int) $request->user('manager')?->id;

        $result = $bankTransfers->managerApprove($draft, $managerId, $restaurantId);

        if ($result['success'] ?? false) {
            $activityLog->record('manager', $managerId, 'bank_transfer.approved', $restaurantId, 'pending_bank_transfer', $draft, null, null, $request->ip(), $request->userAgent());
        }

        return redirect()->route('manager.bank-transfers.index')->with(
            ($result['success'] ?? false) ? 'success' : 'error',
            $result['message'] ?? 'Unable to approve transfer.',
        );
    }

    public function reject(Request $request, int $draft, BankTransferService $bankTransfers, ActivityLogService $activityLog)
    {
        $restaurantId = (int) $request->attributes->get('restaurant_id');
        $managerId = (int) $request->user('manager')?->id;

        $rejected = $bankTransfers->managerReject($draft, $managerId, $restaurantId);

        if ($rejected) {
            $activityLog->record('manager', $managerId, 'bank_transfer.rejected', $restaurantId, 'pending_bank_transfer', $draft, null, null, $request->ip(), $request->userAgent());
        }

        return redirect()->route('manager.bank-transfers.index')->with(
            $rejected ? 'success' : 'error',
            $rejected ? 'Transfer rejected.' : 'Unable to reject transfer.',
        );
    }
}
