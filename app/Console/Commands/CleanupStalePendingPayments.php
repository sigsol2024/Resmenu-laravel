<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class CleanupStalePendingPayments extends Command
{
    protected $signature = 'payments:cleanup-stale {--hours=24 : Delete drafts older than this many hours}';

    protected $description = 'Remove abandoned pending online payments and expired bank transfer drafts';

    public function handle(): int
    {
        $hours = max(1, (int) $this->option('hours'));
        $cutoff = now()->subHours($hours);
        $online = 0;
        $bank = 0;

        if (Schema::hasTable('pending_online_payments')) {
            $online = DB::table('pending_online_payments')->where('created_at', '<', $cutoff)->delete();
        }

        if (Schema::hasTable('pending_bank_transfers')) {
            $bank = DB::table('pending_bank_transfers')
                ->where('created_at', '<', $cutoff)
                ->whereIn('status', ['pending', 'customer_claimed', 'expired', 'cancelled'])
                ->delete();
        }

        Log::info('Stale pending payments cleaned', ['online' => $online, 'bank' => $bank, 'hours' => $hours]);
        $this->info("Deleted {$online} pending online payment(s) and {$bank} bank transfer draft(s).");

        return self::SUCCESS;
    }
}
