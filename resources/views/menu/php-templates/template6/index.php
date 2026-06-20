<?php
/**
 * Template 6 — Lusso three-page menu (sections → categories → items)
 */
require_once __DIR__.'/helpers.php';

$menuViewLevel = $menuViewLevel ?? 'home';
$GLOBALS['t6_is_template_preview'] = ! empty($isTemplatePreview);
$uploadBaseUrl = $uploadBaseUrl ?? (defined('UPLOAD_URL') ? rtrim(UPLOAD_URL, '/') : '');
$popularItems = $popularItems ?? [];
$t6BackUrl = null;
if ($menuViewLevel === 'section' && ! empty($fullMenuUrl)) {
    $t6BackUrl = $fullMenuUrl;
} elseif ($menuViewLevel === 'category' && ! empty($sectionMenuUrl)) {
    $t6BackUrl = $sectionMenuUrl;
}

$pageTitle = $restaurant['name'] ?? 'Menu';
if ($menuViewLevel === 'section' && ! empty($activeSection['name'])) {
    $pageTitle .= ' | '.$activeSection['name'];
} elseif ($menuViewLevel === 'category' && ! empty($activeCategory['name'])) {
    $pageTitle .= ' | '.$activeCategory['name'];
}

include __DIR__.'/partials/head.php';
include __DIR__.'/partials/header.php';

switch ($menuViewLevel) {
    case 'section':
        include __DIR__.'/views/section.php';
        break;
    case 'category':
        include __DIR__.'/views/category.php';
        break;
    default:
        include __DIR__.'/views/home.php';
        break;
}

include __DIR__.'/partials/side-dock.php';
if (($menuViewLevel ?? '') === 'section') {
    include __DIR__.'/partials/category-menu-drawer.php';
}
include __DIR__.'/partials/footer.php';
include __DIR__.'/partials/cart.php';
include __DIR__.'/partials/scripts.php';
