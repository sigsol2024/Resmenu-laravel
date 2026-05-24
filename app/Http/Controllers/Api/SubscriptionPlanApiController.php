<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SubscriptionService;
use App\Support\ApiJsonResponse;
use Illuminate\Http\Request;

class SubscriptionPlanApiController extends Controller
{
    public function __construct(private SubscriptionService $subscriptions) {}

    public function index(Request $request)
    {
        $activeOnly = ! $request->has('active_only') || (string) $request->query('active_only') !== '0';
        $plans = $this->subscriptions->getPlans($activeOnly);

        if ($plans === []) {
            return ApiJsonResponse::error('No subscription plans found', [], 404);
        }

        return ApiJsonResponse::success('Subscription plans retrieved successfully', $plans);
    }
}
