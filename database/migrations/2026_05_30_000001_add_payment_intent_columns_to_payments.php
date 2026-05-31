<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('payments')) {
            return;
        }

        if (! Schema::hasColumn('payments', 'plan_id')) {
            DB::statement('ALTER TABLE `payments` ADD COLUMN `plan_id` int(11) DEFAULT NULL AFTER `subscription_id`');
        }

        if (! Schema::hasColumn('payments', 'billing_cycle')) {
            DB::statement("ALTER TABLE `payments` ADD COLUMN `billing_cycle` varchar(20) DEFAULT NULL AFTER `plan_id`");
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('payments', 'billing_cycle')) {
            DB::statement('ALTER TABLE `payments` DROP COLUMN `billing_cycle`');
        }
        if (Schema::hasColumn('payments', 'plan_id')) {
            DB::statement('ALTER TABLE `payments` DROP COLUMN `plan_id`');
        }
    }
};
