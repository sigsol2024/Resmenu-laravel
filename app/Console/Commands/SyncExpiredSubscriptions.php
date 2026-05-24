<?php

namespace App\Console\Commands;

use App\Services\SubscriptionService;
use Illuminate\Console\Command;

class SyncExpiredSubscriptions extends Command
{
    protected $signature = 'subscriptions:sync-expired';

    protected $description = 'Mark expired trials and subscriptions';

    public function handle(SubscriptionService $subscriptions): int
    {
        $count = $subscriptions->syncExpiredStatuses();
        $this->info("Updated {$count} subscription row(s) to expired.");

        return self::SUCCESS;
    }
}
