<?php

namespace Tests\Unit\MenuImport;

use App\Services\MenuImport\MenuImportDraftStore;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class MenuImportDraftStoreTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['cache.default' => 'array']);
        Cache::flush();
    }

    public function test_draft_is_only_readable_by_the_same_restaurant_and_manager(): void
    {
        $store = new MenuImportDraftStore;
        $token = $store->create(1, 10, ['rows' => [['id' => 'r2']]]);

        $this->assertMatchesRegularExpression('/^[A-Za-z0-9]{40}$/', $token);
        $this->assertSame([['id' => 'r2']], $store->get(1, 10, $token)['rows']);
        $this->assertNull($store->get(2, 10, $token));
        $this->assertNull($store->get(1, 11, $token));
        $this->assertNull($store->get(1, 10, '../'.$token));
    }

    public function test_a_manager_keeps_only_the_most_recent_drafts(): void
    {
        $store = new MenuImportDraftStore;
        $tokens = [];
        for ($i = 0; $i < MenuImportDraftStore::MAX_DRAFTS_PER_MANAGER + 2; $i++) {
            $tokens[] = $store->create(1, 10, ['rows' => [['id' => 'r'.$i]]]);
        }
        $otherManager = $store->create(1, 11, ['rows' => []]);

        $this->assertNull($store->get(1, 10, $tokens[0]));
        $this->assertNull($store->get(1, 10, $tokens[1]));
        foreach (array_slice($tokens, 2) as $token) {
            $this->assertNotNull($store->get(1, 10, $token));
        }
        $this->assertNotNull($store->get(1, 11, $otherManager));
    }

    public function test_draft_expires_and_can_be_forgotten(): void
    {
        config(['resmenu.menu_import.draft_ttl_minutes' => 5]);
        $store = new MenuImportDraftStore;
        $token = $store->create(1, 10, ['rows' => []]);

        $this->travel(6)->minutes();
        $this->assertNull($store->get(1, 10, $token));

        $this->travelBack();
        $second = $store->create(1, 10, ['rows' => []]);
        $store->forget(1, 10, $second);
        $this->assertNull($store->get(1, 10, $second));
    }
}
