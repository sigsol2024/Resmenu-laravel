<?php
$drawerSections = $sections ?? [];
if (($menuViewLevel ?? '') !== 'home' || empty($drawerSections)) {
    return;
}
$drawerRestName = trim((string) ($restaurant['name'] ?? 'Menu'));
?>
<div id="t6-menu-backdrop" class="hidden fixed inset-0 z-[90] bg-surface/60 backdrop-blur-sm" aria-hidden="true"></div>

<aside id="t6-menu-drawer" class="hidden fixed right-0 top-0 h-full w-full sm:w-96 md:w-[420px] bg-surface-container-highest shadow-2xl z-[100] border-l border-outline-variant/10 overflow-y-auto hide-scrollbar" aria-labelledby="t6-menu-drawer-title" role="dialog" aria-modal="true">
<div class="px-gutter py-4 flex items-center justify-between sticky top-0 bg-surface-container-highest/95 backdrop-blur-md z-20 border-b border-outline-variant/10">
<div>
<h2 id="t6-menu-drawer-title" class="font-headline-md text-headline-md text-primary serif"><?php echo t6_esc($drawerRestName); ?></h2>
<p class="font-label-md text-label-md text-on-surface-variant/70 uppercase">Sections</p>
</div>
<button type="button" id="t6-menu-close" class="material-symbols-outlined text-on-surface-variant hover:text-primary transition-colors text-3xl" aria-label="Close menu">close</button>
</div>

<div class="px-gutter py-4 pb-gutter space-y-1">
<?php foreach ($drawerSections as $section):
    $secUrl = t6_section_url($fullMenuUrl ?? '', $section['slug'] ?? '');
?>
<a href="<?php echo t6_esc($secUrl); ?>" class="block py-2.5 px-3 rounded-lg font-label-lg text-label-lg text-on-surface hover:text-primary hover:bg-surface-container-high transition-colors">
<?php echo t6_esc($section['name'] ?? ''); ?>
</a>
<?php endforeach; ?>
</div>
</aside>
