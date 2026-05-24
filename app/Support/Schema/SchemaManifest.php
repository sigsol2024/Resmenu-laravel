<?php

namespace App\Support\Schema;

use Illuminate\Support\Facades\File;

class SchemaManifest
{
    public const VERSION = 'schema-baseline-v1';

    public static function path(): string
    {
        return database_path('schema/MIGRATION_MANIFEST.json');
    }

    /**
     * @return array{version: string, tables: array<string, array>}
     */
    public static function load(): array
    {
        $path = self::path();
        if (! File::exists($path)) {
            throw new \RuntimeException("Schema manifest not found: {$path}");
        }

        $data = json_decode(File::get($path), true, 512, JSON_THROW_ON_ERROR);

        return $data;
    }

    /**
     * @return list<string>
     */
    public static function tableNames(): array
    {
        $manifest = self::load();

        return array_keys($manifest['tables'] ?? []);
    }
}
