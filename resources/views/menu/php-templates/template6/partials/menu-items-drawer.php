<?php
$drawerCategory = $activeCategory ?? null;
$drawerItems = $drawerCategory['menu_items'] ?? [];
if (($menuViewLevel ?? '') !== 'category' || empty($drawerItems)) {
    return;
}
$drawerCatName = $drawerCategory['name'] ?? 'Menu';
?>
<div id="t6-menu-backdrop" class="hidden fixed inset-0 z-[90] bg-surface/60 backdrop-blur-sm" aria-hidden="true"></div>

<aside id="t6-menu-drawer" class="hidden fixed right-0 top-0 h-full w-full sm:w-96 md:w-[420px] bg-surface-container-highest shadow-2xl z-[100] border-l border-outline-variant/10 overflow-y-auto hide-scrollbar" aria-labelledby="t6-menu-drawer-title" role="dialog" aria-modal="true">
<div class="px-gutter py-4 flex items-center justify-between sticky top-0 bg-surface-container-highest/95 backdrop-blur-md z-20 border-b border-outline-variant/10">
<div>
<h2 id="t6-menu-drawer-title" class="font-headline-md text-headline-md text-primary serif"><?php echo t6_esc($drawerCatName); ?></h2>
<p class="font-label-md text-label-md text-on-surface-variant/70 uppercase">Menu Items</p>
</div>
<button type="button" id="t6-menu-close" class="material-symbols-outlined text-on-surface-variant hover:text-primary transition-colors text-3xl" aria-label="Close menu">close</button>
</div>

<?php if (! empty($sectionMenuUrl)): ?>
<div class="px-gutter py-4 space-y-1 border-b border-outline-variant/10">
<a href="<?php echo t6_esc($sectionMenuUrl); ?>" class="flex items-center gap-3 py-2.5 px-3 rounded-lg text-on-surface-variant hover:text-primary hover:bg-surface-container-high transition-colors font-label-md text-label-md uppercase">
<span class="material-symbols-outlined text-lg text-primary">arrow_back</span>
Back to categories
</a>
</div>
<?php endif; ?>

<div class="px-gutter py-4 pb-gutter space-y-1">
<?php foreach ($drawerItems as $item):
    $anchor = t6_item_anchor($item);
    $price = t6_price($item['price'] ?? 0);
?>
<a href="#<?php echo t6_esc($anchor); ?>" class="t6-menu-item-jump group flex items-center justify-between py-3 px-3 rounded-lg bg-surface-container-high/50 hover:bg-surface-container-high border border-transparent hover:border-outline-variant/20 transition-all">
<div class="min-w-0">
<span class="font-label-lg text-label-lg text-on-surface block group-hover:text-primary transition-colors"><?php echo t6_esc($item['name'] ?? ''); ?></span>
<?php if (! empty($item['description'])): ?>
<span class="font-body-sm text-body-sm text-on-surface-variant line-clamp-1"><?php echo t6_esc($item['description']); ?></span>
<?php endif; ?>
</div>
<span class="font-label-md text-label-md text-primary shrink-0 ml-2"><?php echo t6_esc($price); ?></span>
</a>
<?php endforeach; ?>
</div>
</aside>
