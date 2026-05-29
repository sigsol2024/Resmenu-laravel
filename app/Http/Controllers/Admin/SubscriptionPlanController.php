<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubscriptionPlanController extends Controller
{
    public function index()
    {
        return view('admin.subscription-plans.index', [
            'plans' => SubscriptionPlan::query()->orderBy('display_order')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.subscription-plans.form', ['plan' => new SubscriptionPlan(['is_active' => true])]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        SubscriptionPlan::create($data);

        return redirect()->route('admin.subscription-plans.index')->with('success', 'Plan created.');
    }

    public function edit(SubscriptionPlan $subscriptionPlan)
    {
        return view('admin.subscription-plans.form', ['plan' => $subscriptionPlan]);
    }

    public function update(Request $request, SubscriptionPlan $subscriptionPlan)
    {
        $subscriptionPlan->update($this->validated($request));

        return redirect()->route('admin.subscription-plans.index')->with('success', 'Plan updated.');
    }

    public function destroy(SubscriptionPlan $subscriptionPlan)
    {
        $active = Subscription::where('plan_id', $subscriptionPlan->id)->whereIn('status', ['trial', 'active'])->count();
        if ($active > 0) {
            return back()->with('error', 'Cannot delete plan with active subscriptions.');
        }

        $subscriptionPlan->delete();

        return redirect()->route('admin.subscription-plans.index')->with('success', 'Plan deleted.');
    }

    public function toggle(SubscriptionPlan $subscriptionPlan)
    {
        if ($subscriptionPlan->is_active) {
            $active = Subscription::where('plan_id', $subscriptionPlan->id)->whereIn('status', ['trial', 'active'])->count();
            if ($active > 0) {
                return back()->with('error', 'Cannot deactivate plan with active subscriptions.');
            }
        }

        $subscriptionPlan->update(['is_active' => ! $subscriptionPlan->is_active]);

        return back()->with('success', 'Plan status updated.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'monthly_price' => 'required|numeric|min:0',
            'yearly_discount_percent' => 'nullable|numeric|min:0|max:100',
            'max_categories' => 'nullable|integer|min:-1',
            'max_menu_items' => 'nullable|integer|min:-1',
            'max_qr_styles' => 'nullable|integer|min:-1',
            'max_templates' => 'nullable|integer|min:-1',
            'is_active' => 'nullable|boolean',
            'display_order' => 'nullable|integer',
        ]);

        $monthly = (float) $data['monthly_price'];
        $discount = (float) ($data['yearly_discount_percent'] ?? 20);
        $data['annual_price'] = round($monthly * 12 * (1 - $discount / 100), 2);
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active', true);
        $data['features'] = [
            'food_ordering' => $request->boolean('feature_food_ordering'),
            'table_reservations' => $request->boolean('feature_table_reservations'),
        ];

        return $data;
    }
}
