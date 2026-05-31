<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('email_delivery_suppressions')) {
            return;
        }

        $indexes = DB::select("SHOW INDEX FROM email_delivery_suppressions WHERE Key_name = 'uniq_email_sha256'");
        if ($indexes !== []) {
            return;
        }

        DB::statement('ALTER TABLE `email_delivery_suppressions` ADD UNIQUE KEY `uniq_email_sha256` (`email_sha256`)');
    }

    public function down(): void
    {
        if (! Schema::hasTable('email_delivery_suppressions')) {
            return;
        }

        $indexes = DB::select("SHOW INDEX FROM email_delivery_suppressions WHERE Key_name = 'uniq_email_sha256'");
        if ($indexes === []) {
            return;
        }

        DB::statement('ALTER TABLE `email_delivery_suppressions` DROP INDEX `uniq_email_sha256`');
    }
};
