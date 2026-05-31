<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('payment_fulfillment_receipts')) {
            DB::unprepared('CREATE TABLE `payment_fulfillment_receipts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `gateway` varchar(32) NOT NULL,
  `reference` varchar(100) NOT NULL,
  `restaurant_id` int(11) DEFAULT NULL,
  `fulfillment_type` varchar(32) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `payment_fulfillment_receipts_gateway_reference_unique` (`gateway`,`reference`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        }
    }

    public function down(): void
    {
        DB::statement('DROP TABLE IF EXISTS `payment_fulfillment_receipts`');
    }
};
