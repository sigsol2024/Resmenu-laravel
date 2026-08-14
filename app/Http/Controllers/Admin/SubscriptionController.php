<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\Restaurant;
use App\Services\ActivityLogService;
use App\Services\PlanVisibilityService;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;

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

    $restaurantsWithoutSubscription = Restaurant::query()
      ->whereDoesntHave('subscriptions')
      ->orderBy('name')
      ->get(['id', 'name', 'slug']);

    return view('admin.subscriptions.index', [
      'subscriptions' => $query->paginate(25)->withQueryString(),
      'plans' => SubscriptionPlan::orderBy('display_order')->get(),
      'statusFilter' => $statusFilter,
      'planFilter' => $planFilter,
      'search' => $search,
      'statusCounts' => $statusCounts,
      'restaurantsWithoutSubscription' => $restaurantsWithoutSubscription,
    ]);
  }

  public function store(Request $request, SubscriptionService $service, ActivityLogService $activityLog)
  {
    $data = $request->validate([
      'restaurant_id' => 'required|integer|exists:restaurants,id',
      'plan_id' => 'nullable|integer|exists:subscription_plans,id',
      'include_trial' => 'nullable|boolean',
    ]);

    $restaurantId = (int) $data['restaurant_id'];
    if (Subscription::query()->where('restaurant_id', $restaurantId)->exists()) {
      return back()->with('error', 'This restaurant already has a subscription.');
    }

    $includeTrial = $request->boolean('include_trial', true);
    $subscription = $service->assignSubscriptionForRestaurant(
      $restaurantId,
      ! empty($data['plan_id']) ? (int) $data['plan_id'] : null,
      $includeTrial,
    );

    if (! $subscription) {
      return back()->with('error', 'Could not assign a subscription. Add an active subscription plan first.');
    }

    $activityLog->record(
      'admin',
      (int) $request->user('admin')?->id,
      $includeTrial ? 'subscription.trial_assigned' : 'subscription.assigned',
      $restaurantId,
      'subscription',
      (int) $subscription->id,
      null,
      ['plan_id' => (int) $subscription->plan_id, 'status' => $subscription->status],
      $request->ip(),
      $request->userAgent(),
    );

    $planVisibility = app(PlanVisibilityService::class);
    $planVisibility->forgetCache($restaurantId);

    return back()->with('success', $includeTrial ? '7-day trial assigned.' : 'Active subscription assigned.');
  }

  public function update(Request $request, Subscription $subscription, SubscriptionService $service, ActivityLogService $activityLog, PlanVisibilityService $planVisibility)
  {
    $action = $request->input('action', 'update');
    $adminId = (int) $request->user('admin')?->id;
    $restaurantId = (int) $subscription->restaurant_id;

    if ($action === 'update_status') {
      $data = $request->validate([
        'new_status' => 'required|in:trial,active,expired,cancelled,pending',
      ]);

      $oldStatus = $subscription->status;
      $this->forceUpdate($subscription, ['status' => $data['new_status']]);

      if ($data['new_status'] === 'active') {
        $service->activateSubscription($subscription->id, $subscription->billing_cycle ?? 'monthly');
      } elseif ($data['new_status'] === 'cancelled') {
        $service->deactivateSubscription($subscription->id);
      }

      $activityLog->record('admin', $adminId, 'subscription.status_changed', (int) $subscription->restaurant_id, 'subscription', (int) $subscription->id, ['status' => $oldStatus], ['status' => $data['new_status']], $request->ip(), $request->userAgent());

      $planVisibility->forgetCache($restaurantId);

      return back()->with('success', 'Subscription status updated.');
    }

    if ($action === 'change_plan') {
      $data = $request->validate([
        'new_plan_id' => 'required|integer|exists:subscription_plans,id',
      ]);

      $oldPlanId = (int) $subscription->plan_id;
      $this->forceUpdate($subscription, ['plan_id' => (int) $data['new_plan_id']]);

      $activityLog->record('admin', $adminId, 'subscription.plan_changed', (int) $subscription->restaurant_id, 'subscription', (int) $subscription->id, ['plan_id' => $oldPlanId], ['plan_id' => (int) $data['new_plan_id']], $request->ip(), $request->userAgent());

      $planVisibility->forgetCache($restaurantId);

      return back()->with('success', 'Subscription plan updated.');
    }

    if ($action === 'extend_period') {
      $data = $request->validate([
        'days' => 'required|integer|min:1|max:365',
      ]);
      $days = (int) $data['days'];
      $sub = $subscription->fresh();
      $oldValues = [
        'status' => $sub->status,
        'trial_ends_at' => $sub->trial_ends_at?->toIso8601String(),
        'current_period_end' => $sub->current_period_end?->toIso8601String(),
      ];

      if ($sub->status === 'trial') {
        $base = $sub->trial_ends_at && $sub->trial_ends_at->isFuture() ? $sub->trial_ends_at : now();
        $this->forceUpdate($sub, ['trial_ends_at' => $base->copy()->addDays($days)]);
      } elseif ($sub->status === 'expired' && $sub->trial_ends_at && ! $sub->current_period_end) {
        $base = $sub->trial_ends_at ?? now();
        $this->forceUpdate($sub, [
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
        $this->forceUpdate($sub, $payload);
      }

      $sub = $sub->fresh();
      $activityLog->record('admin', $adminId, 'subscription.extended', (int) $subscription->restaurant_id, 'subscription', (int) $subscription->id, $oldValues, [
        'status' => $sub->status,
        'trial_ends_at' => $sub->trial_ends_at?->toIso8601String(),
        'current_period_end' => $sub->current_period_end?->toIso8601String(),
        'days' => $days,
      ], $request->ip(), $request->userAgent());

      $planVisibility->forgetCache($restaurantId);

      return back()->with('success', "Subscription extended by {$days} days.");
    }

    $data = $request->validate([
      'status' => 'required|in:trial,active,expired,cancelled,pending',
      'plan_id' => 'required|integer|exists:subscription_plans,id',
      'billing_cycle' => 'nullable|in:monthly,annual',
    ]);

    $oldValues = [
      'status' => $subscription->status,
      'plan_id' => (int) $subscription->plan_id,
      'billing_cycle' => $subscription->billing_cycle,
    ];

    $this->forceUpdate($subscription, [
      'status' => $data['status'],
      'plan_id' => $data['plan_id'],
      'billing_cycle' => $data['billing_cycle'] ?? $subscription->billing_cycle ?? 'monthly',
    ]);

    if ($data['status'] === 'active') {
      $service->activateSubscription($subscription->id, $data['billing_cycle'] ?? 'monthly');
    } elseif ($data['status'] === 'cancelled') {
      $service->deactivateSubscription($subscription->id);
    }

    $activityLog->record('admin', $adminId, 'subscription.updated', (int) $subscription->restaurant_id, 'subscription', (int) $subscription->id, $oldValues, [
      'status' => $data['status'],
      'plan_id' => (int) $data['plan_id'],
      'billing_cycle' => $data['billing_cycle'] ?? $subscription->billing_cycle ?? 'monthly',
    ], $request->ip(), $request->userAgent());

    $planVisibility->forgetCache($restaurantId);

    return back()->with('success', 'Subscription updated.');
  }

  /** @param  array<string, mixed>  $attributes */
  private function forceUpdate(Subscription $subscription, array $attributes): void
  {
    $subscription->forceFill($attributes)->save();
  }
}
