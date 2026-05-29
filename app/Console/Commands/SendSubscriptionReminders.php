<?php

namespace App\Console\Commands;

use App\Models\Manager;
use App\Models\Restaurant;
use App\Models\Subscription;
use App\Services\MailService;
use App\Services\SubscriptionService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SendSubscriptionReminders extends Command
{
    protected $signature = 'subscriptions:send-reminders';

    protected $description = 'Send trial expiry and renewal reminder emails';

    public function handle(MailService $mail, SubscriptionService $subscriptions): int
    {
        $subscriptions->syncExpiredStatuses();
        $sent = 0;

        foreach ([7, 1] as $days) {
            $targetDate = Carbon::now()->addDays($days)->toDateString();
            $rows = Subscription::query()
                ->where('status', 'trial')
                ->whereDate('trial_ends_at', $targetDate)
                ->get();

            foreach ($rows as $sub) {
                if ($this->wasEmailSent((int) $sub->id, 'trial_ending', $days)) {
                    continue;
                }

                $email = $this->recipientEmail((int) $sub->restaurant_id);
                if (! $email) {
                    continue;
                }

                $restaurant = Restaurant::find($sub->restaurant_id);
                $name = $restaurant?->name ?? 'your restaurant';
                $subject = "Your trial ends in {$days} day".($days > 1 ? 's' : '').' — Resmenu';
                $html = '<p>Hi,</p><p>Your trial for <strong>'.e($name).'</strong> ends in '
                    .$days.' day'.($days > 1 ? 's' : '')
                    .'. <a href="'.e(url('/manager')).'">Subscribe now</a> to keep your menu online.</p>';

                if ($mail->send($email, $name, $subject, $html)) {
                    $this->recordEmailSent((int) $sub->id, 'trial_ending', $days);
                    $sent++;
                    $this->info("Trial reminder ({$days}d) → {$email}");
                }
            }
        }

        $expiredToday = Subscription::query()
            ->where('status', 'trial')
            ->whereDate('trial_ends_at', Carbon::yesterday()->toDateString())
            ->get();

        foreach ($expiredToday as $sub) {
            if ($this->wasEmailSent((int) $sub->id, 'trial_expired', 0)) {
                continue;
            }
            $email = $this->recipientEmail((int) $sub->restaurant_id);
            if (! $email) {
                continue;
            }
            $restaurant = Restaurant::find($sub->restaurant_id);
            $name = $restaurant?->name ?? 'your restaurant';
            $html = '<p>Your trial for <strong>'.e($name).'</strong> has ended. '
                .'<a href="'.e(url('/register')).'">Renew your subscription</a> to restore access.</p>';
            if ($mail->send($email, $name, 'Your trial has ended — Resmenu', $html)) {
                $this->recordEmailSent((int) $sub->id, 'trial_expired', 0);
                $sent++;
            }
        }

        $this->info("Sent {$sent} reminder email(s).");

        return self::SUCCESS;
    }

    private function recipientEmail(int $restaurantId): ?string
    {
        $managerEmail = Manager::query()->where('restaurant_id', $restaurantId)->value('email');

        return $managerEmail ?: Restaurant::query()->where('id', $restaurantId)->value('email');
    }

    private function wasEmailSent(int $subscriptionId, string $type, int $days): bool
    {
        try {
            return DB::table('subscription_email_log')
                ->where('subscription_id', $subscriptionId)
                ->where('email_type', $type)
                ->where('days_before', $days)
                ->exists();
        } catch (\Throwable) {
            return false;
        }
    }

    private function recordEmailSent(int $subscriptionId, string $type, int $days): void
    {
        try {
            DB::table('subscription_email_log')->insert([
                'subscription_id' => $subscriptionId,
                'email_type' => $type,
                'days_before' => $days,
                'sent_at' => now(),
            ]);
        } catch (\Throwable) {
            // Table may not exist on older DBs — skip deduplication
        }
    }
}
