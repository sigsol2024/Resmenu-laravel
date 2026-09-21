<?php

namespace Tests\Unit;

use App\Support\PhoneNormalizer;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PhoneNormalizerTest extends TestCase
{
    #[DataProvider('validPhones')]
    public function test_valid_phones(string $raw, string $expected): void
    {
        $this->assertSame($expected, PhoneNormalizer::normalize($raw));
        $this->assertTrue(PhoneNormalizer::isValid($raw));
    }

    public static function validPhones(): array
    {
        return [
            'nigeria local' => ['08031234567', '08031234567'],
            'nigeria with spaces' => ['0803 123 4567', '08031234567'],
            'e164' => ['+2348031234567', '+2348031234567'],
            'intl spaces' => ['+234 803 123 4567', '+2348031234567'],
            'us e164' => ['+1 (415) 555-2671', '+14155552671'],
        ];
    }

    #[DataProvider('invalidPhones')]
    public function test_invalid_phones(string $raw): void
    {
        $this->assertNull(PhoneNormalizer::normalize($raw));
        $this->assertFalse(PhoneNormalizer::isValid($raw));
    }

    public static function invalidPhones(): array
    {
        return [
            'empty' => [''],
            'letters' => ['callme'],
            'too short' => ['12345'],
            'too long' => ['+'.str_repeat('1', 20)],
            'mixed letters' => ['+234ABC1234567'],
        ];
    }
}
