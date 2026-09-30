<?php

namespace Tests\Unit\MenuImport;

use App\Services\ActivityLogService;
use App\Services\DisplayOrderService;
use App\Services\MenuImport\MenuImportAnalyzer;
use App\Services\MenuImport\MenuImportCsvReader;
use App\Services\MenuImport\MenuImportFormat;
use App\Services\MenuImport\MenuImportSlugGenerator;
use App\Services\MenuImport\MenuImportWriter;
use App\Services\PlanVisibilityService;
use App\Services\SubscriptionService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Mockery;
use RuntimeException;
use Tests\TestCase;

class MenuImportWriterTest extends TestCase
{
    use MenuImportDatabase;

    private MenuImportAnalyzer $analyzer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpMenuDatabase();

        $subscriptions = Mockery::mock(SubscriptionService::class);
        $subscriptions->shouldReceive('getRemainingUsage')
            ->andReturn(['used' => 0, 'limit' => 'unlimited', 'remaining' => 'unlimited', 'unlimited' => true]);
        $this->analyzer = new MenuImportAnalyzer($subscriptions);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_sample_import_creates_the_full_hierarchy(): void
    {
        $rows = $this->sampleRows();
        $visibility = Mockery::mock(PlanVisibilityService::class);
        $visibility->shouldReceive('forgetCache')->once()->with(1);

        $outcome = $this->writer(visibility: $visibility)->import(1, 7, $rows, $this->revision($rows), ['filename' => 'menu.csv']);

        $this->assertSame('imported', $outcome['status']);
        $this->assertSame(2, $outcome['result']['sections_created']);
        $this->assertSame(3, $outcome['result']['categories_created']);
        $this->assertSame(4, $outcome['result']['items_created']);

        $food = DB::table('sections')->where('name', 'Food')->first();
        $mainCourse = DB::table('categories')->where('name', 'Main Course')->first();
        $jollof = DB::table('menu_items')->where('name', 'Jollof Rice')->first();
        $this->assertSame('food', $food->slug);
        $this->assertEquals(1, $food->is_active);
        $this->assertEquals($food->id, $mainCourse->section_id);
        $this->assertSame('main-course', $mainCourse->slug);
        $this->assertEquals($mainCourse->id, $jollof->category_id);
        $this->assertEquals('12000.00', number_format((float) $jollof->price, 2, '.', ''));
        $this->assertSame('Nigerian-style jollof rice served with grilled chicken', $jollof->description);
        $this->assertEquals(1, $jollof->is_available);
        $this->assertSame([1, 2], DB::table('menu_items')->where('category_id', DB::table('categories')->where('name', 'Starters')->value('id'))->orderBy('id')->pluck('display_order')->map(fn ($v) => (int) $v)->all());
        $this->assertSame(0, DB::table('sections')->where('restaurant_id', '!=', 1)->count());

        $log = DB::table('activity_logs')->first();
        $this->assertSame('menu_import', $log->action);
        $this->assertEquals(7, $log->actor_id);
        $this->assertStringContainsString('"items_created":4', $log->new_values);
    }

    public function test_reimporting_the_same_file_creates_nothing(): void
    {
        $rows = $this->sampleRows();
        $this->writer()->import(1, 7, $rows, $this->revision($rows));
        $counts = $this->counts();

        $second = $this->analyzer->analyze(1, $rows);
        $this->assertFalse($second['has_changes']);
        $this->assertSame(4, $second['summary']['items']['unchanged']);

        $outcome = $this->writer()->import(1, 7, $rows, $second['revision']);

        $this->assertSame('blocked', $outcome['status']);
        $this->assertSame($counts, $this->counts());
    }

    public function test_double_submit_with_the_same_revision_writes_once(): void
    {
        $rows = $this->sampleRows();
        $revision = $this->revision($rows);

        $this->assertSame('imported', $this->writer()->import(1, 7, $rows, $revision)['status']);
        $counts = $this->counts();

        $this->assertSame('stale', $this->writer()->import(1, 7, $rows, $revision)['status']);
        $this->assertSame($counts, $this->counts());
    }

