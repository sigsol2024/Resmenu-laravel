<?php

namespace App\Services;

class EmailDeliverabilityService
{
    /**
     * @return array{state: string} state: ok|permanent_bad|transient_unavailable
     */
    public function evaluateMx(string $email): array
    {
        $email = trim($email);
        if ($email === '' || ! str_contains($email, '@')) {
            return ['state' => 'permanent_bad'];
        }

        $domain = strtolower(trim(substr(strrchr($email, '@'), 1)));
        if ($domain === '' || ! preg_match('/^[a-z0-9]([a-z0-9-]*[a-z0-9])?(\.[a-z0-9]([a-z0-9-]*[a-z0-9])?)+$/i', $domain)) {
            return ['state' => 'permanent_bad'];
        }

        $cached = $this->readCache($domain);
        if ($cached !== null) {
            return ['state' => $cached];
        }

        $result = $this->probeMxWithRetries($domain);
        $this->writeCache($domain, $result['state']);

        return $result;
    }

    public function isDeliverable(string $email): bool
    {
        return $this->evaluateMx($email)['state'] === 'ok';
    }

    /** @return array{state: string} */
    private function probeMxWithRetries(string $domain): array
    {
        for ($attempt = 0; $attempt < 2; $attempt++) {
            $records = @dns_get_record($domain, DNS_MX);
            if ($records === false) {
                usleep(100000);

                continue;
            }
            if ($records === []) {
                $a = @dns_get_record($domain, DNS_A);
                $aaaa = @dns_get_record($domain, DNS_AAAA);

                return ($a || $aaaa) ? ['state' => 'ok'] : ['state' => 'permanent_bad'];
            }

            foreach ($records as $rec) {
                $target = strtolower(trim((string) ($rec['target'] ?? $rec['exchange'] ?? '')));
                if ($target === '.') {
                    return ['state' => 'permanent_bad'];
                }
            }

            return ['state' => 'ok'];
        }

        return ['state' => 'transient_unavailable'];
    }

    private function cacheDir(): ?string
    {
        $dir = storage_path('app/mx-reg-cache');
        if (! is_dir($dir) && ! @mkdir($dir, 0755, true)) {
            return null;
        }

        return is_dir($dir) ? $dir : null;
    }

    private function readCache(string $domain): ?string
    {
        $dir = $this->cacheDir();
        if (! $dir) {
            return null;
        }
        $file = $dir.'/'.hash('sha256', $domain).'.json';
        if (! is_readable($file)) {
            return null;
        }
        $j = json_decode((string) file_get_contents($file), true);
        if (! is_array($j) || ($j['exp'] ?? 0) < time()) {
            return null;
        }
        $st = (string) ($j['state'] ?? '');
        if (in_array($st, ['ok', 'permanent_bad', 'transient_unavailable'], true)) {
            return $st;
        }

        return null;
    }

    private function writeCache(string $domain, string $state): void
    {
        $dir = $this->cacheDir();
        if (! $dir) {
            return;
        }
        $ttl = match ($state) {
            'transient_unavailable' => 60,
            'permanent_bad' => 86400,
            default => 3600,
        };
        file_put_contents(
            $dir.'/'.hash('sha256', $domain).'.json',
            json_encode(['state' => $state, 'exp' => time() + $ttl]),
            LOCK_EX,
        );
    }
}
