<?php

namespace App\Console\Commands;

use App\Support\MenuTemplateResolver;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

/**
 * Staging HTTP matrix for template previews (Pass 1 gate).
 */
class AuditTemplatePreviewsCommand extends Command
{
    protected $signature = 'templates:audit-previews {--base= : Base URL override (default app.url)}';

    protected $description = 'HTTP-check /templates/{id}/preview for API-listed and local template ids';

    public function handle(MenuTemplateResolver $resolver): int
    {
        $base = rtrim((string) ($this->option('base') ?: config('app.url')), '/');
        $this->info("Base: {$base}");

        $apiIds = [];
        try {
            $response = Http::timeout(15)->get($base.'/api/templates', ['limit' => 50]);
            if ($response->successful()) {
                $payload = $response->json();
                $rows = $payload['data'] ?? $payload ?? [];
                if (is_array($rows)) {
                    foreach ($rows as $row) {
                        if (is_array($row) && isset($row['id'])) {
                            $apiIds[] = (int) $row['id'];
                        }
                    }
                }
            } else {
                $this->warn('API /api/templates failed HTTP '.$response->status().' — using local template ids only.');
            }
        } catch (\Throwable $e) {
            $this->warn('API unreachable: '.$e->getMessage().' — using local template ids only.');
        }

        $ids = $apiIds !== [] ? array_values(array_unique($apiIds)) : $resolver->availableTemplateIds();
        sort($ids);

        $ok = 0;
        $fail = 0;
        $rows = [];

        foreach ($ids as $id) {
            $url = $base.'/templates/'.$id.'/preview';
            $status = 0;
            $notes = [];
            try {
                $res = Http::timeout(30)->get($url);
                $status = $res->status();
                $body = $res->body();
                if ($status === 200) {
                    $ok++;
                    if (str_contains($body, '/restaurant/template-preview/reservation')) {
                        $notes[] = 'WARN live reservation CTA';
                    }
                    if (str_contains($body, 'supportsOrdering') || preg_match('/checkoutUrl:\s*[^,\n]+template-preview/', $body)) {
                        // cart config may still be present when ordering disabled
                    }
                    if (! str_contains($body, 'data-template-preview-hero')) {
                        $notes[] = 'no hero marker yet';
                    }
                } else {
                    $fail++;
                    $notes[] = 'HTTP '.$status;
                }
            } catch (\Throwable $e) {
                $fail++;
                $notes[] = $e->getMessage();
            }
            $rows[] = [$id, $url, $status ?: 'ERR', implode('; ', $notes) ?: 'ok'];
        }

        // T7 sections
        foreach (['food', 'desserts', 'drinks'] as $slug) {
            $url = $base.'/templates/7/preview/'.$slug;
            try {
                $res = Http::timeout(30)->get($url);
                $rows[] = [7, $url, $res->status(), $res->successful() ? 'section ok' : 'section fail'];
                if (! $res->successful()) {
                    $fail++;
                } else {
                    $ok++;
                }
            } catch (\Throwable $e) {
                $fail++;
                $rows[] = [7, $url, 'ERR', $e->getMessage()];
            }
        }

        $this->table(['ID', 'URL', 'Status', 'Notes'], $rows);
        $this->line("Passed checks: {$ok}; failed: {$fail}");

        return $fail > 0 ? self::FAILURE : self::SUCCESS;
    }
}
