<?php

namespace App\Console\Commands;

use App\Services\RestaurantLifecycleService;
use Illuminate\Console\Command;

class RestaurantLifecycleCommand extends Command
{
    protected $signature = 'restaurants:lifecycle';

    protected $description = 'Phase 1: auto-suspend inactive restaurants. Phase 2: permanently purge expired suspensions.';

    public function handle(RestaurantLifecycleService $lifecycle): int
    {
        $result = $lifecycle->runDaily();

        $this->info(sprintf(
            'Lifecycle complete — suspended: %d, purged: %d, skipped: %d',
            $result['suspended'],
            $result['purged'],
            $result['skipped']
        ));

        return self::SUCCESS;
    }
}
