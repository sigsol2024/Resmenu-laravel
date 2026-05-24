<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Baseline migration (schema-baseline-v1). IMMUTABLE after freeze — fix forward only.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared('CREATE TABLE `payment_settings` (
  `id` int(11) NOT NULL,
  `gateway` varchar(50) NOT NULL COMMENT \'paystack or flutterwave\',
  `is_active` tinyint(1) NOT NULL DEFAULT 0 COMMENT \'Enable/disable gateway\',
  `test_mode` tinyint(1) NOT NULL DEFAULT 1 COMMENT \'Use test or live keys\',
  `public_key_live` varchar(255) DEFAULT NULL COMMENT \'Live public key\',
  `secret_key_live` text DEFAULT NULL COMMENT \'Live secret key (encrypted)\',
  `webhook_secret_live` varchar(255) DEFAULT NULL COMMENT \'Live webhook secret\',
  `public_key_test` varchar(255) DEFAULT NULL COMMENT \'Test public key\',
  `secret_key_test` text DEFAULT NULL COMMENT \'Test secret key (encrypted)\',
  `webhook_secret_test` varchar(255) DEFAULT NULL COMMENT \'Test webhook secret\',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `payment_settings`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