    public function test_stale_revision_writes_nothing(): void
    {
        $rows = $this->sampleRows();
        $revision = $this->revision($rows);
        $this->makeSection('Food');

        $outcome = $this->writer()->import(1, 7, $rows, $revision);

        $this->assertSame('stale', $outcome['status']);
        $this->assertNotSame($revision, $outcome['analysis']['revision']);
        $this->assertSame(1, DB::table('sections')->count());
        $this->assertSame(0, DB::table('menu_items')->count());
    }

    public function test_failure_mid_write_rolls_everything_back(): void
    {
        $food = $this->makeSection('Food');
        $starters = $this->makeCategory($food, 'Starters');
        $this->makeItem($starters, 'Chicken Wings', '8500', 'Crispy');
        $before = $this->counts();

        $rows = [
            $this->row('Food', 'Starters', 'Chicken Wings', '', '9999', 2),
            $this->row('Desserts', 'Cakes', 'Cheesecake', '', '4000', 3),
        ];
        $orders = Mockery::mock(DisplayOrderService::class)->makePartial();
        $orders->shouldReceive('nextMenuItemOrder')->andThrow(new RuntimeException('disk full'));

        try {
            $this->writer(orders: $orders)->import(1, 7, $rows, $this->revision($rows));
            $this->fail('Expected the import to throw.');
        } catch (RuntimeException $e) {
            $this->assertSame('disk full', $e->getMessage());
        }

        $this->assertSame($before, $this->counts());
        $this->assertEquals('8500.00', number_format((float) DB::table('menu_items')->value('price'), 2, '.', ''));
        $this->assertSame(0, DB::table('activity_logs')->count());
    }

    public function test_update_changes_only_price_and_description(): void
    {
        $food = $this->makeSection('Food');
        $starters = $this->makeCategory($food, 'Starters');
        $wings = $this->makeItem($starters, 'Chicken Wings', '8500', 'Crispy wings', 1, ['image' => 'wings.jpg', 'is_available' => 0, 'display_order' => 7, 'slug' => 'chicken-wings']);
        $rolls = $this->makeItem($starters, 'Spring Rolls', '5000', 'Old copy');

        $rows = [
            $this->row('FOOD', 'starters', 'chicken wings', '', '9000', 2),
            $this->row('Food', 'Starters', 'Spring Rolls', 'New copy', '5000', 3),
        ];
        $outcome = $this->writer()->import(1, 7, $rows, $this->revision($rows));

        $this->assertSame(2, $outcome['result']['items_updated']);
        $this->assertSame(1, $outcome['result']['sections_reused']);
        $this->assertSame(1, $outcome['result']['categories_reused']);

        $w = DB::table('menu_items')->find($wings);
        $this->assertEquals('9000.00', number_format((float) $w->price, 2, '.', ''));
        $this->assertSame('Crispy wings', $w->description);
        $this->assertSame(['Chicken Wings', 'chicken-wings', 'wings.jpg', 0, 7], [$w->name, $w->slug, $w->image, (int) $w->is_available, (int) $w->display_order]);

        $r = DB::table('menu_items')->find($rolls);
        $this->assertSame('New copy', $r->description);
        $this->assertSame(1, DB::table('sections')->count());
        $this->assertSame(1, DB::table('categories')->count());
    }

