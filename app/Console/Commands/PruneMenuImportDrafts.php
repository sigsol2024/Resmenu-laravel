<?php

namespace App\Console\Commands;

use App\Services\MenuImport\MenuImportDraftStore;
use Illuminate\Cache\DatabaseStore;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * The database cache store only deletes an expired row when that key is read
 * again, and abandoned import drafts never are. Other stores expire natively.
 */
class PruneMenuImportDrafts extends Command
{
    protected $signature = 'menu-import:prune-drafts';

    protected $description = 'Delete expired menu import drafts from the database cache';

    public function handle(): int
    {
        $storeName = (string) config('cache.default');
        $store = Cache::store($storeName)->getStore();
        if (! $store instanceof DatabaseStore) {
            $this->info('The cache store expires drafts on its own; nothing to prune.');

            return self::SUCCESS;
        }

        $deleted = $store->getConnection()
            ->table((string) config("cache.stores.{$storeName}.table", 'cache'))
            ->where('key', 'like', $store->getPrefix().MenuImportDraftStore::KEY_PREFIX.'%')
            ->where('expiration', '<=', now()->getTimestamp())
            ->delete();

        Log::info('Expired menu import drafts pruned', ['deleted' => $deleted]);
        $this->info("Deleted {$deleted} expired menu import draft(s).");

        return self::SUCCESS;
    }
}
