<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
  public function index(Request $request, SubscriptionService $subscriptions)
  {
    $subscriptions->syncExpiredStatuses();

    $statusFilter = $request->query('status', '');
    $planFilter = (int) $request->query('plan_id', 0);
    $search = trim((string) $request->query('q', ''));

    $query = Subscription::query()->with(['restaurant', 'plan'])->orderByDesc('id');

    if ($statusFilter !== '') {
      $query->where('status', $statusFilter);
    }
    if ($planFilter > 0) {
      $query->where('plan_id', $planFilter);
    }
    if ($search !== '') {
      $query->whereHas('restaurant', fn ($q) => $q->where('name', 'like', "%{$search}%")->orWhere('slug', 'like', "%{$search}%"));
    }

    $statusCounts = Subscription::query()
      ->selectRaw('status, COUNT(*) as count')
      ->groupBy('status')
      ->pluck('count', 'status')
      ->all();

    return view('admin.subscriptions.index', [
      'subscriptions' => $query->paginate(25)->withQueryString(),
      'plans' => SubscriptionPlan::orderBy('display_order')->get(),
      'statusFilter' => $statusFilter,
      'planFilter' => $planFilter,
      'search' => $search,
      'statusCounts' => $statusCounts,
    ]);
  }

  public function update(Request $request, Subscription $subscription, SubscriptionService $service)
  {
    $action = $request->input('action', 'update');

    if ($action === 'update_status') {
      $data = $request->validate([
        'new_status' => 'required|in:trial,active,expired,cancelled,pending',
      ]);

      $subscription->update(['status' => $data['new_status']]);

      if ($data['new_status'] === 'active') {
        $service->activateSubscription($subscription->id, $subscription->billing_cycle ?? 'monthly');
      } elseif ($data['new_status'] === 'cancelled') {
        $service->deactivateSubscription($subscription->id);
      }

      return back()->with('success', 'Subscription status updated.');
    }

    if ($action === 'change_plan') {
      $data = $request->validate([
        'new_plan_id' => 'required|integer|exists:subscription_plans,id',
      ]);

      $subscription->update(['plan_id' => (int) $data['new_plan_id']]);

      return back()->with('success', 'Subscription plan updated.');
    }

    if ($action === 'extend_period') {
      $data = $request->validate([
        'days' => 'required|integer|min:1|max:365',
      ]);
      $days = (int) $data['days'];
      $sub = $subscription->fresh();

      if ($sub->status === 'trial') {
        $base = $sub->trial_ends_at && $sub->trial_ends_at->isFuture() ? $sub->trial_ends_at : now();
        $sub->update(['trial_ends_at' => $base->copy()->addDays($days)]);
      } elseif ($sub->status === 'expired' && $sub->trial_ends_at && ! $sub->current_period_end) {
        $base = $sub->trial_ends_at ?? now();
        $sub->update([
          'status' => 'trial',
          'trial_ends_at' => ($base->isFuture() ? $base : now())->copy()->addDays($days),
        ]);
      } else {
        $base = $sub->current_period_end && $sub->current_period_end->isFuture() ? $sub->current_period_end : now();
        $newEnd = $base->copy()->addDays($days);
        $payload = ['current_period_end' => $newEnd];
        if ($sub->status === 'expired' && $newEnd->isFuture()) {
          $payload['status'] = 'active';
        }
        $sub->update($payload);
      }

      return back()->with('success', "Subscription extended by {$days} days.");
    }

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
