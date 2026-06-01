<?php

namespace Tests\Unit;

use App\Support\SafeRedirect;
use Tests\TestCase;

class SafeRedirectTest extends TestCase
{
    public function test_legacy_manager_billing_php_maps_to_laravel_path(): void
    {
        $this->assertSame(
            '/manager/billing',
            SafeRedirect::localPath('/manager/billing.php'),
        );
    }

    public function test_legacy_path_preserves_query_string(): void
    {
        $this->assertSame(
            '/manager/billing?upgrade_required=1',
            SafeRedirect::localPath('/manager/billing.php?upgrade_required=1'),
        );
    }

    public function test_modern_paths_unchanged(): void
    {
        $this->assertSame('/manager/billing', SafeRedirect::localPath('/manager/billing'));
    }
}
