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
ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);',
  1 => 'ALTER TABLE `categories`
ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_restaurant_slug` (`restaurant_id`,`slug`),
  ADD KEY `idx_restaurant_id` (`restaurant_id`),
  ADD KEY `idx_slug` (`slug`),
  ADD KEY `idx_display_order` (`display_order`),
  ADD KEY `idx_section_id` (`section_id`);',
  2 => 'ALTER TABLE `category_secondary_sections`
ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_category_secondary` (`category_id`,`section_id`),
  ADD KEY `idx_secondary_section` (`section_id`);',
  3 => 'ALTER TABLE `customization_settings`
ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `restaurant_template` (`restaurant_id`,`template_id`);',
  4 => 'ALTER TABLE `email_delivery_suppressions`
ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_email_sha256` (`email_sha256`),
  ADD KEY `idx_created` (`created_at`);',
  5 => 'ALTER TABLE `login_attempts`
ADD PRIMARY KEY (`id`),
  ADD KEY `idx_login_attempts_ip_time` (`ip_address`,`attempted_at`),
  ADD KEY `idx_login_attempts_identifier_time` (`identifier`(191),`attempted_at`);',
  6 => 'ALTER TABLE `managers`
ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_restaurant_id` (`restaurant_id`);',
  7 => 'ALTER TABLE `menu_items`
ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_restaurant_category_slug` (`restaurant_id`,`category_id`,`slug`),
  ADD KEY `idx_restaurant_id` (`restaurant_id`),
  ADD KEY `idx_category_id` (`category_id`),
  ADD KEY `idx_slug` (`slug`),
  ADD KEY `idx_display_order` (`display_order`);',
  8 => 'ALTER TABLE `orders`
ADD PRIMARY KEY (`id`),
  ADD KEY `restaurant_id` (`restaurant_id`),
  ADD KEY `status` (`status`),
  ADD KEY `created_at` (`created_at`);',
  9 => 'ALTER TABLE `order_items`
ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `menu_item_id` (`menu_item_id`);',
  10 => 'ALTER TABLE `password_reset_tokens`
ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_token_hash` (`token_hash`),
  ADD KEY `idx_user_active` (`user_type`,`user_id`,`used_at`,`expires_at`),
  ADD KEY `idx_identifier_created` (`identifier`,`created_at`),
  ADD KEY `idx_ip_created` (`request_ip`,`created_at`);',
  11 => 'ALTER TABLE `payments`
ADD PRIMARY KEY (`id`),
  ADD KEY `restaurant_id` (`restaurant_id`),
  ADD KEY `subscription_id` (`subscription_id`),
  ADD KEY `status` (`status`),
  ADD KEY `transaction_reference` (`transaction_reference`);',
  12 => 'ALTER TABLE `payment_settings`
ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `gateway` (`gateway`);',
  13 => 'ALTER TABLE `pending_bank_transfers`
ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `restaurant_id` (`restaurant_id`),
  ADD KEY `created_at` (`created_at`);',
  14 => 'ALTER TABLE `pending_online_payments`
ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reference` (`reference`),
  ADD KEY `restaurant_id` (`restaurant_id`),
  ADD KEY `created_at` (`created_at`);',
  15 => 'ALTER TABLE `public_api_rate_events`
ADD PRIMARY KEY (`id`),
  ADD KEY `idx_action_ip_time` (`action`,`ip_address`,`created_at`);',
  16 => 'ALTER TABLE `qr_code_scans`
ADD PRIMARY KEY (`id`),
  ADD KEY `restaurant_id` (`restaurant_id`),
  ADD KEY `scanned_at` (`scanned_at`);',
  17 => 'ALTER TABLE `qr_templates`
ADD PRIMARY KEY (`id`);',
  18 => 'ALTER TABLE `restaurants`
ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_slug` (`slug`),
  ADD KEY `idx_is_active` (`is_active`),
  ADD KEY `subscription_id` (`subscription_id`);',
  19 => 'ALTER TABLE `restaurant_payment_settings`
ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `restaurant_id_gateway` (`restaurant_id`,`gateway`);',
  20 => 'ALTER TABLE `restaurant_qr_codes`
ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `restaurant_id` (`restaurant_id`),
  ADD KEY `qr_template_id` (`qr_template_id`);',
  21 => 'ALTER TABLE `restaurant_reservation_settings`
ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `restaurant_id` (`restaurant_id`);',
  22 => 'ALTER TABLE `sections`
ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_restaurant_section_slug` (`restaurant_id`,`slug`),
  ADD KEY `idx_sections_restaurant` (`restaurant_id`),
  ADD KEY `idx_sections_display_order` (`display_order`);',
  23 => 'ALTER TABLE `site_settings`
ADD PRIMARY KEY (`id`);',
  24 => 'ALTER TABLE `subscriptions`
ADD PRIMARY KEY (`id`),
  ADD KEY `restaurant_id` (`restaurant_id`),
  ADD KEY `plan_id` (`plan_id`),
  ADD KEY `status` (`status`),
  ADD KEY `current_period_end` (`current_period_end`);',
  25 => 'ALTER TABLE `subscription_change_requests`
ADD PRIMARY KEY (`id`),
  ADD KEY `idx_subscription_pending` (`subscription_id`,`status`),
  ADD KEY `idx_effective_pending` (`effective_at`,`status`),
  ADD KEY `idx_restaurant_pending` (`restaurant_id`,`status`),
  ADD KEY `subscription_change_requests_ibfk_3` (`from_plan_id`),
  ADD KEY `subscription_change_requests_ibfk_4` (`to_plan_id`);',
  26 => 'ALTER TABLE `subscription_emails`
ADD PRIMARY KEY (`id`),
  ADD KEY `subscription_id` (`subscription_id`),
  ADD KEY `idx_subscription_email_lookup` (`subscription_id`,`email_type`,`days_before`,`sent_at`);',
  27 => 'ALTER TABLE `subscription_plans`
ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);',
  28 => 'ALTER TABLE `table_inventory_daily`
ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `restaurant_date` (`restaurant_id`,`inventory_date`),
  ADD KEY `restaurant_id` (`restaurant_id`),
  ADD KEY `inventory_date` (`inventory_date`);',
  29 => 'ALTER TABLE `table_reservations`
ADD PRIMARY KEY (`id`),
  ADD KEY `restaurant_id` (`restaurant_id`),
  ADD KEY `reservation_date` (`reservation_date`),
  ADD KEY `status` (`status`);',
  30 => 'ALTER TABLE `templates`
ADD PRIMARY KEY (`id`),
  ADD KEY `idx_is_active` (`is_active`);',
  31 => 'ALTER TABLE `template_customizations`
ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `template_id` (`template_id`);',
  32 => 'ALTER TABLE `template_plans`
ADD PRIMARY KEY (`template_id`,`plan_id`),
  ADD KEY `plan_id` (`plan_id`);',
  33 => 'ALTER TABLE `template_restaurants`
ADD PRIMARY KEY (`template_id`,`restaurant_id`),
  ADD KEY `restaurant_id` (`restaurant_id`);',
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
