<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            if (! Schema::hasColumn('restaurants', 'suspended_at')) {
                $table->timestamp('suspended_at')->nullable()->after('is_active');
            }
            if (! Schema::hasColumn('restaurants', 'suspension_reason')) {
                $table->string('suspension_reason', 32)->nullable()->after('suspended_at');
            }
            if (! Schema::hasColumn('restaurants', 'last_activity_at')) {
                $table->timestamp('last_activity_at')->nullable()->after('suspension_reason');
            }
        });

        Schema::table('managers', function (Blueprint $table) {
            if (! Schema::hasColumn('managers', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable()->after('restaurant_id');
            }
        });

        // Seed last_activity_at so NULL never means "expired" for existing rows.
        DB::table('restaurants')
            ->whereNull('last_activity_at')
            ->update(['last_activity_at' => DB::raw('COALESCE(updated_at, created_at, NOW())')]);
    }

    public function down(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            foreach (['suspended_at', 'suspension_reason', 'last_activity_at'] as $col) {
                if (Schema::hasColumn('restaurants', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        Schema::table('managers', function (Blueprint $table) {
            if (Schema::hasColumn('managers', 'last_login_at')) {
                $table->dropColumn('last_login_at');
            }
        });
    }
};
