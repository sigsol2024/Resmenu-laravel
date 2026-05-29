<?php

namespace App\Services;

class DisposableEmailService
{
    /** @var array<string, true>|null */
    private static ?array $domainSet = null;

    public function isDisposable(string $email): bool
    {
        $domain = strtolower(trim((string) substr(strrchr($email, '@'), 1)));
        if ($domain === '') {
            return false;
        }

        return isset($this->domainSet()[$domain]);
    }

    /** @return array<string, true> */
    private function domainSet(): array
    {
        if (self::$domainSet !== null) {
            return self::$domainSet;
        }

        self::$domainSet = [];
        $path = base_path('data/disposable-email-domains.txt');
        if (! is_readable($path)) {
            return self::$domainSet;
        }

        $fh = fopen($path, 'rb');
        if (! $fh) {
            return self::$domainSet;
        }

        while (($line = fgets($fh)) !== false) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }
            $line = strtolower($line);
            if (preg_match('/^[a-z0-9.-]+$/', $line)) {
                self::$domainSet[$line] = true;
            }
        }
        fclose($fh);

        return self::$domainSet;
    }
}
