<?php

namespace App\Support;

class PhoneNormalizer
{
    /**
     * Normalize to a compact international-ish form (+digits) when possible.
     * Returns null when invalid.
     */
    public static function normalize(?string $raw): ?string
    {
        if ($raw === null) {
            return null;
        }

        $trimmed = trim($raw);
        if ($trimmed === '') {
            return null;
        }

        $hasPlus = str_starts_with($trimmed, '+');
        $digits = preg_replace('/\D+/', '', $trimmed) ?? '';
        if ($digits === '' || strlen($digits) < 7 || strlen($digits) > 15) {
            return null;
        }

        // Reject if original (minus spaces/dashes/parens) had letters
        if (preg_match('/[A-Za-z]/', $trimmed)) {
            return null;
        }

        if ($hasPlus) {
            return '+'.$digits;
        }

        // National numbers (often leading 0) stay without inventing a country code.
        if (str_starts_with($digits, '0')) {
            return $digits;
        }

        // Bare country-code form (11–15 digits, no leading 0) → E.164 with +.
        if (strlen($digits) >= 11) {
            return '+'.$digits;
        }

        return $digits;
    }

    public static function isValid(?string $raw): bool
    {
        return self::normalize($raw) !== null;
    }
}
