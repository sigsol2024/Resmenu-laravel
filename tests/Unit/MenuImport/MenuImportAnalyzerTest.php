<?php

namespace Tests\Unit\MenuImport;

use App\Services\MenuImport\MenuImportAnalyzer;
use App\Services\MenuImport\MenuImportCsvReader;
use App\Services\MenuImport\MenuImportFormat;
use App\Services\MenuImport\MenuImportRowRules;
use App\Services\SubscriptionService;
use Illuminate\Support\Facades\DB;
use Mockery;
use Tests\TestCase;

class MenuImportAnalyzerTest extends TestCase
{
    use MenuImportDatabase;

    /** @var array<string, array<string, mixed>> */
    private array $usage = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpMenuDatabase();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_sample_csv_builds_the_expected_hierarchy_with_zero_errors(): void
    {
        $rows = (new MenuImportCsvReader)->readString(MenuImportFormat::sampleCsv())['rows'];

        $analysis = $this->analyze($rows);

        $this->assertSame(0, $analysis['blocking']);
        $this->assertTrue($analysis['can_import']);
        $this->assertSame([
            'Food' => ['Starters', 'Main Course'],
            'Drinks' => ['Soft Drinks'],
        ], $this->treeShape($analysis));
        $this->assertSame(['new' => 2, 'existing' => 0], $analysis['summary']['sections']);
        $this->assertSame(['new' => 3, 'existing' => 0], $analysis['summary']['categories']);
        $this->assertSame(4, $analysis['summary']['items']['new']);
        $this->assertSame('8500.00', $analysis['plan']['items'][0]['price']);
    }

    public function test_existing_section_category_and_item_are_reused_and_updated(): void
    {
        $food = $this->makeSection('Food');
        $starters = $this->makeCategory($food, 'Starters');
        $wingsId = $this->makeItem($starters, 'Chicken Wings', '8500', 'Crispy wings');

        $analysis = $this->analyze([$this->row('Food', 'Starters', 'Chicken Wings', 'Crispy wings', '9000')]);

        $section = $analysis['tree'][0];
        $this->assertSame('existing', $section['status']);
        $this->assertSame('Section already exists: Food. The existing section will be used and no duplicate section will be created.', $section['messages'][0]['text']);
        $this->assertSame('existing', $section['categories'][0]['status']);
        $this->assertStringStartsWith('Category already exists: Starters under Food. The existing category will be used', $section['categories'][0]['messages'][0]['text']);

        $row = $analysis['rows']['r2'];
        $this->assertSame('update', $row['status']);
        $this->assertSame(['price' => ['from' => '8500.00', 'to' => '9000.00']], $row['changes']);
        $this->assertSame('Menu item already exists: Chicken Wings under Food / Starters. The existing menu item will be updated.', $row['messages'][0]['text']);
        $this->assertSame($wingsId, $analysis['plan']['items'][0]['existing_id']);
        $this->assertSame(0, $analysis['summary']['categories']['new']);
    }

    public function test_all_existing_and_identical_is_unchanged_with_nothing_to_import(): void
    {
        $food = $this->makeSection('Food');
        $starters = $this->makeCategory($food, 'Starters');
        $this->makeItem($starters, 'Chicken Wings', '8500', 'Crispy wings');

        $analysis = $this->analyze([$this->row('food', ' STARTERS ', 'chicken  wings', 'Crispy wings', '₦8,500')]);

        $this->assertSame('unchanged', $analysis['rows']['r2']['status']);
        $this->assertFalse($analysis['has_changes']);
        $this->assertFalse($analysis['can_import']);
        $this->assertSame([], $analysis['plan']['items']);
    }

    public function test_blank_description_keeps_the_existing_description(): void
    {
        $food = $this->makeSection('Food');
        $starters = $this->makeCategory($food, 'Starters');
        $this->makeItem($starters, 'Chicken Wings', '8500', 'Crispy wings');

        $priceChange = $this->analyze([$this->row('Food', 'Starters', 'Chicken Wings', '', '9000')])['rows']['r2'];
        $this->assertSame('update', $priceChange['status']);
        $this->assertSame(['price'], array_keys($priceChange['changes']));
        $this->assertContains('Description: unchanged (blank in file).', array_column($priceChange['messages'], 'text'));

        $samePrice = $this->analyze([$this->row('Food', 'Starters', 'Chicken Wings', '', '8500')])['rows']['r2'];
        $this->assertSame('unchanged', $samePrice['status']);
    }

    public function test_new_item_with_blank_description_is_created_empty(): void
    {
        $analysis = $this->analyze([$this->row('Food', 'Starters', 'Wings', '', '100')]);

        $this->assertSame('', $analysis['plan']['items'][0]['description']);
    }

