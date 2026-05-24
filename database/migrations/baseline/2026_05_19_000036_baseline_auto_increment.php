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
        foreach (array (
  0 => 'ALTER TABLE `admins`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;',
  1 => 'ALTER TABLE `categories`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;',
  2 => 'ALTER TABLE `category_secondary_sections`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;',
  3 => 'ALTER TABLE `customization_settings`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;',
  4 => 'ALTER TABLE `email_delivery_suppressions`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;',
  5 => 'ALTER TABLE `login_attempts`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;',
  6 => 'ALTER TABLE `managers`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;',
  7 => 'ALTER TABLE `menu_items`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;',
  8 => 'ALTER TABLE `orders`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;',
  9 => 'ALTER TABLE `order_items`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;',
  10 => 'ALTER TABLE `password_reset_tokens`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;',
  11 => 'ALTER TABLE `payments`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;',
  12 => 'ALTER TABLE `payment_settings`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;',
  13 => 'ALTER TABLE `pending_bank_transfers`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;',
  14 => 'ALTER TABLE `pending_online_payments`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;',
  15 => 'ALTER TABLE `public_api_rate_events`
MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;',
  16 => 'ALTER TABLE `qr_code_scans`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;',
  17 => 'ALTER TABLE `qr_templates`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;',
  18 => 'ALTER TABLE `restaurants`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;',
  19 => 'ALTER TABLE `restaurant_payment_settings`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;',
  20 => 'ALTER TABLE `restaurant_qr_codes`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;',
  21 => 'ALTER TABLE `restaurant_reservation_settings`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;',
  22 => 'ALTER TABLE `sections`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;',
  23 => 'ALTER TABLE `site_settings`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;',
  24 => 'ALTER TABLE `subscriptions`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;',
  25 => 'ALTER TABLE `subscription_change_requests`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;',
  26 => 'ALTER TABLE `subscription_emails`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;',
  27 => 'ALTER TABLE `subscription_plans`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;',
  28 => 'ALTER TABLE `table_inventory_daily`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;',
  29 => 'ALTER TABLE `table_reservations`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;',
  30 => 'ALTER TABLE `templates`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;',
  31 => 'ALTER TABLE `template_customizations`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;',
) as $statement) {
            if (preg_match('/^SET FOREIGN_KEY_CHECKS=(0|1);?$/i', $statement)) {
                DB::statement(rtrim($statement, ';'));
            } else {
                DB::unprepared($statement);
            }
        }
    }

    public function down(): void
    {
        // Baseline batch rollback not supported; use migrate:fresh only on empty dev DBs.
    }
};
