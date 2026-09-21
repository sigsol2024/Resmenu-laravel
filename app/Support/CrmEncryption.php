<?php

namespace App\Support;

/**
 * CRM secret encryption. Uses CRM_ENCRYPTION_KEY when set; falls back to payment key for decrypt compat.
 */
class CrmEncryption
{
    private const METHOD = 'AES-256-CBC';

    private const PLACEHOLDER_KEY = 'your-32-character-secret-key-here';

    public static function encrypt(string $plain): string
    {
        if ($plain === '') {
            return '';
        }

        $key = self::resolveEncryptKey();
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(self::METHOD));
        $encrypted = openssl_encrypt($plain, self::METHOD, $key, 0, $iv);

        return base64_encode($iv.'::'.$encrypted);
    }

    public static function decrypt(?string $encryptedKey): string
    {
        if ($encryptedKey === null || $encryptedKey === '') {
            return '';
        }

        $data = base64_decode($encryptedKey, true);
        if ($data === false) {
            return '';
        }

        $parts = explode('::', $data, 2);
        if (count($parts) !== 2) {
            return '';
        }

        [$iv, $encrypted] = $parts;

        foreach (self::decryptKeys() as $key) {
            $plain = openssl_decrypt($encrypted, self::METHOD, $key, 0, $iv);
            if ($plain !== false && $plain !== '') {
                return $plain;
            }
        }

        return '';
    }

    private static function resolveEncryptKey(): string
    {
        $crm = (string) config('resmenu.crm_encryption_key', '');
        if ($crm !== '' && $crm !== self::PLACEHOLDER_KEY) {
            return $crm;
        }

        return LegacyEncryption::resolveKeyForCrmFallback();
    }

    /** @return list<string> */
    private static function decryptKeys(): array
    {
        $keys = [];
        $crm = (string) config('resmenu.crm_encryption_key', '');
        if ($crm !== '' && $crm !== self::PLACEHOLDER_KEY) {
            $keys[] = $crm;
        }
        $keys[] = LegacyEncryption::resolveKeyForCrmFallback();

        return array_values(array_unique($keys));
    }
}
