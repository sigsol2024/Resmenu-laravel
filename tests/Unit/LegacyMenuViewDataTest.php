<?php

namespace Tests\Unit;

use App\Support\LegacyMenuViewData;
use Tests\TestCase;

class LegacyMenuViewDataTest extends TestCase
{
    public function test_normalize_strips_html_tags_without_html_encoding(): void
    {
        $payload = [
            'restaurant' => [
                'name' => '<script>alert(1)</script>Cafe & Co',
                'description' => '<b>Fresh</b> food',
            ],
            'sections' => [
                [
                    'name' => '<img onerror=alert(1)>Starters',
                    'categories' => [
                        [
                            'name' => 'Salads',
                            'menu_items' => [
                                ['name' => 'Caesar', 'description' => '<i>crisp</i>'],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $normalized = LegacyMenuViewData::normalize($payload);

        $this->assertSame('alert(1)Cafe & Co', $normalized['restaurant']['name']);
        $this->assertSame('Fresh food', $normalized['restaurant']['description']);
        $this->assertSame('Starters', $normalized['sections'][0]['name']);
        $this->assertSame('crisp', $normalized['sections'][0]['categories'][0]['menu_items'][0]['description']);
        $this->assertStringNotContainsString('&amp;', $normalized['restaurant']['name']);
    }
}
