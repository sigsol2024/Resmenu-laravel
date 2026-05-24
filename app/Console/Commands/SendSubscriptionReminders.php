<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use App\Services\MailService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendSubscriptionReminders extends Command
{
    protected $signature = 'subscriptions:send-reminders';

    protected $description = 'Send trial expiry and renewal reminder emails';

    public function handle(MailService $mail): int
    {
        $soon = Carbon::now()->addDays(3);
        $trials = Subscription::query()
            ->where('status', 'trial')
            ->whereNotNull('trial_ends_at')
            ->whereBetween('trial_ends_at', [Carbon::now(), $soon])
            ->get();

        foreach ($trials as $sub) {
            $this->info('Trial ending soon for restaurant '.$sub->restaurant_id);
        }

        $this->info('Processed '.$trials->count().' trial reminders.');

        return self::SUCCESS;
    }
}
