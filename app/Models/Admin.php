<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Schema;

class Admin extends Authenticatable
{
    protected $table = 'admins';

    public $timestamps = true;

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    /** @var array<string, string> permission key → column */
    public const PERMISSION_MAP = [
        'subscription_plans' => 'can_subscription_plans',
        'subscriptions' => 'can_subscriptions',
        'payments' => 'can_payments',
        'payment_settings' => 'can_payment_settings',
        'templates' => 'can_templates',
        'qr_templates' => 'can_qr_templates',
        'settings' => 'can_settings',
        'crm' => 'can_crm',
    ];

    protected $fillable = ['username', 'email', 'password_hash'];

    protected $hidden = ['password_hash'];

    protected $casts = [
        'is_super_admin' => 'boolean',
        'is_active' => 'boolean',
        'can_subscription_plans' => 'boolean',
        'can_subscriptions' => 'boolean',
        'can_payments' => 'boolean',
        'can_payment_settings' => 'boolean',
        'can_templates' => 'boolean',
        'can_qr_templates' => 'boolean',
        'can_settings' => 'boolean',
        'can_crm' => 'boolean',
    ];

    public function getAuthPassword(): string
    {
        return (string) $this->password_hash;
    }

    public function isSuperAdmin(): bool
    {
        if (! Schema::hasColumn('admins', 'is_super_admin')) {
            return false;
        }

        return (bool) $this->is_super_admin;
    }

    public function isActive(): bool
    {
        if (! Schema::hasColumn('admins', 'is_active')) {
            return true;
        }

        return (bool) $this->is_active;
    }

    public function hasPermission(string $key): bool
    {
        if (! array_key_exists($key, self::PERMISSION_MAP)) {
            return false;
        }

        if ($this->isSuperAdmin()) {
            return true;
        }

        $column = self::PERMISSION_MAP[$key];

        if (! Schema::hasColumn('admins', $column)) {
            return false;
        }

        return (bool) $this->getAttribute($column);
    }

    /** @return list<string> */
    public static function permissionKeys(): array
    {
        return array_keys(self::PERMISSION_MAP);
    }
}
