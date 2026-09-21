<?php

namespace App\Services;

use App\Models\Manager;
use Illuminate\Support\Facades\Log;

class ManagerEmailChangeService
{
    public function __construct(private ManagerEmailVerificationService $verification) {}

    /**
     * Apply a new email. Same address is a no-op for verification.
     * Different address clears email_verified_at and sends a new magic link.
     *
     * @return array{changed: bool, sent: bool}
     */
    public function apply(Manager $manager, string $newEmail): array
    {
        $newEmail = strtolower(trim($newEmail));
        $oldEmail = strtolower(trim((string) $manager->email));

        if ($newEmail === $oldEmail) {
            return ['changed' => false, 'sent' => false];
        }

        $manager->email = $newEmail;
        $manager->email_verified_at = null;
        $manager->save();

        $sent = false;
        try {
            $sent = $this->verification->send($manager->fresh());
        } catch (\Throwable $e) {
            Log::warning('Verification email after address change failed: '.$e->getMessage());
        }

        return ['changed' => true, 'sent' => $sent];
    }
}
