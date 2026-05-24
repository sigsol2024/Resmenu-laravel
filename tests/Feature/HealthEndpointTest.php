<?php

namespace Tests\Feature;

use Tests\TestCase;

class HealthEndpointTest extends TestCase
{
    public function test_health_endpoint_returns_json(): void
    {
        $response = $this->get('/health');

        $response->assertOk();
        $response->assertJsonStructure(['status', 'db']);
        $response->assertJsonMissing(['env', 'upload_root', 'templates']);
    }
}