    public function test_same_names_under_different_sections_stay_separate(): void
    {
        $analysis = $this->analyze([
            $this->row('Food', 'Main Course', 'Rice', 'Jollof', '5000', 2),
            $this->row('Drinks', 'Main Course', 'Rice', 'Rice milk', '1500', 3),
        ]);

        $this->assertSame(['Food' => ['Main Course'], 'Drinks' => ['Main Course']], $this->treeShape($analysis));
        $this->assertSame(0, $analysis['blocking']);
        $this->assertSame(['new', 'new'], [$analysis['rows']['r2']['status'], $analysis['rows']['r3']['status']]);
        $this->assertSame(2, $analysis['summary']['categories']['new']);
    }

    public function test_existing_item_under_other_section_is_not_matched(): void
    {
        $drinks = $this->makeSection('Drinks');
        $drinksMain = $this->makeCategory($drinks, 'Main Course');
        $this->makeItem($drinksMain, 'Rice', '1500');

        $analysis = $this->analyze([$this->row('Food', 'Main Course', 'Rice', '', '5000')]);

        $this->assertSame('new', $analysis['tree'][0]['status']);
        $this->assertSame('new', $analysis['tree'][0]['categories'][0]['status']);
        $this->assertSame('new', $analysis['rows']['r2']['status']);
        $this->assertStringContainsString('A menu item named Rice already exists under Drinks / Main Course', $analysis['rows']['r2']['messages'][0]['text']);
    }

    public function test_same_category_name_under_a_different_parent_creates_a_new_category(): void
    {
        $food = $this->makeSection('Food');
        $this->makeCategory($food, 'Pasta');
        $this->makeSection('Specials');

        $analysis = $this->analyze([$this->row('Specials', 'Pasta', 'Carbonara', '', '7000')]);

        $section = $analysis['tree'][0];
        $this->assertSame('existing', $section['status']);
        $this->assertSame('new', $section['categories'][0]['status']);
        $this->assertSame('A category named Pasta already exists under Food. A new Pasta category will be created under Specials.', $section['categories'][0]['messages'][0]['text']);
        $this->assertSame('new', $analysis['rows']['r2']['status']);
    }

    public function test_identical_duplicate_rows_are_merged(): void
    {
        $analysis = $this->analyze([
            $this->row('Food', 'Starters', 'Wings', 'Hot', '8500', 3),
            $this->row('Food', 'Starters', 'wings', 'Hot', '8,500', 5),
        ]);

        $this->assertSame('new', $analysis['rows']['r3']['status']);
        $this->assertSame('duplicate', $analysis['rows']['r5']['status']);
        $this->assertSame('Row 5 duplicates row 3 and will be imported once.', $analysis['rows']['r5']['messages'][0]['text']);
        $this->assertCount(1, $analysis['plan']['items']);
        $this->assertTrue($analysis['can_import']);
    }

    public function test_conflicting_duplicate_rows_block_the_import(): void
    {
        $analysis = $this->analyze([
            $this->row('Food', 'Starters', 'Chicken Wings', 'Hot', '8500', 3),
            $this->row('Food', 'Starters', 'Chicken Wings', 'Hot', '9000', 5),
        ]);

        $this->assertSame('conflict', $analysis['rows']['r3']['status']);
        $this->assertSame('conflict', $analysis['rows']['r5']['status']);
        $this->assertStringStartsWith('Rows 3 and 5 define Chicken Wings under Food / Starters with different price/description.', $analysis['rows']['r3']['errors']['row']);
        $this->assertSame(2, $analysis['blocking']);
        $this->assertFalse($analysis['can_import']);
    }

    public function test_names_differing_by_case_or_spacing_are_grouped_with_a_note(): void
    {
        $analysis = $this->analyze([
            $this->row('Food', 'Starters', 'A', '', '1', 2),
            $this->row('  food ', 'starters', 'B', '', '1', 3),
        ]);

        $this->assertSame(['Food' => ['Starters']], $this->treeShape($analysis));
        $this->assertStringContainsString('"food" were grouped with "Food"', $analysis['tree'][0]['messages'][0]['text']);
    }

    public function test_moving_an_item_recalculates_matches(): void
    {
        $food = $this->makeSection('Food');
        $starters = $this->makeCategory($food, 'Starters');
        $this->makeItem($starters, 'Chicken Wings', '8500');

        $rows = [$this->row('Food', 'Starters', 'Chicken Wings', '', '8500')];
        $this->assertSame('unchanged', $this->analyze($rows)['rows']['r2']['status']);

        $rows[0]['category'] = 'Main Course';
        $moved = $this->analyze($rows);

        $this->assertSame('existing', $moved['tree'][0]['status']);
        $this->assertSame('new', $moved['tree'][0]['categories'][0]['status']);
        $this->assertSame('new', $moved['rows']['r2']['status']);
    }

