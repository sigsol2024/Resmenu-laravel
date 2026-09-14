<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

/**
 * Safe read-only check that lifecycle columns/indexes exist (does not migrate).
 */
class VerifyRestaurantLifecycleSchemaCommand extends Command
{
    protected $signature = 'restaurants:verify-lifecycle-schema';

    protected $description = 'Verify suspend/lifecycle columns and indexes without running migrations';

    public function handle(): int
    {
        $ok = true;

        foreach (['suspended_at', 'suspension_reason', 'last_activity_at'] as $col) {
            if (! Schema::hasColumn('restaurants', $col)) {
                $this->error("Missing restaurants.{$col} — run migrations on this environment.");
                $ok = false;
            } else {
                $this->info("OK restaurants.{$col}");
            }
        }

        if (! Schema::hasColumn('managers', 'last_login_at')) {
            $this->error('Missing managers.last_login_at — run migrations on this environment.');
            $ok = false;
        } else {
            $this->info('OK managers.last_login_at');
        }

        $indexNames = collect(Schema::getIndexes('restaurants'))->pluck('name')->all();
        foreach (['restaurants_suspended_at_index', 'restaurants_last_activity_at_index'] as $idx) {
            if (! in_array($idx, $indexNames, true)) {
                $this->warn("Missing index {$idx} — run migration 2026_09_14_160000_add_restaurant_lifecycle_indexes.");
                $ok = false;
            } else {
                $this->info("OK index {$idx}");
            }
        }

        $nullActivity = \App\Models\Restaurant::query()->whereNull('last_activity_at')->count();
        $this->line("Restaurants with NULL last_activity_at: {$nullActivity} (skipped by auto-suspend)");

        return $ok ? self::SUCCESS : self::FAILURE;
    }
}
