<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('subscription_email_log')) {
            return;
        }

        Schema::create('subscription_email_log', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subscription_id');
            $table->string('email_type', 50);
            $table->integer('days_before')->default(0);
            $table->timestamp('sent_at');
            $table->unique(['subscription_id', 'email_type', 'days_before'], 'sub_email_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_email_log');
    }
};
