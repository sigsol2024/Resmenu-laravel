<?php

namespace App\Console\Commands;

use App\Services\SubscriptionService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApplyScheduledSubscriptionChanges extends Command
{
    protected $signature = 'subscriptions:apply-scheduled';

    protected $description = 'Apply pending subscription plan/cycle changes that are due';

    public function handle(SubscriptionService $subscriptions): int
    {
        $rows = DB::table('subscription_change_requests')
            ->where('status', 'pending')
            ->where('effective_at', '<=', now())
            ->orderBy('id')
            ->get();

        $applied = 0;
        foreach ($rows as $row) {
            if ($subscriptions->applyScheduledChange((int) $row->id)) {
                $applied++;
            }
        }

        Log::info('Scheduled subscription changes applied', ['count' => $applied]);
        $this->info("Applied {$applied} scheduled subscription change(s).");

        return self::SUCCESS;
    }
}
