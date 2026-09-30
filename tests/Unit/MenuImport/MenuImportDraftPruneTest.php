<?php

namespace Tests\Unit\MenuImport;

use App\Services\MenuImport\MenuImportDraftStore;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class MenuImportDraftPruneTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config([
            'database.default' => 'menu_import_prune',
            'database.connections.menu_import_prune' => ['driver' => 'sqlite', 'database' => ':memory:', 'prefix' => ''],
            'cache.default' => 'database',
            'cache.stores.database.connection' => 'menu_import_prune',
            'cache.stores.database.lock_connection' => 'menu_import_prune',
        ]);
        DB::purge('menu_import_prune');
        app('cache')->forgetDriver('database');

        Schema::create('cache', function (Blueprint $t) {
            $t->string('key')->primary();
            $t->mediumText('value');
            $t->integer('expiration');
        });
    }

    public function test_expired_drafts_are_deleted_and_everything_else_is_kept(): void
    {
        config(['resmenu.menu_import.draft_ttl_minutes' => 120]);
        $store = new MenuImportDraftStore;
        $abandoned = $store->create(1, 10, ['rows' => [['id' => 'r2']]]);
        Cache::put('unrelated', 'value', now()->addMinute());

        $this->travel(3)->hours();
        $live = $store->create(1, 11, ['rows' => [['id' => 'r2']]]);

        $this->artisan('menu-import:prune-drafts')->assertSuccessful();

        $prefix = Cache::store('database')->getStore()->getPrefix();
        $this->assertSame(0, DB::table('cache')->where('key', 'like', $prefix.'menu_import:%:10%')->count());
        $this->assertSame(0, DB::table('cache')->where('key', 'like', '%'.$abandoned)->count());
        $this->assertNotNull($store->get(1, 11, $live));
        $this->assertTrue(DB::table('cache')->where('key', $prefix.'unrelated')->exists());
    }

    public function test_other_cache_stores_are_left_alone(): void
    {
        config(['cache.default' => 'array']);

        $this->artisan('menu-import:prune-drafts')
            ->expectsOutputToContain('nothing to prune')
            ->assertSuccessful();
    }
}
