<?php

namespace App\Services\MenuImport;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * Holds an uploaded-but-not-imported menu in the cache. Keys include the
 * restaurant and manager, so a token is useless to anyone else.
 */
final class MenuImportDraftStore
{
    /** Older drafts are discarded when a manager uploads more files than this. */
    public const MAX_DRAFTS_PER_MANAGER = 3;

    /** Every draft key starts with this, so expired drafts can be pruned. */
    public const KEY_PREFIX = 'menu_import:';

    public function create(int $restaurantId, int $managerId, array $draft): string
    {
        $token = Str::random(40);
        $this->put($restaurantId, $managerId, $token, $draft);

        $indexKey = $this->indexKey($restaurantId, $managerId);
        $tokens = Cache::get($indexKey);
        $tokens = is_array($tokens) ? array_values(array_filter($tokens, [self::class, 'validToken'])) : [];
        $tokens[] = $token;
        while (count($tokens) > self::MAX_DRAFTS_PER_MANAGER) {
            $this->forget($restaurantId, $managerId, (string) array_shift($tokens));
        }
        Cache::put($indexKey, $tokens, $this->expiresAt());

        return $token;
    }

    public function get(int $restaurantId, int $managerId, string $token): ?array
    {
        if (! self::validToken($token)) {
            return null;
        }

        $draft = Cache::get($this->key($restaurantId, $managerId, $token));
        if (! is_array($draft)
            || (int) ($draft['restaurant_id'] ?? 0) !== $restaurantId
            || (int) ($draft['manager_id'] ?? 0) !== $managerId) {
            return null;
        }

        return $draft;
    }

    public function put(int $restaurantId, int $managerId, string $token, array $draft): void
    {
        $draft['restaurant_id'] = $restaurantId;
        $draft['manager_id'] = $managerId;

        Cache::put($this->key($restaurantId, $managerId, $token), $draft, $this->expiresAt());
    }

    public function forget(int $restaurantId, int $managerId, string $token): void
    {
        if (self::validToken($token)) {
            Cache::forget($this->key($restaurantId, $managerId, $token));
        }
    }

    public function lockKey(int $restaurantId, int $managerId, string $token): string
    {
        return 'lock:'.$this->key($restaurantId, $managerId, $token);
    }

    public static function validToken(string $token): bool
    {
        return (bool) preg_match('/^[A-Za-z0-9]{40}$/', $token);
    }

    private function key(int $restaurantId, int $managerId, string $token): string
    {
        return self::KEY_PREFIX."{$restaurantId}:{$managerId}:{$token}";
    }

    private function indexKey(int $restaurantId, int $managerId): string
    {
        return self::KEY_PREFIX."index:{$restaurantId}:{$managerId}";
    }

    private function expiresAt(): \DateTimeInterface
    {
        return now()->addMinutes(max(1, (int) config('resmenu.menu_import.draft_ttl_minutes', 120)));
    }
}
