<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ActivityLogService
{
    /** @param  array<string, mixed>|null  $oldValues */
    /** @param  array<string, mixed>|null  $newValues */
    public function record(
        string $actorType,
        ?int $actorId,
        string $action,
        ?int $restaurantId = null,
        ?string $subjectType = null,
        ?int $subjectId = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?string $ip = null,
        ?string $userAgent = null,
    ): void {
        if (! Schema::hasTable('activity_logs')) {
            return;
        }

        DB::table('activity_logs')->insert([
            'actor_type' => $actorType,
            'actor_id' => $actorId,
            'restaurant_id' => $restaurantId,
            'action' => $action,
            'subject_type' => $subjectType,
            'subject_id' => $subjectId,
            'old_values' => $oldValues !== null ? json_encode($oldValues) : null,
            'new_values' => $newValues !== null ? json_encode($newValues) : null,
            'ip' => $ip,
            'user_agent' => $userAgent !== null ? substr($userAgent, 0, 512) : null,
            'created_at' => now(),
        ]);
    }
}
