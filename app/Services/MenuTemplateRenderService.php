<?php

namespace App\Services;

use App\Support\LegacyMenuViewData;
use App\Support\MenuViewHelpers;
use Symfony\Component\HttpFoundation\Response;

/**
 * Renders menu templates shipped under resources/views/menu/php-templates (ported from legacy).
 */
class MenuTemplateRenderService
{
    public function render(int $templateId, array $viewData): Response
    {
        $viewData = LegacyMenuViewData::normalize($viewData);
        MenuViewHelpers::register($viewData);

        $templateId = max(1, (int) $templateId);
        $path = resource_path('views/menu/php-templates/template'.$templateId.'/index.php');

        if (! is_file($path)) {
            $path = resource_path('views/menu/php-templates/template1/index.php');
        }

        $uploadBaseUrl = rtrim((string) ($viewData['uploadBaseUrl'] ?? config('resmenu.upload_url')), '/');
        $templateAssetBaseUrl = rtrim((string) ($viewData['templateAssetBaseUrl'] ?? (config('app.url').'/templates/template'.$templateId)), '/');
        ${'template'.$templateId.'BaseUrl'} = $templateAssetBaseUrl;

        extract($viewData, EXTR_SKIP);

        ob_start();
        try {
            include $path;
            $html = ob_get_clean() ?: '';
            $html = $this->normalizeUploadUrls($html, $uploadBaseUrl);
        } catch (\Throwable $e) {
            if (ob_get_level() > 0) {
                ob_end_clean();
            }
            throw $e;
        }

        return response($html, 200)->header('Content-Type', 'text/html; charset=UTF-8');
    }

    private function normalizeUploadUrls(string $html, string $uploadBaseUrl): string
    {
        return (string) preg_replace(
            '#https?://[^"\'\s]*/uploads/(?=(?:menu-items|heroes|logos|categories|sections|site)/)#',
            $uploadBaseUrl.'/',
            $html
        );
    }
}
