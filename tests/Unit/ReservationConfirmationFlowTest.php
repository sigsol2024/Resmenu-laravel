<?php

namespace Tests\Unit;

use App\Http\Controllers\Public\OrderPaymentCallbackController;
use App\Http\Controllers\Public\ReservationConfirmationController;
use App\Models\TableReservation;
use App\Services\BankTransferService;
use App\Services\CustomizationService;
use App\Services\MailService;
use App\Services\ManagerFeatureAccess;
use App\Services\OrderSubmissionService;
use App\Services\PendingOnlinePaymentService;
use App\Services\ReservationBookingService;
use App\Services\RestaurantPaymentVerificationService;
use App\Services\RestaurantTransactionalMailService;
use App\Services\SubscriptionService;
use App\Support\ReservationConfirmationAccess;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Mockery;
use Tests\TestCase;

class ReservationConfirmationFlowTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'app.key' => 'base64:'.base64_encode(random_bytes(32)),
            'resmenu.app_hmac_secret' => 'test-hmac-secret',
            'database.default' => 'reservation_flow_sqlite',
            'database.connections.reservation_flow_sqlite' => [
                'driver' => 'sqlite',
                'database' => ':memory:',
                'prefix' => '',
                'foreign_key_constraints' => false,
            ],
        ]);
        DB::purge('reservation_flow_sqlite');
        Cache::flush();

        $this->createSchema();

        $request = Request::create('/');
        $request->setLaravelSession($this->app['session']->driver('array'));
        $this->app->instance('request', $request);

        DB::table('restaurants')->insert(['id' => 1, 'name' => 'Test Bistro', 'slug' => 'test-bistro']);
        DB::table('managers')->insert(['id' => 1, 'restaurant_id' => 1, 'username' => 'boss', 'email' => 'manager@example.com']);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_booking_with_deposit_sends_no_email_until_paid(): void
    {
        DB::table('restaurant_reservation_settings')->insert(['restaurant_id' => 1, 'deposit_amount' => 5000]);

        $mail = Mockery::mock(RestaurantTransactionalMailService::class);
        $mail->shouldNotReceive('sendReservationCreated');

        $result = $this->bookingService($mail)->create(1, $this->bookingData());

        $this->assertTrue($result['success']);
        $this->assertNotEmpty($result['checkout_url']);
        $this->assertArrayNotHasKey('confirmation_url', $result);
        $this->assertFalse((bool) TableReservation::find($result['reservation_id'])->deposit_paid);
    }

    public function test_booking_without_deposit_emails_and_returns_confirmation_url(): void
    {
        $mail = Mockery::mock(RestaurantTransactionalMailService::class);
        $mail->shouldReceive('sendReservationCreated')->once();

        $result = $this->bookingService($mail)->create(1, $this->bookingData());

        $this->assertTrue($result['success']);
        $this->assertStringContainsString('/reservations/'.$result['reservation_id'].'/confirmation', $result['confirmation_url']);
        $this->assertStringContainsString('sig=', $result['confirmation_url']);
    }

    public function test_guest_email_is_suppressed_while_deposit_is_outstanding(): void
    {
        $id = $this->reservation(['deposit_amount' => 5000, 'deposit_paid' => false]);
        $sent = $this->captureTransactionalMail(fn (RestaurantTransactionalMailService $s) => $s->sendReservationCreated($id, 1));

        $this->assertSame(['manager@example.com'], array_column($sent, 'to'));
    }

    public function test_guest_email_after_deposit_paid_says_team_will_get_back(): void
    {
        $id = $this->reservation(['deposit_amount' => 5000, 'deposit_paid' => true, 'status' => 'confirmed']);
        $sent = $this->captureTransactionalMail(fn (RestaurantTransactionalMailService $s) => $s->sendReservationCreated($id, 1, notifyManager: false));

        $this->assertCount(1, $sent);
        $this->assertSame('guest@example.com', $sent[0]['to']);
        $this->assertStringContainsString('our team will get back to you', $sent[0]['html']);
        $this->assertStringContainsString('deposit of ₦5,000.00 has been received', $sent[0]['html']);
    }

    public function test_gateway_fulfilment_marks_paid_then_emails_once(): void
    {
        $id = $this->reservation(['deposit_amount' => 5000]);
        DB::table('pending_online_payments')->insert($this->onlineDraft('POP_1', $id));

        $mail = Mockery::mock(RestaurantTransactionalMailService::class);
        $mail->shouldReceive('sendReservationCreated')->once()->with($id, 1)
            ->andReturnUsing(function () use ($id) {
                $this->assertTrue((bool) TableReservation::find($id)->deposit_paid, 'Email must only be sent after payment is recorded');
            });

        $service = new PendingOnlinePaymentService(Mockery::mock(OrderSubmissionService::class), $mail);

        $first = $service->fulfillFromWebhook('POP_1', 'paystack');
        $second = $service->fulfillFromWebhook('POP_1', 'paystack');

        $this->assertSame('reservation', $first['type']);
        $this->assertSame($id, $first['reservation_id']);
        $this->assertTrue($second['already_processed']);
        $this->assertSame($id, $service->fulfilledPayload('paystack', 'POP_1')['reservation_id']);
    }

    public function test_bank_transfer_claim_does_not_email_guest_and_redirects_to_confirmation(): void
    {
        $id = $this->reservation(['deposit_amount' => 5000]);
        DB::table('pending_bank_transfers')->insert($this->bankDraft('tok123', $id));

        $transactional = Mockery::mock(RestaurantTransactionalMailService::class);
        $transactional->shouldNotReceive('sendReservationCreated');
        $transactional->shouldReceive('sendBankTransferClaimed')->once()
            ->with(Mockery::on(fn ($draft) => $draft->token === 'tok123'));

        $service = new BankTransferService(Mockery::mock(OrderSubmissionService::class), $transactional);
        $result = $service->customerClaimPayment('tok123');

        $this->assertTrue($result['success']);
        $this->assertStringContainsString('/reservations/'.$id.'/confirmation', (string) $result['redirect']);
        $this->assertFalse((bool) TableReservation::find($id)->deposit_paid);
    }

    public function test_bank_transfer_approval_emails_guest_after_marking_paid(): void
    {
        $id = $this->reservation(['deposit_amount' => 5000]);
        DB::table('pending_bank_transfers')->insert($this->bankDraft('tok456', $id, 'customer_claimed'));
        $draftId = (int) DB::table('pending_bank_transfers')->where('token', 'tok456')->value('id');

        $transactional = Mockery::mock(RestaurantTransactionalMailService::class);
        $transactional->shouldReceive('sendReservationCreated')->once()->with($id, 1, false)
            ->andReturnUsing(function () use ($id) {
                $this->assertTrue((bool) TableReservation::find($id)->deposit_paid);
            });

        $service = new BankTransferService(Mockery::mock(OrderSubmissionService::class), $transactional);
        $result = $service->managerApprove($draftId, 1, 1);

        $this->assertTrue($result['success']);
        $this->assertSame('approved', DB::table('pending_bank_transfers')->where('id', $draftId)->value('status'));
    }

    public function test_callback_race_uses_cached_fulfilment_instead_of_menu(): void
    {
        $id = $this->reservation(['deposit_amount' => 5000, 'deposit_paid' => true]);

        $verification = Mockery::mock(RestaurantPaymentVerificationService::class);
        $verification->shouldReceive('verifyCallbackPayment')->andReturn(['ok' => false, 'error' => 'replay_throttled']);
        $pending = Mockery::mock(PendingOnlinePaymentService::class);
        $pending->shouldReceive('fulfilledPayload')->andReturn(['type' => 'reservation', 'slug' => 'test-bistro', 'reservation_id' => $id]);

        $response = (new OrderPaymentCallbackController)(
            Request::create('/order-payment/callback/paystack', 'GET', ['reference' => 'POP_9', 'slug' => 'test-bistro']),
            $verification,
            $pending,
            'paystack',
        );

        $this->assertStringContainsString('/reservations/'.$id.'/confirmation', $response->getTargetUrl());
    }

    public function test_callback_unconfirmed_payment_shows_pending_page_only_to_booking_session(): void
    {
        $id = $this->reservation(['deposit_amount' => 5000]);

        $verification = Mockery::mock(RestaurantPaymentVerificationService::class);
        $verification->shouldReceive('verifyCallbackPayment')->andReturn(['ok' => false, 'error' => 'verify_http_failed']);
        $pending = Mockery::mock(PendingOnlinePaymentService::class);
        $pending->shouldReceive('fulfilledPayload')->andReturn(null);
        $pending->shouldReceive('reservationIdForReference')->andReturn($id);

        $call = fn () => (new OrderPaymentCallbackController)(
            Request::create('/order-payment/callback/paystack', 'GET', ['reference' => 'POP_8', 'slug' => 'test-bistro']),
            $verification,
            $pending,
            'paystack',
        );

        $this->assertSame(route('public.menu', 'test-bistro'), $call()->getTargetUrl());

        ReservationConfirmationAccess::grant($id);
        $this->assertSame(route('public.reservation.confirmation', ['reservation' => $id]), $call()->getTargetUrl());
    }

    public function test_callback_cancelled_payment_returns_guest_to_deposit_checkout(): void
    {
        $id = $this->reservation(['deposit_amount' => 5000]);

        $verification = Mockery::mock(RestaurantPaymentVerificationService::class);
        $verification->shouldReceive('verifyCallbackPayment')->andReturn(['ok' => false, 'error' => 'payment_not_successful']);
        $pending = Mockery::mock(PendingOnlinePaymentService::class);
        $pending->shouldReceive('fulfilledPayload')->andReturn(null);
        $pending->shouldReceive('reservationIdForReference')->andReturn($id);
        $pending->shouldNotReceive('discardFailed');

        $response = (new OrderPaymentCallbackController)(
            Request::create('/order-payment/callback/paystack', 'GET', ['reference' => 'POP_7', 'slug' => 'test-bistro']),
            $verification,
            $pending,
            'paystack',
        );

        $this->assertSame(route('public.checkout', ['slug' => 'test-bistro', 'reservation_id' => $id]), $response->getTargetUrl());
    }

    public function test_manager_can_approve_claimed_transfer_after_customer_window(): void
    {
        $id = $this->reservation(['deposit_amount' => 5000]);
        DB::table('pending_bank_transfers')->insert(array_merge($this->bankDraft('tok789', $id, 'customer_claimed'), ['created_at' => now()->subHours(3)]));
        $draftId = (int) DB::table('pending_bank_transfers')->where('token', 'tok789')->value('id');

        $transactional = Mockery::mock(RestaurantTransactionalMailService::class);
        $transactional->shouldReceive('sendReservationCreated')->once();
        $service = new BankTransferService(Mockery::mock(OrderSubmissionService::class), $transactional);

        $this->assertFalse($service->expireDraft('tok789'), 'A claimed transfer must not be expired by the guest countdown');
        $this->assertTrue($service->managerApprove($draftId, 1, 1)['success']);
        $this->assertTrue((bool) TableReservation::find($id)->deposit_paid);
    }

    public function test_unclaimed_transfer_still_expires_after_window(): void
    {
        $id = $this->reservation(['deposit_amount' => 5000]);
        DB::table('pending_bank_transfers')->insert(array_merge($this->bankDraft('tok000', $id), ['created_at' => now()->subHours(3)]));
        $draftId = (int) DB::table('pending_bank_transfers')->where('token', 'tok000')->value('id');

        $transactional = Mockery::mock(RestaurantTransactionalMailService::class);
        $transactional->shouldNotReceive('sendReservationCreated');
        $service = new BankTransferService(Mockery::mock(OrderSubmissionService::class), $transactional);

        $this->assertFalse($service->managerApprove($draftId, 1, 1)['success']);
        $this->assertFalse((bool) TableReservation::find($id)->deposit_paid);
    }

    public function test_rejecting_reservation_transfer_emails_guest_and_keeps_booking_pending(): void
    {
        $id = $this->reservation(['deposit_amount' => 5000]);
        DB::table('pending_bank_transfers')->insert($this->bankDraft('tokRej', $id, 'customer_claimed'));
        $draftId = (int) DB::table('pending_bank_transfers')->where('token', 'tokRej')->value('id');

        $transactional = Mockery::mock(RestaurantTransactionalMailService::class);
        $transactional->shouldReceive('sendReservationDepositRejected')->once()->with($id, 1);
        $service = new BankTransferService(Mockery::mock(OrderSubmissionService::class), $transactional);

        $this->assertTrue($service->managerReject($draftId, 1, 1));
        $this->assertSame('pending', TableReservation::find($id)->status);
        $this->assertFalse((bool) TableReservation::find($id)->deposit_paid);
    }

    public function test_deposit_rejected_email_contains_pay_again_link(): void
    {
        $id = $this->reservation(['deposit_amount' => 5000]);
        $sent = $this->captureTransactionalMail(fn (RestaurantTransactionalMailService $s) => $s->sendReservationDepositRejected($id, 1));

        $this->assertCount(1, $sent);
        $this->assertSame('guest@example.com', $sent[0]['to']);
        $this->assertStringContainsString('Action needed', $sent[0]['subject']);
        $this->assertStringContainsString('Pay deposit', $sent[0]['html']);
        $this->assertStringContainsString('/reservations/'.$id.'/confirmation', $sent[0]['html']);

        $paidId = $this->reservation(['deposit_amount' => 5000, 'deposit_paid' => true]);
        $this->assertSame([], $this->captureTransactionalMail(fn (RestaurantTransactionalMailService $s) => $s->sendReservationDepositRejected($paidId, 1)));
    }

    public function test_session_access_works_without_hmac_secret(): void
    {
        config(['resmenu.app_hmac_secret' => '']);

        $url = ReservationConfirmationAccess::url(77, 'test-bistro');

        $this->assertSame(route('public.reservation.confirmation', ['reservation' => 77]), $url);
        $this->assertTrue(ReservationConfirmationAccess::granted(77));
        $this->assertFalse(ReservationConfirmationAccess::granted(78));
    }

    public function test_confirmation_page_state_reflects_payment(): void
    {
        $this->assertSame('awaiting_payment', ReservationConfirmationController::stateFor(new TableReservation(['deposit_amount' => 5000, 'deposit_paid' => false, 'status' => 'pending'])));
        $this->assertSame('received', ReservationConfirmationController::stateFor(new TableReservation(['deposit_amount' => 5000, 'deposit_paid' => true, 'status' => 'confirmed'])));
        $this->assertSame('received', ReservationConfirmationController::stateFor(new TableReservation(['deposit_amount' => 0, 'deposit_paid' => false, 'status' => 'pending'])));
        $this->assertSame('closed', ReservationConfirmationController::stateFor(new TableReservation(['deposit_amount' => 0, 'status' => 'cancelled'])));
        $this->assertSame('deposit_due', ReservationConfirmationController::stateFor(new TableReservation(['deposit_amount' => 5000, 'deposit_paid' => false, 'status' => 'pending']), paymentInFlight: false));
    }

    public function test_confirmation_page_renders_copy_for_each_state(): void
    {
        $render = function (array $attrs) {
            $reservation = TableReservation::find($this->reservation($attrs));

            return view('public.reservation-confirmation', [
                'reservation' => $reservation,
                'restaurant' => $reservation->restaurant,
                'primaryColor' => '#111111',
                'state' => ReservationConfirmationController::stateFor($reservation),
            ])->render();
        };

        $paid = $render(['deposit_amount' => 5000, 'deposit_paid' => true, 'status' => 'confirmed']);
        $this->assertStringContainsString('received your reservation', $paid);
        $this->assertStringContainsString('Our team will get back to you', $paid);
        $this->assertStringContainsString('Deposit paid', $paid);

        $awaiting = $render(['deposit_amount' => 5000, 'deposit_paid' => false]);
        $this->assertStringContainsString('still being confirmed', $awaiting);
        $this->assertStringNotContainsString('A confirmation email has been sent', $awaiting);

        $this->assertStringContainsString('Reservation Cancelled', $render(['status' => 'cancelled']));

        $reservation = TableReservation::find($this->reservation(['deposit_amount' => 5000]));
        $due = view('public.reservation-confirmation', [
            'reservation' => $reservation,
            'restaurant' => $reservation->restaurant,
            'state' => 'deposit_due',
            'payDepositUrl' => 'https://example.test/pay',
        ])->render();
        $this->assertStringContainsString('Deposit still due', $due);
        $this->assertStringContainsString('https://example.test/pay', $due);
        $this->assertStringNotContainsString('window.location.reload', $due);
    }

    private function bookingService(RestaurantTransactionalMailService $mail): ReservationBookingService
    {
        $subscriptions = Mockery::mock(SubscriptionService::class);
        $subscriptions->shouldReceive('checkAccess')->andReturn(['valid' => true, 'message' => '']);
        $features = Mockery::mock(ManagerFeatureAccess::class);
        $features->shouldReceive('tableReservationsUsable')->andReturn(true);

        return new ReservationBookingService($subscriptions, $mail, $features);
    }

    /** @return list<array{to:string, subject:string, html:string}> */
    private function captureTransactionalMail(callable $send): array
    {
        $sent = [];
        $mail = Mockery::mock(MailService::class);
        $mail->shouldReceive('send')->andReturnUsing(function ($to, $name, $subject, $html) use (&$sent) {
            $sent[] = ['to' => $to, 'subject' => $subject, 'html' => $html];

            return true;
        });
        $customization = Mockery::mock(CustomizationService::class);
        $customization->shouldReceive('forRestaurant')->andReturn([]);

        $send(new RestaurantTransactionalMailService($mail, $customization));

        return $sent;
    }

    /** @return array<string, mixed> */
    private function bookingData(): array
    {
        return [
            'guest_name' => 'Ada Guest',
            'guest_email' => 'guest@example.com',
            'guest_phone' => '08012345678',
            'reservation_date' => now()->addDay()->toDateString(),
            'reservation_time' => '19:00',
            'party_size' => 2,
        ];
    }

    /** @param  array<string, mixed>  $overrides */
    private function reservation(array $overrides = []): int
    {
        return (int) TableReservation::create(array_merge([
            'restaurant_id' => 1,
            'reservation_number' => 'RES'.random_int(10000, 99999),
            'status' => 'pending',
            'guest_name' => 'Ada Guest',
            'guest_email' => 'guest@example.com',
            'guest_phone' => '08012345678',
            'reservation_date' => now()->addDay()->toDateString(),
            'reservation_time' => '19:00:00',
            'party_size' => 2,
            'deposit_amount' => 0,
            'deposit_paid' => false,
        ], $overrides))->id;
    }

    /** @return array<string, mixed> */
    private function onlineDraft(string $reference, int $reservationId): array
    {
        return [
            'reference' => $reference, 'restaurant_id' => 1, 'payment_type' => 'reservation', 'reservation_id' => $reservationId,
            'gateway' => 'paystack', 'cart_json' => '[]', 'customer_name' => 'Ada Guest', 'customer_phone' => '08012345678',
            'customer_email' => 'guest@example.com', 'delivery_address' => 'Reservation #'.$reservationId,
            'subtotal' => 5000, 'delivery_fee' => 0, 'tax' => 0, 'total' => 5000, 'created_at' => now(),
        ];
    }

    /** @return array<string, mixed> */
    private function bankDraft(string $token, int $reservationId, string $status = 'pending'): array
    {
        return [
            'token' => $token, 'restaurant_id' => 1, 'payment_type' => 'reservation', 'reservation_id' => $reservationId,
            'cart_json' => '[]', 'customer_name' => 'Ada Guest', 'customer_phone' => '08012345678',
            'customer_email' => 'guest@example.com', 'delivery_address' => 'Reservation #'.$reservationId,
            'subtotal' => 5000, 'delivery_fee' => 0, 'tax' => 0, 'total' => 5000, 'status' => $status, 'created_at' => now(),
        ];
    }

    private function createSchema(): void
    {
        Schema::create('restaurants', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('slug');
            $t->string('logo')->nullable();
            $t->timestamps();
        });
        Schema::create('managers', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('restaurant_id');
            $t->string('username')->nullable();
            $t->string('email');
            $t->timestamps();
        });
        Schema::create('restaurant_reservation_settings', function (Blueprint $t) {
            $t->unsignedBigInteger('restaurant_id');
            $t->decimal('deposit_amount', 10, 2)->default(0);
        });
        Schema::create('table_reservations', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('restaurant_id');
            $t->string('reservation_number')->nullable();
            $t->string('status')->default('pending');
            $t->string('guest_name');
            $t->string('guest_email');
            $t->string('guest_phone');
            $t->date('reservation_date');
            $t->string('reservation_time');
            $t->integer('party_size');
            $t->string('special_occasion')->nullable();
            $t->decimal('deposit_amount', 10, 2)->default(0);
            $t->boolean('deposit_paid')->default(false);
            $t->text('notes')->nullable();
            $t->boolean('is_walkin')->default(false);
            $t->timestamps();
        });
        Schema::create('pending_online_payments', function (Blueprint $t) {
            $t->id();
            $t->string('reference');
            $t->unsignedBigInteger('restaurant_id');
            $t->string('payment_type')->default('order');
            $t->unsignedBigInteger('reservation_id')->nullable();
            $t->string('gateway');
            $t->text('cart_json')->nullable();
            $t->string('customer_name')->nullable();
            $t->string('customer_phone')->nullable();
            $t->string('customer_email')->nullable();
            $t->string('delivery_address')->nullable();
            $t->decimal('subtotal', 10, 2)->default(0);
            $t->decimal('delivery_fee', 10, 2)->default(0);
            $t->decimal('tax', 10, 2)->default(0);
            $t->decimal('total', 10, 2)->default(0);
            $t->timestamp('created_at')->nullable();
        });
        Schema::create('pending_bank_transfers', function (Blueprint $t) {
            $t->id();
            $t->string('token');
            $t->unsignedBigInteger('restaurant_id');
            $t->string('payment_type')->default('order');
            $t->unsignedBigInteger('reservation_id')->nullable();
            $t->text('cart_json')->nullable();
            $t->string('customer_name')->nullable();
            $t->string('customer_phone')->nullable();
            $t->string('customer_email')->nullable();
            $t->string('delivery_address')->nullable();
            $t->decimal('subtotal', 10, 2)->default(0);
            $t->decimal('delivery_fee', 10, 2)->default(0);
            $t->decimal('tax', 10, 2)->default(0);
            $t->decimal('total', 10, 2)->default(0);
            $t->string('status')->default('pending');
            $t->timestamp('created_at')->nullable();
            $t->timestamp('customer_claimed_at')->nullable();
            $t->timestamp('approved_at')->nullable();
            $t->unsignedBigInteger('approved_by_manager_id')->nullable();
        });
    }
}
