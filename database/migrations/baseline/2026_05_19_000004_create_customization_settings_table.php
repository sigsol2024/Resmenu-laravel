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
        DB::unprepared('CREATE TABLE `customization_settings` (
  `id` int(11) NOT NULL,
  `restaurant_id` int(11) NOT NULL,
  `template_id` int(11) NOT NULL DEFAULT 1,
  `menu_title_color` varchar(7) DEFAULT \'#000000\',
  `menu_title_size` int(11) DEFAULT 24,
  `menu_title_font` varchar(100) DEFAULT \'Inter\',
  `price_color` varchar(7) DEFAULT \'#000000\',
  `price_size` int(11) DEFAULT 18,
  `price_font` varchar(100) DEFAULT \'Inter\',
  `description_color` varchar(7) DEFAULT \'#666666\',
  `description_size` int(11) DEFAULT 14,
  `description_font` varchar(100) DEFAULT \'Inter\',
  `category_title_color` varchar(7) DEFAULT \'#000000\',
  `category_title_size` int(11) DEFAULT 20,
  `category_title_font` varchar(100) DEFAULT \'Inter\',
  `background_color` varchar(7) DEFAULT \'#FFFFFF\',
  `header_background_color` varchar(7) DEFAULT \'#FFFFFF\',
  `primary_color` varchar(7) DEFAULT \'#111111\',
  `secondary_color` varchar(7) DEFAULT \'#FFFFFF\',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `customization_settings`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
