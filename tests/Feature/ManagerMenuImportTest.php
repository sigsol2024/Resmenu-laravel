<?php

namespace Tests\Feature;

use App\Http\Middleware\EnsureActiveSubscription;
use App\Models\Manager;
use App\Services\ManagerFeatureAccess;
use App\Services\MenuImport\MenuImportDraftStore;
use App\Services\MenuImport\MenuImportFormat;
use App\Services\PlanVisibilityService;
use App\Services\SubscriptionService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Mockery;
use Tests\TestCase;
use Tests\Unit\MenuImport\MenuImportDatabase;

/**
 * HTTP-level tests for the menu import. They run on in-memory SQLite with the
 * real auth, tenant and email-verification middleware; only the subscription
 * gate is bypassed because it is not what these tests are about.
 */
class ManagerMenuImportTest extends TestCase
{
    use MenuImportDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['app.key' => 'base64:'.base64_encode(random_bytes(32))]);
        $this->setUpMenuDatabase();

        Schema::table('restaurants', function (Blueprint $t) {
            $t->timestamp('suspended_at')->nullable();
            $t->boolean('is_active')->default(true);
        });
        Schema::create('managers', function (Blueprint $t) {
            $t->increments('id');
            $t->integer('restaurant_id');
            $t->string('username');
            $t->string('email');
            $t->string('password_hash')->nullable();
            $t->string('remember_token')->nullable();
            $t->timestamp('email_verified_at')->nullable();
            $t->timestamps();
        });
        DB::table('managers')->insert([
            ['id' => 1, 'restaurant_id' => 1, 'username' => 'owner', 'email' => 'owner@example.com', 'email_verified_at' => now()],
            ['id' => 2, 'restaurant_id' => 2, 'username' => 'other', 'email' => 'other@example.com', 'email_verified_at' => now()],
            ['id' => 3, 'restaurant_id' => 1, 'username' => 'unverified', 'email' => 'new@example.com', 'email_verified_at' => null],
        ]);

        $subscriptions = Mockery::mock(SubscriptionService::class)->makePartial();
        $subscriptions->shouldReceive('getRemainingUsage')
            ->andReturn(['used' => 0, 'limit' => 'unlimited', 'remaining' => 'unlimited', 'unlimited' => true]);
        $subscriptions->shouldReceive('getRestaurantSubscription')->andReturn(null);
        $this->app->instance(SubscriptionService::class, $subscriptions);
        $features = Mockery::mock(ManagerFeatureAccess::class);
        $features->shouldReceive('foodOrderingUsable', 'tableReservationsUsable', 'planHasFoodOrdering', 'planHasTableReservations')->andReturn(false);
        $this->app->instance(ManagerFeatureAccess::class, $features);
        $visibility = Mockery::mock(PlanVisibilityService::class);
        $visibility->shouldReceive('forgetCache');
        $this->app->instance(PlanVisibilityService::class, $visibility);

        $this->withoutMiddleware(EnsureActiveSubscription::class);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_guests_are_sent_to_login(): void
    {
        $this->post(route('manager.menu-import.upload'))->assertRedirect(route('login'));
        $this->get(route('manager.menu-import.sample'))->assertRedirect(route('login'));
    }

    public function test_unverified_manager_is_blocked(): void
    {
        $this->actingAs($this->manager(3), 'manager')
            ->post(route('manager.menu-import.upload'), ['file' => $this->sampleUpload()])
            ->assertRedirect(route('manager.dashboard'));

        $this->actingAs($this->manager(3), 'manager')
            ->postJson(route('manager.menu-import.analyze', str_repeat('a', 40)), ['rows' => []])
            ->assertForbidden();

        $this->assertSame(0, DB::table('sections')->count());

        // Reading the blank template is not a menu write; it must still download as CSV.
        $this->actingAs($this->manager(3), 'manager')
            ->get(route('manager.menu-import.template'))
            ->assertOk()
            ->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    public function test_sample_and_template_downloads(): void
    {
        $sample = $this->actingAs($this->manager(), 'manager')->get(route('manager.menu-import.sample'));
        $sample->assertOk();
        $this->assertSame(MenuImportFormat::sampleCsv(), $sample->getContent());
        $this->assertStringContainsString('text/csv', $sample->headers->get('Content-Type'));
        $this->assertStringContainsString('attachment; filename="resmenu-menu-import-sample.csv"', $sample->headers->get('Content-Disposition'));

        $template = $this->actingAs($this->manager(), 'manager')->get(route('manager.menu-import.template'));
        $this->assertSame(MenuImportFormat::templateCsv(), $template->getContent());
    }

    public function test_empty_sections_page_offers_both_options_and_shows_the_sample(): void
    {
        $response = $this->actingAs($this->manager(), 'manager')->get(route('manager.sections.index'));

        $response->assertOk()
            ->assertSee('Create your first section')
            ->assertSee('Import your menu')
            ->assertSee('Download sample CSV')
            ->assertSee('Download blank template')
            ->assertSee(route('manager.menu-import.sample'), false)
            ->assertSeeInOrder(MenuImportFormat::HEADERS);
        foreach (MenuImportFormat::SAMPLE_ROWS as $row) {
            $response->assertSeeInOrder($row);
        }
        $response->assertDontSee('mimp-modal is-open', false);
    }

    public function test_upload_errors_reopen_the_modal_on_the_originating_page(): void
    {
        $this->actingAs($this->manager(), 'manager')
            ->from(route('manager.sections.index'))
            ->followingRedirects()
            ->post(route('manager.menu-import.upload'), ['file' => UploadedFile::fake()->createWithContent('menu.csv', "Name,Cost\nWings,1\n")])
            ->assertOk()
            ->assertSee('mimp-modal is-open', false)
            ->assertSee('Missing required columns: Section, Category, Menu Item, Description, Price')
            ->assertDontSee('id="sectionModal" style="display: flex;', false);
    }

    public function test_upload_creates_a_draft_and_writes_nothing(): void
    {
        $response = $this->actingAs($this->manager(), 'manager')
            ->post(route('manager.menu-import.upload'), ['file' => $this->sampleUpload()]);

        $token = $this->tokenFrom($response->headers->get('Location'));
        $this->assertCount(4, app(MenuImportDraftStore::class)->get(1, 1, $token)['rows']);
        $this->assertSame([0, 0, 0], $this->counts());
    }

    public function test_invalid_upload_returns_to_the_modal_with_errors(): void
    {
        $this->actingAs($this->manager(), 'manager')
            ->from(route('manager.sections.index'))
            ->post(route('manager.menu-import.upload'), ['file' => UploadedFile::fake()->createWithContent('menu.xlsx', 'x')])
            ->assertRedirect(route('manager.sections.index'))
            ->assertSessionHasErrors(['0'], null, 'menuImport')
            ->assertSessionHas('menu_import_open', true);

        $this->actingAs($this->manager(), 'manager')
            ->from(route('manager.sections.index'))
            ->post(route('manager.menu-import.upload'), [])
            ->assertSessionHasErrors(['0'], null, 'menuImport');
    }

    public function test_preview_page_renders_the_draft(): void
    {
        $token = $this->upload();

        $this->actingAs($this->manager(), 'manager')
            ->get(route('manager.menu-import.preview', $token))
            ->assertOk()
            ->assertSee('Verify &amp; Import', false)
            ->assertSee('menu.csv')
            ->assertSee('Chicken Wings');
    }

    public function test_analyze_applies_edits_but_ignores_ids_and_unknown_rows(): void
    {
        $token = $this->upload();
        $rows = app(MenuImportDraftStore::class)->get(1, 1, $token)['rows'];
        $rows[0]['price'] = '9999';
        $rows[0]['restaurant_id'] = 2;
        $rows[0]['existing_id'] = 123;
        $rows[0]['row_number'] = 999;
        $rows[] = ['id' => 'r77', 'section' => 'Injected', 'category' => 'X', 'name' => 'Y', 'price' => '1'];

        $response = $this->actingAs($this->manager(), 'manager')
            ->postJson(route('manager.menu-import.analyze', $token), ['rows' => $rows])
            ->assertOk()
            ->assertJsonPath('rows.r2.price', '9999')
            ->assertJsonPath('rows.r2.row_number', 2)
            ->assertJsonPath('summary.items.new', 4)
            ->assertJsonMissingPath('plan');

        $this->assertArrayNotHasKey('r77', $response->json('rows'));
        $stored = app(MenuImportDraftStore::class)->get(1, 1, $token)['rows'];
        $this->assertSame('9999', $stored[0]['price']);
        $this->assertArrayNotHasKey('restaurant_id', $stored[0]);
    }

    public function test_confirmed_import_writes_to_the_managers_restaurant_only(): void
    {
        $token = $this->upload();
        $revision = $this->analyzeRevision($token);

        $this->actingAs($this->manager(), 'manager')
            ->postJson(route('manager.menu-import.import', $token), ['revision' => $revision])
            ->assertOk()
            ->assertJsonPath('redirect', route('manager.menu-import.result'));

        $this->assertSame([2, 3, 4], $this->counts());
        $this->assertSame(0, DB::table('menu_items')->where('restaurant_id', '!=', 1)->count());
        $this->assertNull(app(MenuImportDraftStore::class)->get(1, 1, $token));

        $this->actingAs($this->manager(), 'manager')
            ->get(route('manager.menu-import.result'))
            ->assertOk()
            ->assertSee('Import complete');
    }

    public function test_another_restaurants_manager_cannot_use_the_token(): void
    {
        $token = $this->upload();
        $revision = $this->analyzeRevision($token);
        $other = $this->manager(2);

        $this->actingAs($other, 'manager')->get(route('manager.menu-import.preview', $token))->assertNotFound();
        $this->actingAs($other, 'manager')->postJson(route('manager.menu-import.analyze', $token), ['rows' => []])->assertStatus(410);
        $this->actingAs($other, 'manager')->postJson(route('manager.menu-import.import', $token), ['revision' => $revision])->assertStatus(410);

        $this->assertSame([0, 0, 0], $this->counts());
        $this->assertNotNull(app(MenuImportDraftStore::class)->get(1, 1, $token));
    }

    public function test_stale_revision_returns_409_with_the_new_analysis(): void
    {
        $token = $this->upload();
        $revision = $this->analyzeRevision($token);
        $this->makeSection('Food');

        $this->actingAs($this->manager(), 'manager')
            ->postJson(route('manager.menu-import.import', $token), ['revision' => $revision])
            ->assertStatus(409)
            ->assertJsonPath('analysis.summary.sections.existing', 1);

        $this->assertSame(0, DB::table('menu_items')->count());
    }

    public function test_reimporting_the_same_file_changes_nothing(): void
    {
        $first = $this->upload();
        $this->actingAs($this->manager(), 'manager')
            ->postJson(route('manager.menu-import.import', $first), ['revision' => $this->analyzeRevision($first)])
            ->assertOk();

        $second = $this->upload();
        $this->actingAs($this->manager(), 'manager')
            ->postJson(route('manager.menu-import.import', $second), ['revision' => $this->analyzeRevision($second)])
            ->assertStatus(422)
            ->assertJsonPath('message', 'There is nothing to import: every item already exists with the same details.');

        $this->assertSame([2, 3, 4], $this->counts());
    }

    public function test_oversized_analyze_payload_is_rejected_and_the_draft_is_unchanged(): void
    {
        $token = $this->upload();
        config(['resmenu.menu_import.max_file_kb' => 1]);
        $rows = app(MenuImportDraftStore::class)->get(1, 1, $token)['rows'];
        $huge = [];
        for ($i = 0; $i < 12; $i++) {
            $huge[] = ['id' => $rows[0]['id'], 'description' => str_repeat('d', 60000)] + $rows[0];
        }

        $this->actingAs($this->manager(), 'manager')
            ->postJson(route('manager.menu-import.analyze', $token), ['rows' => $huge])
            ->assertStatus(413);

        $this->assertSame($rows, app(MenuImportDraftStore::class)->get(1, 1, $token)['rows']);
    }

    public function test_frequent_analyze_calls_do_not_use_up_the_import_rate_limit(): void
    {
        $token = $this->upload();
        $rows = app(MenuImportDraftStore::class)->get(1, 1, $token)['rows'];
        for ($i = 0; $i < 35; $i++) {
            $revision = $this->actingAs($this->manager(), 'manager')
                ->postJson(route('manager.menu-import.analyze', $token), ['rows' => $rows])
                ->assertOk()
                ->json('revision');
        }

        $this->actingAs($this->manager(), 'manager')
            ->postJson(route('manager.menu-import.import', $token), ['revision' => $revision])
            ->assertOk();
    }

    public function test_import_routes_keep_their_own_rate_limit(): void
    {
        $token = $this->upload();
        for ($i = 0; $i < 30; $i++) {
            $this->actingAs($this->manager(), 'manager')->get(route('manager.menu-import.template'))->assertOk();
        }

        $this->actingAs($this->manager(), 'manager')->get(route('manager.menu-import.template'))->assertStatus(429);
        $this->actingAs($this->manager(), 'manager')
            ->postJson(route('manager.menu-import.analyze', $token), ['rows' => []])
            ->assertOk();
    }

    public function test_cancel_discards_the_draft(): void
    {
        $token = $this->upload();

        $this->actingAs($this->manager(), 'manager')
            ->delete(route('manager.menu-import.cancel', $token))
            ->assertRedirect(route('manager.sections.index'));

        $this->assertNull(app(MenuImportDraftStore::class)->get(1, 1, $token));
    }

    private function upload(): string
    {
        $response = $this->actingAs($this->manager(), 'manager')
            ->post(route('manager.menu-import.upload'), ['file' => $this->sampleUpload()]);

        return $this->tokenFrom($response->headers->get('Location'));
    }

    private function analyzeRevision(string $token): string
    {
        $rows = app(MenuImportDraftStore::class)->get(1, 1, $token)['rows'];

        return $this->actingAs($this->manager(), 'manager')
            ->postJson(route('manager.menu-import.analyze', $token), ['rows' => $rows])
            ->json('revision');
    }

    private function tokenFrom(?string $location): string
    {
        $this->assertNotNull($location);
        $this->assertMatchesRegularExpression('#/manager/menu-import/([A-Za-z0-9]{40})$#', $location);

        return substr($location, -40);
    }

    private function sampleUpload(): UploadedFile
    {
        return UploadedFile::fake()->createWithContent('menu.csv', MenuImportFormat::sampleCsv());
    }

    private function manager(int $id = 1): Manager
    {
        return Manager::findOrFail($id);
    }

    /** @return list<int> */
    private function counts(): array
    {
        return [DB::table('sections')->count(), DB::table('categories')->count(), DB::table('menu_items')->count()];
    }
}
