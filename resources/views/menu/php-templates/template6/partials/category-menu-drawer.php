<?php
$section = $activeSection ?? null;
$drawerCategories = $section['categories'] ?? [];
if (($menuViewLevel ?? '') !== 'section' || empty($drawerCategories)) {
    return;
}
$drawerSectionName = $section['name'] ?? 'Menu';
$drawerSectionSlug = $section['slug'] ?? '';
?>
<div id="t6-menu-backdrop" class="hidden fixed inset-0 z-[90] bg-surface/60 backdrop-blur-sm" aria-hidden="true"></div>

<aside id="t6-menu-drawer" class="hidden fixed right-0 top-0 h-full w-full sm:w-96 md:w-[420px] bg-surface-container-highest shadow-2xl z-[100] border-l border-outline-variant/10 overflow-y-auto hide-scrollbar" aria-labelledby="t6-menu-drawer-title" role="dialog" aria-modal="true">
<div class="px-gutter py-4 flex items-center justify-between sticky top-0 bg-surface-container-highest/95 backdrop-blur-md z-20 border-b border-outline-variant/10">
<div>
<h2 id="t6-menu-drawer-title" class="font-headline-md text-headline-md text-primary serif"><?php echo t6_esc($drawerSectionName); ?></h2>
<p class="font-label-md text-label-md text-on-surface-variant/70 uppercase">Categories</p>
</div>
<button type="button" id="t6-menu-close" class="material-symbols-outlined text-on-surface-variant hover:text-primary transition-colors text-3xl" aria-label="Close menu">close</button>
</div>

<div class="px-gutter py-4 space-y-1">
<a href="<?php echo t6_esc($fullMenuUrl ?? '#'); ?>" class="flex items-center gap-3 py-2.5 px-3 rounded-lg text-on-surface-variant hover:text-primary hover:bg-surface-container-high transition-colors font-label-md text-label-md uppercase">
<span class="material-symbols-outlined text-lg text-primary">home</span>
All Sections
</a>
</div>

<div class="px-gutter pb-gutter space-y-1">
<?php foreach ($drawerCategories as $i => $cat):
    $cslug = $cat['slug'] ?? ('cat-'.$i);
    $catUrl = t6_category_url($fullMenuUrl ?? '', $drawerSectionSlug, $cslug);
?>
<a href="<?php echo t6_esc($catUrl); ?>" class="group block py-2.5 px-3 rounded-lg font-label-lg text-label-lg text-on-surface hover:text-primary hover:bg-surface-container-high transition-colors">
<?php echo t6_esc($cat['name'] ?? ''); ?>
</a>
<?php endforeach; ?>
</div>
</aside>
