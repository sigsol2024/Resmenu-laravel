<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index(SubscriptionService $subscriptions)
    {
        $subscriptions->syncExpiredStatuses();

        return view('admin.subscriptions.index', [
            'subscriptions' => Subscription::query()->with(['restaurant', 'plan'])->orderByDesc('id')->paginate(25),
            'plans' => SubscriptionPlan::orderBy('display_order')->get(),
        ]);
    }

    public function update(Request $request, Subscription $subscription, SubscriptionService $service)
    {
        $data = $request->validate([
            'status' => 'required|in:trial,active,expired,cancelled,pending',
            'plan_id' => 'required|integer|exists:subscription_plans,id',
            'billing_cycle' => 'nullable|in:monthly,annual',
        ]);

        $subscription->update([
            'status' => $data['status'],
            'plan_id' => $data['plan_id'],
            'billing_cycle' => $data['billing_cycle'] ?? $subscription->billing_cycle ?? 'monthly',
        ]);

        if ($data['status'] === 'active') {
            $service->activateSubscription($subscription->id, $data['billing_cycle'] ?? 'monthly');
        } elseif ($data['status'] === 'cancelled') {
            $service->deactivateSubscription($subscription->id);
        }

        return back()->with('success', 'Subscription updated.');
    }
}
