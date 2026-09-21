<?php

namespace App\Console\Commands;

use App\Models\Admin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class PromoteSuperAdminCommand extends Command
{
    protected $signature = 'admins:promote-super {username : Admin username to promote}';

    protected $description = 'Promote a single admin to Super Admin (CRM access). Does not demote others.';

    public function handle(): int
    {
        if (! Schema::hasColumn('admins', 'is_super_admin')) {
            $this->error('admins.is_super_admin column is missing. Run migrations first.');

            return self::FAILURE;
        }

        $username = trim((string) $this->argument('username'));
        $admin = Admin::query()->where('username', $username)->first();
        if (! $admin) {
            $this->error('No admin found with that username.');

            return self::FAILURE;
        }

        $admin->forceFill(['is_super_admin' => true])->save();
        $this->info('Promoted Super Admin: '.$admin->username);

        return self::SUCCESS;
    }
}
