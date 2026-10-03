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
      $newStatus = $data['new_status'];

      if ($newStatus === 'active') {
        return back()->with('error', 'Paid time is only added by recording a payment under Payments → Record Payment.');
      }
      if ($newStatus === 'trial' && $subscription->current_period_end !== null) {
        return back()->with('error', 'This restaurant has had a paid period, so it cannot go back to a trial. Record a payment to renew it.');
      }

      $oldValues = ['status' => $subscription->status, 'trial_ends_at' => $subscription->trial_ends_at?->toIso8601String()];
      $attributes = ['status' => $newStatus];
      if ($newStatus === 'trial' && ! $subscription->trial_ends_at?->isFuture()) {
        // A lapsed trial reopens for the standard extension only.
        $attributes['trial_ends_at'] = now()->addDays(Subscription::TRIAL_EXTENSION_DAYS);
      }
      $this->forceUpdate($subscription, $attributes);

      if ($newStatus === 'cancelled') {
        $service->deactivateSubscription($subscription->id);
      }

      $activityLog->record('admin', $adminId, 'subscription.status_changed', (int) $subscription->restaurant_id, 'subscription', (int) $subscription->id, $oldValues, [
        'status' => $newStatus,
        'trial_ends_at' => $subscription->trial_ends_at?->toIso8601String(),
      ], $request->ip(), $request->userAgent());

      $planVisibility->forgetCache($restaurantId);

      return back()->with('success', 'Subscription status updated.');
    }

    if ($action === 'change_plan') {
      return back()->with('error', 'Plan changes require a recorded payment. Use Record Payment from the subscription actions.');
    }

    // Paid time is only added by recording a payment; admins may only nudge trials.
    if ($action === 'extend_period' || $action === 'reset_trial') {
      $sub = $subscription->fresh();
      if (! $sub->isTrialLike()) {
        return back()->with('error', 'Only trials can be extended here. For a paid plan, record the payment under Payments → Record Payment; that renews or changes the subscription.');
      }

      if ($action === 'extend_period') {
        $request->validate(['days' => 'required|integer|in:'.Subscription::TRIAL_EXTENSION_DAYS]);
        $base = $sub->trial_ends_at && $sub->trial_ends_at->isFuture() ? $sub->trial_ends_at : now();
        $newEnd = $base->copy()->addDays(Subscription::TRIAL_EXTENSION_DAYS);
        $message = 'Trial extended by '.Subscription::TRIAL_EXTENSION_DAYS.' days.';
      } else {
        if (! $sub->trialCanBeShortened()) {
          return back()->with('error', 'This trial already ends within '.Subscription::TRIAL_EXTENSION_DAYS.' days. Use "+ '.Subscription::TRIAL_EXTENSION_DAYS.' days" to extend it.');
        }
        $newEnd = now()->addDays(Subscription::TRIAL_EXTENSION_DAYS);
        $message = 'Trial now ends '.Subscription::TRIAL_EXTENSION_DAYS.' days from today ('.$newEnd->format('M j, Y').').';
      }

      $oldValues = ['status' => $sub->status, 'trial_ends_at' => $sub->trial_ends_at?->toIso8601String()];
      $this->forceUpdate($sub, ['status' => 'trial', 'trial_ends_at' => $newEnd]);

      $activityLog->record('admin', $adminId, $action === 'reset_trial' ? 'subscription.trial_reset' : 'subscription.trial_extended', $restaurantId, 'subscription', (int) $subscription->id, $oldValues, [
        'status' => 'trial',
        'trial_ends_at' => $newEnd->toIso8601String(),
      ], $request->ip(), $request->userAgent());

      $planVisibility->forgetCache($restaurantId);

      return back()->with('success', $message);
    }

    // Plan / cycle changes go through Record Payment; there is no free-form edit.
    return back()->with('error', 'Invalid action.');
  }

  /** @param  array<string, mixed>  $attributes */
  private function forceUpdate(Subscription $subscription, array $attributes): void
  {
    $subscription->forceFill($attributes)->save();
  }
}
