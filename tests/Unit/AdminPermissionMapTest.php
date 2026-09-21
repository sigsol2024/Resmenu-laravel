<?php

namespace Tests\Unit;

use App\Models\Admin;
use PHPUnit\Framework\TestCase;

class AdminPermissionMapTest extends TestCase
{
    public function test_permission_map_covers_locked_keys_only(): void
    {
        $expected = [
            'subscription_plans' => 'can_subscription_plans',
            'subscriptions' => 'can_subscriptions',
            'payments' => 'can_payments',
            'payment_settings' => 'can_payment_settings',
            'templates' => 'can_templates',
            'qr_templates' => 'can_qr_templates',
            'settings' => 'can_settings',
            'crm' => 'can_crm',
        ];

        $this->assertSame($expected, Admin::PERMISSION_MAP);
        $this->assertSame(array_keys($expected), Admin::permissionKeys());
    }

    public function test_privileged_fields_are_not_fillable(): void
    {
        $admin = new Admin;
        $fillable = $admin->getFillable();

        foreach ([
            'is_super_admin',
            'is_active',
            'can_subscription_plans',
            'can_subscriptions',
            'can_payments',
            'can_payment_settings',
            'can_templates',
            'can_qr_templates',
            'can_settings',
            'can_crm',
        ] as $field) {
            $this->assertNotContains($field, $fillable);
        }

        $this->assertSame(['username', 'email', 'password_hash'], $fillable);
    }
}
