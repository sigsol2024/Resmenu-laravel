<?php

namespace Tests\Unit\MenuImport;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * In-memory SQLite copy of the menu tables from
 * database/schema/sigsolmenu_laravel.structure.sql, so these tests always run.
 */
trait MenuImportDatabase
{
    protected function setUpMenuDatabase(): void
    {
        config([
            'database.default' => 'menu_import_sqlite',
            'database.connections.menu_import_sqlite' => [
                'driver' => 'sqlite',
                'database' => ':memory:',
                'prefix' => '',
                'foreign_key_constraints' => false,
            ],
            'cache.default' => 'array',
        ]);
        DB::purge('menu_import_sqlite');
        Cache::flush();

        Schema::create('restaurants', function (Blueprint $t) {
            $t->increments('id');
            $t->string('name');
            $t->string('slug');
            $t->timestamps();
        });
        Schema::create('sections', function (Blueprint $t) {
            $t->increments('id');
            $t->integer('restaurant_id');
            $t->string('name');
            $t->string('slug');
            $t->string('image')->nullable();
            $t->integer('display_order')->default(0);
            $t->boolean('is_active')->default(true);
            $t->timestamps();
            $t->unique(['restaurant_id', 'slug']);
        });
        Schema::create('categories', function (Blueprint $t) {
            $t->increments('id');
            $t->integer('restaurant_id');
            $t->integer('section_id');
            $t->string('name');
            $t->string('slug');
            $t->string('image')->nullable();
            $t->text('description')->nullable();
            $t->integer('display_order')->default(0);
            $t->boolean('is_active')->default(true);
            $t->timestamps();
            $t->unique(['restaurant_id', 'slug']);
        });
        Schema::create('category_secondary_sections', function (Blueprint $t) {
            $t->increments('id');
            $t->integer('category_id');
            $t->integer('section_id');
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });
        Schema::create('menu_items', function (Blueprint $t) {
            $t->increments('id');
            $t->integer('restaurant_id');
            $t->integer('category_id');
            $t->string('name');
            $t->string('slug');
            $t->text('description')->nullable();
            $t->decimal('price', 10, 2);
            $t->string('image')->nullable();
            $t->integer('display_order')->default(0);
            $t->boolean('is_available')->default(true);
            $t->timestamps();
            $t->unique(['restaurant_id', 'category_id', 'slug']);
        });
        Schema::create('activity_logs', function (Blueprint $t) {
            $t->increments('id');
            $t->string('actor_type');
            $t->integer('actor_id')->nullable();
            $t->integer('restaurant_id')->nullable();
            $t->string('action');
            $t->string('subject_type')->nullable();
            $t->integer('subject_id')->nullable();
            $t->text('old_values')->nullable();
            $t->text('new_values')->nullable();
            $t->string('ip')->nullable();
            $t->string('user_agent', 512)->nullable();
            $t->timestamp('created_at')->nullable();
        });

        DB::table('restaurants')->insert([
            ['id' => 1, 'name' => 'Test Bistro', 'slug' => 'test-bistro'],
            ['id' => 2, 'name' => 'Other Place', 'slug' => 'other-place'],
        ]);
    }

    protected function makeSection(string $name, int $restaurantId = 1, array $extra = []): int
    {
        return (int) DB::table('sections')->insertGetId(array_merge([
            'restaurant_id' => $restaurantId,
            'name' => $name,
            'slug' => \Illuminate\Support\Str::slug($name).'-'.uniqid(),
            'display_order' => 1,
            'is_active' => 1,
        ], $extra));
    }

    protected function makeCategory(int $sectionId, string $name, int $restaurantId = 1, array $extra = []): int
    {
        return (int) DB::table('categories')->insertGetId(array_merge([
            'restaurant_id' => $restaurantId,
            'section_id' => $sectionId,
            'name' => $name,
            'slug' => \Illuminate\Support\Str::slug($name),
            'display_order' => 1,
            'is_active' => 1,
        ], $extra));
    }

    protected function makeItem(int $categoryId, string $name, string $price, ?string $description = null, int $restaurantId = 1, array $extra = []): int
    {
        return (int) DB::table('menu_items')->insertGetId(array_merge([
            'restaurant_id' => $restaurantId,
            'category_id' => $categoryId,
            'name' => $name,
            'slug' => \Illuminate\Support\Str::slug($name).'-'.uniqid(),
            'description' => $description,
            'price' => $price,
            'display_order' => 1,
            'is_available' => 1,
        ], $extra));
    }
}
