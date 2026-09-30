<?php

namespace Tests\Unit\MenuImport;

use App\Services\MenuImport\MenuImportCsvReader;
use App\Services\MenuImport\MenuImportFormat;
use App\Services\MenuImport\MenuImportRowRules;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class MenuImportCsvReaderTest extends TestCase
{
    private MenuImportCsvReader $reader;

    protected function setUp(): void
    {
        parent::setUp();
        $this->reader = new MenuImportCsvReader;
    }

    public function test_sample_csv_has_five_headers_and_four_complete_rows(): void
    {
        $csv = MenuImportFormat::sampleCsv();

        $this->assertStringStartsWith("\xEF\xBB\xBF", $csv);
        $this->assertStringStartsWith("\xEF\xBB\xBFSection,Category,Menu Item,Description,Price\r\n", $csv);
        $this->assertSame(5, substr_count($csv, "\r\n"));
    }

    public function test_sample_csv_round_trips_with_zero_errors(): void
    {
        $result = $this->reader->readString(MenuImportFormat::sampleCsv());

        $this->assertTrue($result['ok'], implode(' ', $result['errors']));
        $this->assertSame([], $result['notices']);
        $this->assertCount(4, $result['rows']);

        foreach (MenuImportFormat::SAMPLE_ROWS as $i => [$section, $category, $name, $description, $price]) {
            $row = $result['rows'][$i];
            $this->assertSame($i + 2, $row['row_number']);
            $this->assertSame([$section, $category, $name, $description, $price], [$row['section'], $row['category'], $row['name'], $row['description'], $row['price']]);
            $this->assertNull($row['parse_error']);
            $this->assertSame([], MenuImportRowRules::validate($row)['errors']);
        }
    }

    public function test_blank_template_is_header_row_only(): void
    {
        $this->assertSame("\xEF\xBB\xBFSection,Category,Menu Item,Description,Price\r\n", MenuImportFormat::templateCsv());

        $result = $this->reader->readString(MenuImportFormat::templateCsv());
        $this->assertFalse($result['ok']);
        $this->assertStringContainsString('no menu rows', $result['errors'][0]);
    }

    public function test_excel_exported_csv_with_semicolons_and_windows_1252_parses(): void
    {
        $csv = mb_convert_encoding("Section;Category;Menu Item;Description;Price\r\nFood;Entrées;Crème brûlée;Rich, creamy;\"4,500\"\r\n", 'Windows-1252', 'UTF-8');

        $result = $this->reader->readString($csv);

        $this->assertTrue($result['ok'], implode(' ', $result['errors']));
        $this->assertSame('Entrées', $result['rows'][0]['category']);
        $this->assertSame('Crème brûlée', $result['rows'][0]['name']);
        $this->assertSame('Rich, creamy', $result['rows'][0]['description']);
        $this->assertSame('4,500', $result['rows'][0]['price']);
        $this->assertStringContainsString('Windows-1252', $result['notices'][0]);
    }

    public function test_utf16_and_tab_delimited_file_parses(): void
    {
        $csv = "\xFF\xFE".mb_convert_encoding("Section\tCategory\tMenu Item\tDescription\tPrice\nDrinks\tSoft Drinks\tFanta\t\t1500\n", 'UTF-16LE', 'UTF-8');

        $result = $this->reader->readString($csv);

        $this->assertTrue($result['ok'], implode(' ', $result['errors']));
        $this->assertSame('Fanta', $result['rows'][0]['name']);
        $this->assertSame('', $result['rows'][0]['description']);
    }

    public function test_quoted_commas_newlines_and_cr_line_endings(): void
    {
        $csv = "Section,Category,Menu Item,Description,Price\r\"Food\",\"Starters\",\"Wings, spicy\",\"Line one\nLine two\",\"8500\"\r";

        $result = $this->reader->readString($csv);

        $this->assertTrue($result['ok'], implode(' ', $result['errors']));
        $this->assertSame('Wings, spicy', $result['rows'][0]['name']);
        $this->assertSame("Line one\nLine two", $result['rows'][0]['description']);
    }

    public function test_headers_match_case_insensitively_in_any_order_and_extra_columns_are_noted(): void
    {
        $csv = "price , MENU ITEM,notes,section,Description,category\n8500,Wings,x,Food,Nice,Starters\n";

        $result = $this->reader->readString($csv);

        $this->assertTrue($result['ok'], implode(' ', $result['errors']));
        $this->assertSame(['Food', 'Starters', 'Wings', 'Nice', '8500'], [
            $result['rows'][0]['section'], $result['rows'][0]['category'], $result['rows'][0]['name'],
            $result['rows'][0]['description'], $result['rows'][0]['price'],
        ]);
        $this->assertStringContainsString('extra columns were ignored: notes', $result['notices'][0]);
    }

    public function test_missing_description_column_is_rejected_and_named(): void
    {
        $result = $this->reader->readString("Section,Category,Menu Item,Price\nFood,Starters,Wings,8500\n");

        $this->assertFalse($result['ok']);
        $this->assertStringContainsString('Missing required column: Description', $result['errors'][0]);
    }

    public function test_multiple_missing_columns_and_duplicate_header(): void
    {
        $missing = $this->reader->readString("Name,Cost\nWings,8500\n");
        $this->assertStringContainsString('Section, Category, Menu Item, Description, Price', $missing['errors'][0]);

        $duplicate = $this->reader->readString("Section,Category,Menu Item,Description,Price,Price\nFood,Starters,Wings,,1,2\n");
        $this->assertFalse($duplicate['ok']);
        $this->assertStringContainsString('"Price" column appears more than once', $duplicate['errors'][0]);
    }

    public function test_blank_rows_are_counted_and_row_numbers_follow_the_spreadsheet(): void
    {
        $csv = "Section,Category,Menu Item,Description,Price\n\nFood,Starters,Wings,,8500\n,,,,\nFood,Starters,Rolls,,5000\n";

        $result = $this->reader->readString($csv);

        $this->assertTrue($result['ok']);
        $this->assertSame(2, $result['blank_rows']);
        $this->assertSame([3, 5], array_column($result['rows'], 'row_number'));
        $this->assertSame('2 empty rows were skipped.', $result['notices'][0]);
    }

    public function test_row_with_wrong_number_of_columns_gets_a_row_error(): void
    {
        $result = $this->reader->readString("Section,Category,Menu Item,Description,Price\nFood,Starters,Wings,Crispy, hot,8500\n");

        $this->assertTrue($result['ok']);
        $this->assertStringContainsString('6 values but the file has 5 columns', $result['rows'][0]['parse_error']);
    }

    public function test_partly_filled_rows_are_kept_for_validation(): void
    {
        $result = $this->reader->readString("Section,Category,Menu Item,Description,Price\n,Starters,Wings,,\n");

        $this->assertTrue($result['ok']);
        $errors = MenuImportRowRules::validate($result['rows'][0])['errors'];
        $this->assertSame('Section is required.', $errors['section']);
        $this->assertSame('Price is required.', $errors['price']);
    }

    public function test_binary_and_excel_content_is_rejected(): void
    {
        $this->assertSame(MenuImportCsvReader::EXCEL_MESSAGE, $this->reader->readString("PK\x03\x04rest-of-zip")['errors'][0]);
        $this->assertSame(MenuImportCsvReader::EXCEL_MESSAGE, $this->reader->readString("\xD0\xCF\x11\xE0xls")['errors'][0]);
        $this->assertStringContainsString('does not look like a text CSV', $this->reader->readString("Section\0\xFF\xFE\x00junk")['errors'][0]);
    }

    public function test_uploaded_xlsx_and_non_csv_files_are_rejected_by_extension(): void
    {
        $xlsx = UploadedFile::fake()->createWithContent('menu.xlsx', 'whatever');
        $this->assertSame(MenuImportCsvReader::EXCEL_MESSAGE, $this->reader->read($xlsx)['errors'][0]);

        $pdf = UploadedFile::fake()->createWithContent('menu.pdf', 'whatever');
        $this->assertStringContainsString('Only .csv files', $this->reader->read($pdf)['errors'][0]);

        $empty = UploadedFile::fake()->createWithContent('menu.csv', '');
        $this->assertSame('The file is empty.', $this->reader->read($empty)['errors'][0]);
    }

    public function test_uploaded_csv_with_disguised_excel_content_is_rejected(): void
    {
        $file = UploadedFile::fake()->createWithContent('menu.csv', "PK\x03\x04zipdata");

        $this->assertSame(MenuImportCsvReader::EXCEL_MESSAGE, $this->reader->read($file)['errors'][0]);
    }

    public function test_file_size_and_row_limits(): void
    {
        config(['resmenu.menu_import.max_file_kb' => 1, 'resmenu.menu_import.max_rows' => 2]);

        $big = UploadedFile::fake()->createWithContent('menu.csv', str_repeat('a', 2048));
        $this->assertStringContainsString('larger than 1 KB', $this->reader->read($big)['errors'][0]);

        $tooMany = $this->reader->readString("Section,Category,Menu Item,Description,Price\nA,B,C,,1\nA,B,D,,1\nA,B,E,,1\n");
        $this->assertStringContainsString('more than 2 menu rows', $tooMany['errors'][0]);
    }

    public function test_millions_of_short_lines_are_rejected_without_exhausting_memory(): void
    {
        $header = "Section,Category,Menu Item,Description,Price\n";
        $cases = [
            'empty-cell rows' => [$header.str_repeat(",,,,\n", 400000), 'too many lines'],
            'short rows' => [$header.str_repeat("a\n", 900000), 'more than 1000 menu rows'],
            'one long line' => [$header.str_repeat(',', 2 * 1024 * 1024 - 64), 'line in the file is too long'],
        ];

        foreach ($cases as $name => [$csv, $message]) {
            gc_collect_cycles();
            memory_reset_peak_usage();
            $before = memory_get_usage();

            $result = $this->reader->readString($csv);

            $this->assertFalse($result['ok'], $name);
            $this->assertStringContainsString($message, $result['errors'][0], $name);
            $this->assertLessThan(32 * 1024 * 1024, memory_get_peak_usage() - $before, $name);
        }
    }

    public function test_full_file_with_blank_rows_up_to_the_line_limit_still_parses(): void
    {
        $csv = "Section,Category,Menu Item,Description,Price\n"
            .str_repeat("Food,Mains,Rice,\"Long, tasty\",1500\n\n\n\n\n", MenuImportCsvReader::maxRows());

        $result = $this->reader->readString($csv);

        $this->assertTrue($result['ok']);
        $this->assertCount(MenuImportCsvReader::maxRows(), $result['rows']);
        $this->assertSame(MenuImportCsvReader::maxRows() * 4, $result['blank_rows']);
    }

    public function test_original_filename_is_only_a_display_name(): void
    {
        $file = UploadedFile::fake()->createWithContent('..\\..\\evil<script>.csv', MenuImportFormat::sampleCsv());

        $result = $this->reader->read($file);

        $this->assertTrue($result['ok']);
        $this->assertSame('evil<script>.csv', $result['filename']);
    }

    public function test_price_normalization(): void
    {
        foreach (['8500' => '8500.00', '8500.50' => '8500.50', '8,500' => '8500.00', '₦8,500' => '8500.00', 'NGN 8500' => '8500.00', '₦ 1,250,000.5' => '1250000.50', '0' => '0.00'] as $raw => $expected) {
            $result = MenuImportRowRules::validate(['section' => 'A', 'category' => 'B', 'name' => 'C', 'price' => $raw]);
            $this->assertSame([], $result['errors'], "Price {$raw}");
            $this->assertSame($expected, $result['price'], "Price {$raw}");
        }

        foreach (['-100' => 'negative', '10.555' => '2 decimal places', '100000000' => '99,999,999.99', 'abc' => 'must be a number', '8,5' => 'must be a number', '' => 'required'] as $raw => $message) {
            $errors = MenuImportRowRules::validate(['section' => 'A', 'category' => 'B', 'name' => 'C', 'price' => $raw])['errors'];
            $this->assertStringContainsString($message, $errors['price'] ?? '', "Price {$raw}");
        }
    }
}
