<?php

namespace App\Services\MenuImport;

/**
 * Single definition of the menu import file format. The CSV reader, the
 * downloadable template/sample and the on-screen sample table all use it.
 */
final class MenuImportFormat
{
    /** Column header => draft row field, in the order the template uses. */
    public const COLUMNS = [
        'Section' => 'section',
        'Category' => 'category',
        'Menu Item' => 'name',
        'Description' => 'description',
        'Price' => 'price',
    ];

    public const HEADERS = ['Section', 'Category', 'Menu Item', 'Description', 'Price'];

    public const SAMPLE_ROWS = [
        ['Food', 'Starters', 'Chicken Wings', 'Crispy chicken wings served with our house sauce', '8500'],
        ['Food', 'Starters', 'Spring Rolls', 'Crispy vegetable spring rolls served with sweet chili sauce', '5000'],
        ['Food', 'Main Course', 'Jollof Rice', 'Nigerian-style jollof rice served with grilled chicken', '12000'],
        ['Drinks', 'Soft Drinks', 'Coca-Cola', 'Chilled Coca-Cola', '2000'],
    ];

    public const TEMPLATE_FILENAME = 'resmenu-menu-import-template.csv';

    public const SAMPLE_FILENAME = 'resmenu-menu-import-sample.csv';

    public static function templateCsv(): string
    {
        return self::toCsv([self::HEADERS]);
    }

    public static function sampleCsv(): string
    {
        return self::toCsv([self::HEADERS, ...self::SAMPLE_ROWS]);
    }

    /** @param  list<list<string>>  $rows */
    private static function toCsv(array $rows): string
    {
        $lines = array_map(
            fn (array $row) => implode(',', array_map(self::csvCell(...), $row)),
            $rows,
        );

        return "\xEF\xBB\xBF".implode("\r\n", $lines)."\r\n";
    }

    private static function csvCell(string $value): string
    {
        return preg_match('/[",\r\n]/', $value)
            ? '"'.str_replace('"', '""', $value).'"'
            : $value;
    }
}
