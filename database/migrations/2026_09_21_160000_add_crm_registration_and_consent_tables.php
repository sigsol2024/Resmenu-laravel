<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('managers', function (Blueprint $table) {
            if (! Schema::hasColumn('managers', 'phone')) {
                $table->string('phone', 40)->nullable()->after('email');
            }
            if (! Schema::hasColumn('managers', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable()->after('phone');
            }
            if (! Schema::hasColumn('managers', 'marketing_consent')) {
                $table->boolean('marketing_consent')->default(false)->after('email_verified_at');
            }
            if (! Schema::hasColumn('managers', 'marketing_consent_at')) {
                $table->timestamp('marketing_consent_at')->nullable()->after('marketing_consent');
            }
            if (! Schema::hasColumn('managers', 'marketing_consent_source')) {
                $table->string('marketing_consent_source', 40)->nullable()->after('marketing_consent_at');
            }
            if (! Schema::hasColumn('managers', 'marketing_consent_text_version')) {
                $table->string('marketing_consent_text_version', 32)->nullable()->after('marketing_consent_source');
            }
        });

        Schema::table('admins', function (Blueprint $table) {
            if (! Schema::hasColumn('admins', 'is_super_admin')) {
                $table->boolean('is_super_admin')->default(false)->after('password_hash');
            }
        });

        // Do NOT blanket-promote all admins. Bootstrap happens via SUPER_ADMIN_BOOTSTRAP_USERNAME
        // (see 2026_09_21_170000_fix_super_admin_bootstrap.php and admins:promote-super).
        if (Schema::hasColumn('admins', 'is_super_admin')) {
            DB::table('admins')->update(['is_super_admin' => 0]);
            $bootstrap = trim((string) env('SUPER_ADMIN_BOOTSTRAP_USERNAME', ''));
            if ($bootstrap !== '') {
                DB::table('admins')->where('username', $bootstrap)->update(['is_super_admin' => 1]);
            }
        }

        if (! Schema::hasTable('crm_settings')) {
            Schema::create('crm_settings', function (Blueprint $table) {
                $table->unsignedTinyInteger('id')->primary();
                $table->boolean('enabled')->default(false);
                $table->string('provider', 40)->default('hubspot');
                $table->boolean('sync_contacts')->default(true);
                $table->boolean('sync_marketing_consent')->default(true);
                $table->boolean('website_tracking')->default(false);
                $table->boolean('live_chat')->default(false);
                $table->string('hubspot_portal_id', 64)->nullable();
                $table->text('hubspot_private_app_token_encrypted')->nullable();
                $table->string('hubspot_subscription_type_id', 64)->nullable();
                $table->timestamps();
            });

            DB::table('crm_settings')->insert([
                'id' => 1,
                'enabled' => 0,
                'provider' => 'hubspot',
                'sync_contacts' => 1,
                'sync_marketing_consent' => 1,
                'website_tracking' => 0,
                'live_chat' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (! Schema::hasTable('crm_leads')) {
            Schema::create('crm_leads', function (Blueprint $table) {
                $table->id();
                $table->string('email', 255);
                $table->string('name', 255)->nullable();
                $table->string('phone', 40)->nullable();
                $table->text('message')->nullable();
                $table->string('source', 40);
                $table->boolean('marketing_consent')->default(false);
                $table->timestamp('marketing_consent_at')->nullable();
                $table->string('marketing_consent_source', 40)->nullable();
                $table->string('marketing_consent_text_version', 32)->nullable();
                $table->unsignedBigInteger('manager_id')->nullable();
                $table->string('crm_external_id', 128)->nullable();
                $table->string('sync_status', 32)->default('pending');
                $table->text('sync_error')->nullable();
                $table->timestamp('synced_at')->nullable();
                $table->timestamps();
                $table->index(['email']);
                $table->index(['source']);
                $table->index(['sync_status']);
            });
        }

        if (! Schema::hasTable('crm_consent_events')) {
            Schema::create('crm_consent_events', function (Blueprint $table) {
                $table->id();
                $table->string('email', 255);
                $table->boolean('marketing_consent');
                $table->string('source', 40);
                $table->string('text_version', 32);
                $table->unsignedBigInteger('manager_id')->nullable();
                $table->unsignedBigInteger('crm_lead_id')->nullable();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->timestamp('created_at')->useCurrent();
                $table->index(['email']);
                $table->index(['source']);
            });
        }

        if (! Schema::hasTable('crm_sync_logs')) {
            Schema::create('crm_sync_logs', function (Blueprint $table) {
                $table->id();
                $table->string('provider', 40);
                $table->string('action', 64);
                $table->string('email', 255)->nullable();
                $table->unsignedBigInteger('crm_lead_id')->nullable();
                $table->string('status', 32);
                $table->text('message')->nullable();
                $table->json('payload')->nullable();
                $table->timestamp('created_at')->useCurrent();
                $table->index(['status']);
                $table->index(['provider']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('crm_sync_logs');
        Schema::dropIfExists('crm_consent_events');
        Schema::dropIfExists('crm_leads');
        Schema::dropIfExists('crm_settings');

        Schema::table('admins', function (Blueprint $table) {
            if (Schema::hasColumn('admins', 'is_super_admin')) {
                $table->dropColumn('is_super_admin');
            }
        });

        Schema::table('managers', function (Blueprint $table) {
            foreach ([
                'phone',
                'email_verified_at',
                'marketing_consent',
                'marketing_consent_at',
                'marketing_consent_source',
                'marketing_consent_text_version',
            ] as $col) {
                if (Schema::hasColumn('managers', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
