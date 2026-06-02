<?php

namespace App\Console\Commands;

use App\Services\SubscriptionPaymentLifecycleService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class FailStaleSubscriptionPayments extends Command
{
    protected $signature = 'payments:fail-stale-subscription {--hours= : Mark pending payments older than this many hours as failed}';

    protected $description = 'Mark abandoned subscription payment records as failed after the pending threshold';

    public function handle(SubscriptionPaymentLifecycleService $lifecycle): int
    {
        $hoursOption = $this->option('hours');
        $hours = $hoursOption !== null && $hoursOption !== '' ? max(1, (int) $hoursOption) : null;

        $count = $lifecycle->failStalePendingSubscriptionPayments($hours);

        Log::info('Stale subscription payments marked failed', [
            'count' => $count,
            'threshold_hours' => $hours ?? $lifecycle->pendingThresholdHours(),
        ]);

        $this->info("Marked {$count} stale subscription payment(s) as failed.");

        return self::SUCCESS;
    }
}
