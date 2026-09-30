<?php

namespace Tests\Unit\MenuImport;

use App\Http\Controllers\Manager\CategoryController;
use App\Http\Controllers\Manager\MenuItemController;
use App\Http\Controllers\Manager\SectionController;
use App\Services\MenuImport\MenuImportRowRules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use ReflectionMethod;
use Tests\TestCase;

/**
 * The importer keeps its own copy of the manual controllers' field rules. This
 * runs the real controller validation and the importer's base rules on the
 * same inputs; if someone changes the manual rules, this fails.
 */
class MenuImportRowRulesParityTest extends TestCase
{
    use MenuImportDatabase;

    private int $sectionId;

    private int $categoryId;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpMenuDatabase();
        $this->sectionId = $this->makeSection('Food');
        $this->categoryId = $this->makeCategory($this->sectionId, 'Starters');
    }

    public function test_menu_item_rules_match_menu_item_controller(): void
    {
        foreach ($this->nameCases() as $name) {
            $this->assertParity('name', 'name', $name, fn ($v) => $this->menuItemFails(['name' => $v]));
        }
        foreach ($this->descriptionCases() as $description) {
            $this->assertParity('description', 'description', $description, fn ($v) => $this->menuItemFails(['description' => $v]));
        }
        foreach ($this->priceCases() as $price) {
            $this->assertParity('price', 'price', $price, fn ($v) => $this->menuItemFails(['price' => $v]));
        }
    }

    public function test_category_rules_match_category_controller(): void
    {
        foreach ($this->nameCases() as $name) {
            $this->assertParity('category', 'name', $name, fn ($v) => $this->categoryFails(['name' => $v]));
        }
        foreach ($this->descriptionCases() as $description) {
            $this->assertParity('description', 'description', $description, fn ($v) => $this->categoryFails(['description' => $v]));
        }
    }

    public function test_section_rules_match_section_controller(): void
    {
        foreach ($this->nameCases() as $name) {
            $this->assertParity('section', 'name', $name, fn ($v) => $this->sectionFails(['name' => $v]));
        }
    }

    private function assertParity(string $importField, string $controllerField, mixed $value, callable $controllerFailures): void
    {
        $importRejects = Validator::make(
            [$importField => $value],
            [$importField => MenuImportRowRules::baseRules()[$importField]],
        )->fails();
        $controllerRejects = in_array($controllerField, $controllerFailures($value), true);

        $this->assertSame(
            $controllerRejects,
            $importRejects,
            sprintf('Rule drift on "%s" for value %s', $importField, var_export(is_string($value) ? mb_substr($value, 0, 20) : $value, true)),
        );
    }

    /** @return list<string> */
    private function menuItemFails(array $override): array
    {
        return $this->controllerFailures(MenuItemController::class, array_merge([
            'name' => 'Valid',
            'category_id' => $this->categoryId,
            'description' => 'ok',
            'price' => '10',
        ], $override));
    }

    /** @return list<string> */
    private function categoryFails(array $override): array
    {
        return $this->controllerFailures(CategoryController::class, array_merge([
            'name' => 'Valid',
            'section_id' => $this->sectionId,
            'description' => 'ok',
        ], $override));
    }

    /** @return list<string> */
    private function sectionFails(array $override): array
    {
        return $this->controllerFailures(SectionController::class, array_merge(['name' => 'Valid'], $override));
    }

    /** @return list<string> */
    private function controllerFailures(string $controllerClass, array $input): array
    {
        $controller = $this->app->make($controllerClass);
        $method = new ReflectionMethod($controller, 'validated');
        $request = Request::create('/', 'POST', $input);

        try {
            $method->invoke($controller, $request, 1);
        } catch (ValidationException $e) {
            return array_keys($e->errors());
        }

        return [];
    }

    /** @return list<mixed> */
    private function nameCases(): array
    {
        return ['', 'Rice', 'Jollof Rice & Chicken', str_repeat('a', 255), str_repeat('a', 256), str_repeat('é', 255), str_repeat('é', 256), '名前'];
    }

    /** @return list<mixed> */
    private function descriptionCases(): array
    {
        return [null, '', 'Tasty', str_repeat('x', 5000)];
    }

    /** @return list<mixed> */
    private function priceCases(): array
    {
        return ['', '0', '8500', '8500.50', '0.5', '-1', 'abc', '1e3', '99999999999'];
    }
}
