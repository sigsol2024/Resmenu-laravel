<?php

namespace Tests\Unit;

use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class ExpiredCsrfTokenHandlingTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (! config('app.key')) {
            config(['app.key' => 'base64:'.base64_encode(random_bytes(32))]);
        }

        $throw = fn () => throw new TokenMismatchException('CSRF token mismatch.');

        Route::middleware('web')->post('/manager/__expired-token-test', $throw);
        Route::middleware('web')->post('/admin/__expired-token-test', $throw);
        Route::middleware('web')->post('/restaurant/demo/__expired-token-test', $throw);
    }

    public function test_manager_area_redirects_to_login_instead_of_419_page(): void
    {
        $response = $this->post('/manager/__expired-token-test');

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error', 'Your session has expired. Please log in again.');
    }

    public function test_admin_area_redirects_to_login_instead_of_419_page(): void
    {
        $response = $this->post('/admin/__expired-token-test');

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error');
    }

    public function test_public_form_goes_back_with_input_and_message(): void
    {
        $response = $this
            ->from('/restaurant/demo/reservation')
            ->post('/restaurant/demo/__expired-token-test', [
                'guest_name' => 'Ada',
                'guest_email' => 'ada@example.com',
            ]);

        $response->assertRedirect('/restaurant/demo/reservation');
        $response->assertSessionHasErrors('session');
        $response->assertSessionHasInput('guest_name', 'Ada');
    }

    public function test_json_request_gets_419_json_with_login_redirect(): void
    {
        $response = $this->postJson('/manager/__expired-token-test');

        $response->assertStatus(419);
        $response->assertJson(['redirect' => route('login')]);
    }
}