    public function test_same_category_name_under_other_section_gets_a_new_category_and_unique_slug(): void
    {
        $food = $this->makeSection('Food');
        $foodPasta = $this->makeCategory($food, 'Pasta', 1, ['slug' => 'pasta']);
        $specials = $this->makeSection('Specials');

        $rows = [$this->row('Specials', 'Pasta', 'Carbonara', '', '7000')];
        $this->writer()->import(1, 7, $rows, $this->revision($rows));

        $new = DB::table('categories')->where('id', '!=', $foodPasta)->first();
        $this->assertSame('Pasta', $new->name);
        $this->assertEquals($specials, $new->section_id);
        $this->assertMatchesRegularExpression('/^pasta-[A-Za-z0-9]{4}$/', $new->slug);
        $this->assertEquals($food, DB::table('categories')->find($foodPasta)->section_id);
        $this->assertEquals($new->id, DB::table('menu_items')->where('name', 'Carbonara')->value('category_id'));
    }

    public function test_same_item_name_in_two_sections_creates_two_items(): void
    {
        $rows = [
            $this->row('Food', 'Main Course', 'Rice', '', '5000', 2),
            $this->row('Drinks', 'Main Course', 'Rice', '', '1500', 3),
        ];
        $this->writer()->import(1, 7, $rows, $this->revision($rows));

        $categories = DB::table('categories')->orderBy('id')->get();
        $this->assertCount(2, $categories);
        $this->assertNotEquals($categories[0]->section_id, $categories[1]->section_id);
        $this->assertSame('main-course', $categories[0]->slug);
        $this->assertMatchesRegularExpression('/^main-course-[A-Za-z0-9]{4}$/', $categories[1]->slug);

        $items = DB::table('menu_items')->orderBy('id')->get();
        $this->assertCount(2, $items);
        $this->assertNotSame($items[0]->slug, $items[1]->slug);
        $this->assertNotEquals($items[0]->category_id, $items[1]->category_id);
    }

    public function test_new_category_gets_next_display_order_within_its_section(): void
    {
        $food = $this->makeSection('Food');
        $this->makeCategory($food, 'Starters', 1, ['display_order' => 4]);
        $drinks = $this->makeSection('Drinks');
        $this->makeCategory($drinks, 'Soft', 1, ['display_order' => 9]);

        $rows = [$this->row('Food', 'Grill', 'Suya', '', '3000')];
        $this->writer()->import(1, 7, $rows, $this->revision($rows));

        $this->assertEquals(5, DB::table('categories')->where('name', 'Grill')->value('display_order'));
    }

    public function test_slug_generator_falls_back_when_the_name_has_no_latin_characters(): void
    {
        $slugs = new MenuImportSlugGenerator;
        $expected = Str::slug('🍕🍕') ?: 'section';

        $this->assertSame($expected, $slugs->forSection(1, '🍕🍕'));
        DB::table('sections')->insert(['restaurant_id' => 1, 'name' => 'x', 'slug' => $expected]);
        $this->assertMatchesRegularExpression('/^'.preg_quote($expected, '/').'-[A-Za-z0-9]{4}$/', $slugs->forSection(1, '🍕🍕'));
        $this->assertSame($expected, $slugs->forSection(2, '🍕🍕'));
    }

    private function writer(?DisplayOrderService $orders = null, ?PlanVisibilityService $visibility = null): MenuImportWriter
    {
        if (! $visibility) {
            $visibility = Mockery::mock(PlanVisibilityService::class);
            $visibility->shouldReceive('forgetCache');
        }

        return new MenuImportWriter(
            $this->analyzer,
            new MenuImportSlugGenerator,
            $orders ?? new DisplayOrderService,
            $visibility,
            new ActivityLogService,
        );
    }

    private function revision(array $rows): string
    {
        return $this->analyzer->analyze(1, $rows)['revision'];
    }

    private function sampleRows(): array
    {
        return (new MenuImportCsvReader)->readString(MenuImportFormat::sampleCsv())['rows'];
    }

    /** @return array<string, int> */
    private function counts(): array
    {
        return [
            'sections' => DB::table('sections')->count(),
            'categories' => DB::table('categories')->count(),
            'menu_items' => DB::table('menu_items')->count(),
        ];
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
}
