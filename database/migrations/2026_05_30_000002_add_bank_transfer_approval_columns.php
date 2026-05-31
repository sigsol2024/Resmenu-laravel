<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('pending_bank_transfers')) {
            return;
        }

        if (! Schema::hasColumn('pending_bank_transfers', 'status')) {
            DB::statement("ALTER TABLE `pending_bank_transfers` ADD COLUMN `status` enum('pending','customer_claimed','approved','expired','cancelled') NOT NULL DEFAULT 'pending' AFTER `total`");
        }

        if (! Schema::hasColumn('pending_bank_transfers', 'customer_claimed_at')) {
            DB::statement('ALTER TABLE `pending_bank_transfers` ADD COLUMN `customer_claimed_at` datetime DEFAULT NULL AFTER `status`');
        }

        if (! Schema::hasColumn('pending_bank_transfers', 'approved_at')) {
            DB::statement('ALTER TABLE `pending_bank_transfers` ADD COLUMN `approved_at` datetime DEFAULT NULL AFTER `customer_claimed_at`');
        }

        if (! Schema::hasColumn('pending_bank_transfers', 'approved_by_manager_id')) {
            DB::statement('ALTER TABLE `pending_bank_transfers` ADD COLUMN `approved_by_manager_id` int(11) DEFAULT NULL AFTER `approved_at`');
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('pending_bank_transfers', 'approved_by_manager_id')) {
            DB::statement('ALTER TABLE `pending_bank_transfers` DROP COLUMN `approved_by_manager_id`');
        }
        if (Schema::hasColumn('pending_bank_transfers', 'approved_at')) {
            DB::statement('ALTER TABLE `pending_bank_transfers` DROP COLUMN `approved_at`');
        }
        if (Schema::hasColumn('pending_bank_transfers', 'customer_claimed_at')) {
            DB::statement('ALTER TABLE `pending_bank_transfers` DROP COLUMN `customer_claimed_at`');
        }
        if (Schema::hasColumn('pending_bank_transfers', 'status')) {
            DB::statement('ALTER TABLE `pending_bank_transfers` DROP COLUMN `status`');
        }
    }
};
