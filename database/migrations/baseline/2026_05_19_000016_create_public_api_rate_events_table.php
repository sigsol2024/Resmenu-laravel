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
        DB::unprepared('CREATE TABLE `public_api_rate_events` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `action` varchar(64) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `public_api_rate_events`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
