<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class SeedSqlSeeder extends Seeder
{
    public function run(): void
    {
        $manifestPath = database_path('seed-sql/manifest.json');
        if (! File::exists($manifestPath)) {
            $this->command?->warn('seed-sql/manifest.json not found; skipping.');

            return;
        }

        /** @var array{files: list<string>, restaurants?: array<string, array{lookup: string, field: string}>} $manifest */
        $manifest = json_decode(File::get($manifestPath), true, 512, JSON_THROW_ON_ERROR);

        foreach ($manifest['files'] as $relative) {
            if (str_starts_with($relative, 'restaurants/')) {
                $this->assertRestaurantDependency($relative, $manifest['restaurants'] ?? []);
            }
            $this->runFile($relative, useTransaction: $this->shouldUseTransaction($relative));
        }
    }

    /**
     * @param  array<string, array{lookup: string, field: string}>  $restaurantManifest
     */
    private function assertRestaurantDependency(string $relative, array $restaurantManifest): void
    {
        $basename = basename($relative);
        $meta = $restaurantManifest[$basename] ?? null;
        if ($meta === null) {
            return;
        }

        $lookup = $meta['lookup'];
        $field = $meta['field'] ?? 'email';

        $exists = match ($field) {
            'email' => DB::table('restaurants')->where('email', $lookup)->orWhere('manager_email', $lookup)->exists(),
            'slug' => DB::table('restaurants')->where('slug', $lookup)->exists(),
            default => throw new RuntimeException("Unknown restaurant lookup field: {$field}"),
        };

        if (! $exists) {
            throw new RuntimeException(
                "Restaurant seed blocked: {$relative} requires restaurants.{$field} = '{$lookup}'. "
                .'Run _shared/00_restaurants_bootstrap.sql first (included in manifest) or import production data.'
            );
        }
    }

    public function runFile(string $relative, bool $useTransaction = false): void
    {
        $path = database_path('seed-sql/'.str_replace(['../', '..\\'], '', $relative));
        if (! File::exists($path)) {
            throw new RuntimeException("Seed SQL file not found: {$path}");
        }

        $sql = File::get($path);
        $this->command?->info("Seeding: {$relative}");

        $runner = function () use ($sql): void {
            foreach ($this->splitStatements($sql) as $statement) {
                if (trim($statement) === '') {
                    continue;
                }
                DB::unprepared($statement);
            }
        };

        if ($useTransaction) {
            DB::transaction($runner);
        } else {
            $runner();
        }

        Log::info('seed-sql executed', ['file' => $relative]);
    }

    private function shouldUseTransaction(string $relative): bool
    {
        return str_starts_with($relative, '_shared/');
    }

    /**
     * @return list<string>
     */
    private function splitStatements(string $sql): array
    {
        $sql = preg_replace('/^--.*$/m', '', $sql) ?? $sql;
        $parts = preg_split('/;\s*\n/', $sql) ?: [];
        $statements = [];
        foreach ($parts as $part) {
            $part = trim($part);
            if ($part === '') {
                continue;
            }
            $statements[] = str_ends_with($part, ';') ? $part : $part.';';
        }

        return $statements;
    }
}
