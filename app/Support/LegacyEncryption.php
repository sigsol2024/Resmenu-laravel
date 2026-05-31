<?php

namespace App\Support;

class LegacyEncryption
{
    private const METHOD = 'AES-256-CBC';

    private const PLACEHOLDER_KEY = 'your-32-character-secret-key-here';

    public static function decrypt(?string $encryptedKey): string
    {
        if ($encryptedKey === null || $encryptedKey === '') {
            return '';
        }

        $key = self::resolveKey();
        $data = base64_decode($encryptedKey, true);
        if ($data === false) {
            return $encryptedKey;
        }

        $parts = explode('::', $data, 2);
        if (count($parts) !== 2) {
            return $encryptedKey;
        }

        [$iv, $encrypted] = $parts;
        $plain = openssl_decrypt($encrypted, self::METHOD, $key, 0, $iv);

        return $plain !== false ? $plain : '';
    }

    public static function encrypt(string $plain): string
    {
        if ($plain === '') {
            return '';
        }

        $key = self::resolveKey();
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(self::METHOD));
        $encrypted = openssl_encrypt($plain, self::METHOD, $key, 0, $iv);

        return base64_encode($iv.'::'.$encrypted);
    }

    private static function resolveKey(): string
    {
        $key = (string) config('resmenu.payment_encryption_key', '');

        if ($key === '' || $key === self::PLACEHOLDER_KEY) {
            if (app()->environment(['local', 'testing'])) {
                return 'local-dev-payment-encryption-key!';
            }

            throw new \RuntimeException('PAYMENT_ENCRYPTION_KEY must be set to a secure value.');
        }

        return $key;
    }
}
