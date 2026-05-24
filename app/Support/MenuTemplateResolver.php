<?php

namespace App\Support;

class MenuTemplateResolver
{
    /** Blade-native templates (preferred when present). Empty — use PHP templates for cart/ordering parity. */
    private const BLADE_VIEWS = [];

    public function bladeViewFor(int $templateId): ?string
    {
        return self::BLADE_VIEWS[$templateId] ?? null;
    }

    public function hasBladeView(int $templateId): bool
    {
        return isset(self::BLADE_VIEWS[$templateId]);
    }

    public function hasPhpTemplate(int $templateId): bool
    {
        $templateId = max(1, (int) $templateId);
        $path = resource_path('views/menu/php-templates/template'.$templateId.'/index.php');

        return is_file($path);
    }

    public function supportsTemplate(int $templateId): bool
    {
        return $this->hasBladeView($templateId) || $this->hasPhpTemplate($templateId);
    }

    /** @return list<int> */
    public function availableTemplateIds(): array
    {
        $ids = array_keys(self::BLADE_VIEWS);
        $dir = resource_path('views/menu/php-templates');
        if (is_dir($dir)) {
            foreach (glob($dir.'/template*/index.php') ?: [] as $file) {
                if (preg_match('/template(\d+)/', $file, $m)) {
                    $ids[] = (int) $m[1];
                }
            }
        }

        $ids = array_values(array_unique($ids));
        sort($ids);

        return $ids;
    }
}
