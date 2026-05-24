<?php

namespace Tests\Feature;

use Tests\TestCase;

class NoLegacyReferencesTest extends TestCase
{
    public function test_app_code_has_no_legacy_runner_references(): void
    {
        $paths = [
            app_path(),
            base_path('routes'),
            base_path('config'),
        ];

        foreach ($paths as $path) {
            $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));
            foreach ($iterator as $file) {
                if (! $file->isFile() || $file->getExtension() !== 'php') {
                    continue;
                }
                $contents = file_get_contents($file->getPathname());
                $this->assertStringNotContainsString('LegacyPhpRunner', $contents, $file->getPathname());
                $this->assertStringNotContainsString("config('features", $contents, $file->getPathname());
                $this->assertStringNotContainsString('../Resmenu', $contents, $file->getPathname());
            }
        }
    }
}
