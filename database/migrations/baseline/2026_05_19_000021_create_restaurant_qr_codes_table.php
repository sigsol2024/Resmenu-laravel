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
        DB::unprepared('CREATE TABLE `restaurant_qr_codes` (
  `id` int(11) NOT NULL,
  `restaurant_id` int(11) NOT NULL,
  `qr_template_id` int(11) DEFAULT 1,
  `override_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`override_json`)),
  `final_config_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`final_config_json`)),
  `background_color` varchar(7) DEFAULT \'#FFFFFF\',
  `qr_color` varchar(7) DEFAULT \'#000000\',
  `text_content` text DEFAULT NULL,
  `text_color` varchar(7) DEFAULT \'#000000\',
  `text_size` int(11) DEFAULT 16,
  `text_font` varchar(100) DEFAULT \'Arial\',
  `qr_size` int(11) DEFAULT 300,
  `margin` int(11) DEFAULT 20,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `restaurant_qr_codes`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
