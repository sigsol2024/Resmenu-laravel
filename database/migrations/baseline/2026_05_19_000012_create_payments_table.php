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
        DB::unprepared('CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `restaurant_id` int(11) NOT NULL,
  `subscription_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(3) NOT NULL DEFAULT \'NGN\',
  `payment_gateway` enum(\'paystack\',\'flutterwave\',\'manual\') NOT NULL,
  `transaction_reference` varchar(100) DEFAULT NULL COMMENT \'Gateway reference\',
  `gateway_response` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT \'Full gateway response\',
  `status` enum(\'pending\',\'success\',\'failed\',\'refunded\') NOT NULL DEFAULT \'pending\',
  `paid_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `payments`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