    public function test_moving_a_category_rematches_its_items(): void
    {
        $specials = $this->makeSection('Specials');
        $pasta = $this->makeCategory($specials, 'Pasta');
        $this->makeItem($pasta, 'Carbonara', '7000');
        $this->makeSection('Food');

        $rows = [
            $this->row('Food', 'Pasta', 'Carbonara', '', '7500', 2),
            $this->row('Food', 'Pasta', 'Bolognese', '', '6000', 3),
        ];
        $before = $this->analyze($rows);
        $this->assertSame('new', $before['tree'][0]['categories'][0]['status']);
        $this->assertSame('new', $before['rows']['r2']['status']);

        foreach ($rows as &$row) {
            $row['section'] = 'Specials';
        }
        unset($row);
        $after = $this->analyze($rows);

        $this->assertSame('existing', $after['tree'][0]['categories'][0]['status']);
        $this->assertSame('update', $after['rows']['r2']['status']);
        $this->assertSame('new', $after['rows']['r3']['status']);
    }

    public function test_conflicts_are_recalculated_after_a_move(): void
    {
        $rows = [
            $this->row('Food', 'Starters', 'Wings', '', '8500', 2),
            $this->row('Food', 'Grill', 'Wings', '', '9000', 3),
        ];
        $this->assertSame(0, $this->analyze($rows)['blocking']);

        $rows[1]['category'] = 'Starters';
        $conflicted = $this->analyze($rows);
        $this->assertSame(2, $conflicted['blocking']);

        $rows[1]['excluded'] = true;
        $resolved = $this->analyze($rows);
        $this->assertSame(0, $resolved['blocking']);
        $this->assertSame('excluded', $resolved['rows']['r3']['status']);
        $this->assertSame(1, $resolved['summary']['items']['excluded']);
    }

    public function test_row_errors_missing_parents_and_parse_errors(): void
    {
        $rows = [
            $this->row('', 'Starters', 'Wings', '', '100', 2),
            $this->row('Food', '', 'Wings', '', '100', 3),
            $this->row('Food', 'Starters', '', '', 'abc', 4),
            array_merge($this->row('Food', 'Starters', 'Rolls', '', '100', 5), ['parse_error' => 'This row has 6 values']),
        ];

        $analysis = $this->analyze($rows);

        $this->assertSame(['r2', 'r3'], $analysis['attention']);
        $this->assertSame('Section is required.', $analysis['rows']['r2']['errors']['section']);
        $this->assertSame('Category is required.', $analysis['rows']['r3']['errors']['category']);
        $this->assertSame('error', $analysis['rows']['r4']['status']);
        $this->assertArrayHasKey('name', $analysis['rows']['r4']['errors']);
        $this->assertArrayHasKey('price', $analysis['rows']['r4']['errors']);
        $this->assertSame('This row has 6 values', $analysis['rows']['r5']['errors']['row']);
        $this->assertSame(4, $analysis['blocking']);
        $this->assertFalse($analysis['can_import']);
    }

    public function test_plan_limits_block_the_import(): void
    {
        $this->usage['categories'] = ['used' => 1, 'limit' => 2, 'remaining' => 1, 'unlimited' => false];
        $this->usage['menu_items'] = ['used' => 9, 'limit' => 10, 'remaining' => 1, 'unlimited' => false];

        $analysis = $this->analyze([
            $this->row('Food', 'Starters', 'A', '', '1', 2),
            $this->row('Food', 'Mains', 'B', '', '1', 3),
        ]);

        $this->assertCount(2, $analysis['errors']);
        $this->assertSame('Your plan allows 2 categories. You have 1, and this import would add 2 (1 over the limit). Exclude some rows or upgrade your plan.', $analysis['errors'][0]);
        $this->assertStringContainsString('allows 10 menu items', $analysis['errors'][1]);
        $this->assertFalse($analysis['can_import']);
    }

    public function test_updates_do_not_count_against_plan_limits(): void
    {
        $this->usage['menu_items'] = ['used' => 1, 'limit' => 1, 'remaining' => 0, 'unlimited' => false];
        $food = $this->makeSection('Food');
        $starters = $this->makeCategory($food, 'Starters');
        $this->makeItem($starters, 'Wings', '100');

        $analysis = $this->analyze([$this->row('Food', 'Starters', 'Wings', '', '200')]);

        $this->assertSame([], $analysis['errors']);
        $this->assertTrue($analysis['can_import']);
    }

