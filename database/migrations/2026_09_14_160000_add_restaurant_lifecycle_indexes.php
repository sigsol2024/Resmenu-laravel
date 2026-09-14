<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('restaurants')) {
            return;
        }

        $existing = collect(Schema::getIndexes('restaurants'))->pluck('name')->all();

        Schema::table('restaurants', function (Blueprint $table) use ($existing) {
            if (Schema::hasColumn('restaurants', 'suspended_at') && ! in_array('restaurants_suspended_at_index', $existing, true)) {
                $table->index('suspended_at', 'restaurants_suspended_at_index');
            }
            if (Schema::hasColumn('restaurants', 'last_activity_at') && ! in_array('restaurants_last_activity_at_index', $existing, true)) {
                $table->index('last_activity_at', 'restaurants_last_activity_at_index');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('restaurants')) {
            return;
        }

        $existing = collect(Schema::getIndexes('restaurants'))->pluck('name')->all();

        Schema::table('restaurants', function (Blueprint $table) use ($existing) {
            if (in_array('restaurants_suspended_at_index', $existing, true)) {
                $table->dropIndex('restaurants_suspended_at_index');
            }
            if (in_array('restaurants_last_activity_at_index', $existing, true)) {
                $table->dropIndex('restaurants_last_activity_at_index');
            }
        });
    }
};
