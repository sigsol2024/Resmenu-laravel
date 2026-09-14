<?php

namespace App\Services;

use App\Models\Manager;
use App\Models\Restaurant;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RestaurantLifecycleService
{
    public const REASON_ADMIN = 'admin';

    public const REASON_INACTIVITY = 'inactivity';

    public function __construct(
        private RestaurantDeletionService $deletion,
        private RestaurantTransactionalMailService $mail,
    ) {}

    public function touchActivity(Restaurant|int $restaurant): void
    {
        $id = $restaurant instanceof Restaurant ? (int) $restaurant->id : (int) $restaurant;
        if ($id <= 0) {
            return;
        }

        Restaurant::query()->where('id', $id)->update(['last_activity_at' => now()]);
    }

    /**
     * Suspend once. Returns true if newly suspended.
     */
    public function suspend(Restaurant $restaurant, string $reason, bool $sendEmail = true): bool
    {
        if ($restaurant->isSuspended()) {
            return false;
        }

        $reason = $reason === self::REASON_ADMIN ? self::REASON_ADMIN : self::REASON_INACTIVITY;

        $restaurant->forceFill([
            'suspended_at' => now(),
            'suspension_reason' => $reason,
            'is_active' => false,
        ])->save();

        if ($sendEmail) {
            try {
                $this->mail->sendAccountSuspended($restaurant, $reason);
            } catch (\Throwable $e) {
                Log::warning('restaurant.suspend_email_failed', [
                    'restaurant_id' => $restaurant->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return true;
    }

    public function restore(Restaurant $restaurant): void
    {
        $restaurant->forceFill([
            'suspended_at' => null,
            'suspension_reason' => null,
            'is_active' => true,
            'last_activity_at' => now(),
        ])->save();
    }

    /**
     * Phase 1: auto-suspend inactive; Phase 2: purge expired suspensions.
     *
     * @return array{suspended: int, purged: int, skipped: int}
     */
    public function runDaily(): array
    {
        $suspended = 0;
        $purged = 0;
        $skipped = 0;

        $inactivityDays = (int) config('restaurant_lifecycle.inactivity_days', 30);
        $purgeDays = (int) config('restaurant_lifecycle.purge_days_after_suspend', 7);
        $inactiveBefore = Carbon::now()->subDays($inactivityDays);
        $purgeBefore = Carbon::now()->subDays($purgeDays);

        Restaurant::query()
            ->whereNull('suspended_at')
            ->whereNotNull('last_activity_at')
            ->where('last_activity_at', '<=', $inactiveBefore)
            ->orderBy('id')
            ->chunkById(50, function ($rows) use (&$suspended) {
                foreach ($rows as $restaurant) {
                    if ($this->suspend($restaurant, self::REASON_INACTIVITY, true)) {
                        $suspended++;
                    }
                }
            });

        Restaurant::query()
            ->whereNotNull('suspended_at')
            ->where('suspended_at', '<=', $purgeBefore)
            ->orderBy('id')
            ->chunkById(20, function ($rows) use (&$purged, &$skipped, $purgeBefore) {
                foreach ($rows as $restaurant) {
                    try {
                        $deleted = $this->purgeIfStillEligible((int) $restaurant->id, $purgeBefore);
                        if ($deleted) {
                            $purged++;
                        } else {
                            $skipped++;
                        }
                    } catch (\Throwable $e) {
                        $skipped++;
                        Log::error('restaurant.purge_failed', [
                            'restaurant_id' => $restaurant->id,
                            'error' => $e->getMessage(),
                        ]);
                    }
                }
            });

        return compact('suspended', 'purged', 'skipped');
    }

    /**
     * Re-check eligibility under row lock immediately before permanent delete.
     * Prevents wiping a restaurant restored after the purge chunk was loaded.
     * Files are unlinked only after the lock transaction commits successfully.
     */
    private function purgeIfStillEligible(int $restaurantId, Carbon $purgeBefore): bool
    {
        $files = null;

        $deleted = DB::transaction(function () use ($restaurantId, $purgeBefore, &$files) {
            $fresh = Restaurant::query()
                ->whereKey($restaurantId)
                ->whereNotNull('suspended_at')
                ->where('suspended_at', '<=', $purgeBefore)
                ->lockForUpdate()
                ->first();

            if (! $fresh) {
                return false;
            }

            $files = $this->deletion->deleteRowCollectingFiles($fresh);

            return true;
        });

        if ($deleted && is_array($files)) {
            $this->deletion->unlinkCollectedFiles($files);
        }

        return (bool) $deleted;
    }

    public function recordManagerLogin(Manager $manager): void
    {
        $manager->forceFill(['last_login_at' => now()])->save();
        if ($manager->restaurant_id) {
            $this->touchActivity((int) $manager->restaurant_id);
        }
    }

    public function scheduledPurgeDate(?Restaurant $restaurant): ?Carbon
    {
        if (! $restaurant || ! $restaurant->suspended_at) {
            return null;
        }
        $days = (int) config('restaurant_lifecycle.purge_days_after_suspend', 7);

        return Carbon::parse($restaurant->suspended_at)->addDays($days);
    }
}
