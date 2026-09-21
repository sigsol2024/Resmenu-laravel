<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Admin Management & Permissions: active flag + eight module permission booleans.
 * Deny-by-default for Regular Admins; does not modify is_super_admin.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('admins')) {
            return;
        }

        Schema::table('admins', function (Blueprint $table) {
            if (! Schema::hasColumn('admins', 'is_active')) {
                $table->boolean('is_active')->default(true);
            }
            if (! Schema::hasColumn('admins', 'can_subscription_plans')) {
                $table->boolean('can_subscription_plans')->default(false);
            }
            if (! Schema::hasColumn('admins', 'can_subscriptions')) {
                $table->boolean('can_subscriptions')->default(false);
            }
            if (! Schema::hasColumn('admins', 'can_payments')) {
                $table->boolean('can_payments')->default(false);
            }
            if (! Schema::hasColumn('admins', 'can_payment_settings')) {
                $table->boolean('can_payment_settings')->default(false);
            }
            if (! Schema::hasColumn('admins', 'can_templates')) {
                $table->boolean('can_templates')->default(false);
            }
            if (! Schema::hasColumn('admins', 'can_qr_templates')) {
                $table->boolean('can_qr_templates')->default(false);
            }
            if (! Schema::hasColumn('admins', 'can_settings')) {
                $table->boolean('can_settings')->default(false);
            }
            if (! Schema::hasColumn('admins', 'can_crm')) {
                $table->boolean('can_crm')->default(false);
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('admins')) {
            return;
        }

        Schema::table('admins', function (Blueprint $table) {
            $columns = [
                'is_active',
                'can_subscription_plans',
                'can_subscriptions',
                'can_payments',
                'can_payment_settings',
                'can_templates',
                'can_qr_templates',
                'can_settings',
                'can_crm',
            ];

            $existing = array_values(array_filter(
                $columns,
                fn (string $column): bool => Schema::hasColumn('admins', $column)
            ));

            if ($existing !== []) {
                $table->dropColumn($existing);
            }
        });
    }
};