    public function test_duplicate_existing_records_pick_the_first_by_display_order_with_a_warning(): void
    {
        $this->makeSection('Food', 1, ['display_order' => 5]);
        $first = $this->makeSection('Food', 1, ['display_order' => 2]);
        $this->makeSection('Hidden', 1, ['is_active' => 0]);

        $analysis = $this->analyze([
            $this->row('Food', 'Starters', 'A', '', '1', 2),
            $this->row('Hidden', 'Starters', 'B', '', '1', 3),
        ]);

        $this->assertSame($first, $analysis['plan']['sections']['food']['existing_id']);
        $this->assertStringContainsString('There are 2 existing sections named Food', $analysis['tree'][0]['messages'][1]['text']);
        $this->assertStringContainsString('is inactive', $analysis['tree'][1]['messages'][1]['text']);
    }

    public function test_other_restaurants_menu_is_never_matched(): void
    {
        $food = $this->makeSection('Food', 2);
        $starters = $this->makeCategory($food, 'Starters', 2, ['slug' => 'starters-other']);
        $this->makeItem($starters, 'Chicken Wings', '8500', null, 2);

        $analysis = $this->analyze([$this->row('Food', 'Starters', 'Chicken Wings', '', '8500')]);

        $this->assertSame('new', $analysis['tree'][0]['status']);
        $this->assertSame('new', $analysis['rows']['r2']['status']);
        $this->assertSame([], $analysis['existing_categories']);
    }

    public function test_revision_is_stable_and_changes_when_the_database_changes(): void
    {
        $rows = [$this->row('Food', 'Starters', 'Wings', '', '100')];
        $first = $this->analyze($rows)['revision'];

        $this->assertSame($first, $this->analyze($rows)['revision']);

        $this->makeSection('Food');
        $this->assertNotSame($first, $this->analyze($rows)['revision']);
    }

    public function test_sanitize_drops_client_supplied_ids_and_unknown_fields(): void
    {
        $clean = MenuImportAnalyzer::sanitizeRows([
            ['id' => 'r2', 'restaurant_id' => 2, 'existing_id' => 99, 'category_id' => 5, 'section' => 'A', 'category' => 'B', 'name' => 'C', 'price' => '1'],
            ['id' => '<script>', 'section' => ['array'], 'name' => 'D'],
            'not-a-row',
        ]);

        $this->assertCount(2, $clean);
        $this->assertSame(['id', 'row_number', 'section', 'category', 'name', 'description', 'price', 'excluded', 'parse_error'], array_keys($clean[0]));
        $this->assertSame('x1', $clean[1]['id']);
        $this->assertSame('', $clean[1]['section']);
    }

    public function test_sanitize_caps_fields_just_past_the_validation_limits(): void
    {
        $clean = MenuImportAnalyzer::sanitizeRows([[
            'id' => 'r2',
            'section' => str_repeat('s', 5000),
            'category' => str_repeat('c', 5000),
            'name' => str_repeat('n', 5000),
            'description' => str_repeat('d', 200000),
            'price' => str_repeat('9', 5000),
        ]]);

        $this->assertSame(256, mb_strlen($clean[0]['section']));
        $this->assertSame(256, mb_strlen($clean[0]['category']));
        $this->assertSame(256, mb_strlen($clean[0]['name']));
        $this->assertSame(65536, mb_strlen($clean[0]['description']));
        $this->assertSame(100, mb_strlen($clean[0]['price']));

        $errors = MenuImportRowRules::validate($clean[0])['errors'];
        $this->assertArrayHasKey('name', $errors);
        $this->assertArrayHasKey('description', $errors);
        $this->assertArrayHasKey('price', $errors);
    }

    public function test_client_output_has_no_write_plan(): void
    {
        $analysis = MenuImportAnalyzer::forClient($this->analyze([$this->row('Food', 'Starters', 'Wings', '', '100')]));

        $this->assertArrayNotHasKey('plan', $analysis);
        $this->assertArrayNotHasKey('existing_id', $analysis['rows']['r2']);
    }

    private function analyze(array $rows, int $restaurantId = 1): array
    {
        $subscriptions = Mockery::mock(SubscriptionService::class);
        $subscriptions->shouldReceive('getRemainingUsage')->andReturnUsing(
            fn (int $r, string $feature) => $this->usage[$feature] ?? ['used' => 0, 'limit' => 'unlimited', 'remaining' => 'unlimited', 'unlimited' => true],
        );

        return (new MenuImportAnalyzer($subscriptions))->analyze($restaurantId, $rows);
    }

    private function row(string $section, string $category, string $name, string $description, string $price, int $rowNumber = 2): array
    {
        return [
            'id' => 'r'.$rowNumber,
            'row_number' => $rowNumber,
            'section' => $section,
            'category' => $category,
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'excluded' => false,
            'parse_error' => null,
        ];
    }

    /** @return array<string, list<string>> */
    private function treeShape(array $analysis): array
    {
        $shape = [];
        foreach ($analysis['tree'] as $section) {
            $shape[$section['name']] = array_column($section['categories'], 'name');
        }

        return $shape;
    }
}
