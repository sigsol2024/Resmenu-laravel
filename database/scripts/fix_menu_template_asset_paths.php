<?php
/** Fix menu template asset paths to use /legacy/assets/ */
$dir = __DIR__ . '/../../resources/views/menu/php-templates';
$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
$count = 0;
foreach ($it as $file) {
    if (!$file->isFile() || $file->getExtension() !== 'php') {
        continue;
    }
    $path = $file->getPathname();
    $content = file_get_contents($path);
    $updated = str_replace('/assets/css/cart-', '/legacy/assets/css/cart-', $content);
    $updated = str_replace('/assets/css/cart-widget-standalone.css', '/legacy/assets/css/cart-widget-standalone.css', $updated);
    if ($updated !== $content) {
        file_put_contents($path, $updated);
        $count++;
    }
}
echo "Updated {$count} template files\n";
