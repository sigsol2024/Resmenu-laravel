<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

/**
 * Remediation for environments that already applied the blanket is_super_admin=1 update.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('admins', 'is_super_admin')) {
            return;
        }

        DB::table('admins')->update(['is_super_admin' => 0]);

        $bootstrap = trim((string) env('SUPER_ADMIN_BOOTSTRAP_USERNAME', ''));
        if ($bootstrap === '') {
            Log::warning('SUPER_ADMIN_BOOTSTRAP_USERNAME is empty; no Super Admin was promoted. CRM stays locked until admins:promote-super.');

            return;
        }

        $updated = DB::table('admins')->where('username', $bootstrap)->update(['is_super_admin' => 1]);
        if ($updated === 0) {
            Log::warning('SUPER_ADMIN_BOOTSTRAP_USERNAME did not match any admin username.', [
                'username' => $bootstrap,
            ]);
        }
    }

    public function down(): void
    {
        // Intentionally no-op: do not re-blanket-promote.
    }
};
