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
        DB::unprepared('CREATE TABLE `subscription_plans` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL COMMENT \'Basic, Professional, Enterprise\',
  `slug` varchar(50) NOT NULL COMMENT \'basic, professional, enterprise\',
  `description` text DEFAULT NULL,
  `monthly_price` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT \'Price in NGN\',
  `annual_price` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT \'Price in NGN (20% discount)\',
  `yearly_discount_percent` decimal(5,2) NOT NULL DEFAULT 20.00 COMMENT \'Discount % applied to yearly plan (annual price = monthly*12*(1 - this/100))\',
  `max_categories` int(11) NOT NULL DEFAULT 5 COMMENT \'-1 for unlimited\',
  `max_menu_items` int(11) NOT NULL DEFAULT 50 COMMENT \'-1 for unlimited\',
  `max_qr_styles` int(11) NOT NULL DEFAULT 3 COMMENT \'-1 for unlimited\',
  `max_templates` int(11) NOT NULL DEFAULT 3 COMMENT \'-1 for unlimited\',
  `features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT \'Additional features as JSON\',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `display_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `subscription_plans`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
