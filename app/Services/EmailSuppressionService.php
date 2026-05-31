<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class EmailSuppressionService
{
    public function isSuppressed(string $email): bool
    {
        if (! filter_var($email, FILTER_VALIDATE_EMAIL) || ! Schema::hasTable('email_delivery_suppressions')) {
            return false;
        }

        $hash = $this->emailSha256($email);

        return DB::table('email_delivery_suppressions')->where('email_sha256', $hash)->exists();
    }

    public function addSuppression(string $email, string $reason = 'hard_bounce', string $source = 'webhook'): bool
    {
        if (! filter_var($email, FILTER_VALIDATE_EMAIL) || ! Schema::hasTable('email_delivery_suppressions')) {
            return false;
        }

        $hash = $this->emailSha256($email);
        $reason = substr(preg_replace('/[^a-z0-9_\-]/i', '', $reason) ?: 'hard_bounce', 0, 64);
        $source = substr(preg_replace('/[^a-z0-9_\-]/i', '', $source) ?: 'webhook', 0, 64);

        DB::statement(
            'INSERT INTO email_delivery_suppressions (email_sha256, reason, source, created_at)
             VALUES (?, ?, ?, ?)
             ON DUPLICATE KEY UPDATE reason = VALUES(reason), source = VALUES(source)',
            [$hash, $reason, $source, now()]
        );

        return true;
    }

    private function emailSha256(string $email): string
    {
        return hash('sha256', strtolower(trim($email)));
    }
}
