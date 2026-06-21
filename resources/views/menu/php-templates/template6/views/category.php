<?php
$category = $activeCategory ?? null;
$items = $category['menu_items'] ?? [];
$itemCount = count($items);
$catName = $category['name'] ?? 'Menu';
$catHeroUrl = ! empty($category) ? t6_category_image($uploadBaseUrl ?? '', $category) : null;
$catSubtitle = (int) $itemCount.' Selection'.($itemCount === 1 ? '' : 's');
if (! empty($category['description'])) {
    $catSubtitle .= ' • '.$category['description'];
}
?>
<?php if ($catHeroUrl): ?>
<section class="relative w-full h-[38vh] md:h-[42vh] min-h-[220px] max-h-[440px] overflow-hidden">
<img class="absolute inset-0 w-full h-full object-cover" alt="<?php echo t6_esc($catName); ?>" src="<?php echo t6_esc($catHeroUrl); ?>" loading="eager">
<div class="absolute inset-0 lusso-gradient"></div>
<div class="absolute inset-0 bg-surface/30"></div>
<div class="absolute bottom-0 left-0 right-0 px-4 md:px-gutter pb-8 md:pb-10 pt-24 max-w-container-max mx-auto">
<h1 class="font-display-lg text-headline-md md:text-headline-lg text-primary serif mb-2"><?php echo t6_esc($catName); ?></h1>
<p class="font-body-md md:text-body-lg text-on-surface-variant max-w-xl"><?php echo t6_esc($catSubtitle); ?></p>
</div>
</section>
<?php endif; ?>

<main class="<?php echo $catHeroUrl ? 't6-main-after-hero' : 't6-main-offset'; ?> pb-12 md:pb-xl px-4 md:px-gutter max-w-container-max mx-auto min-h-screen">
<?php if (! $catHeroUrl): ?>
<section class="mb-md md:mb-lg">
<div class="max-w-2xl">
<h1 class="font-display-lg text-headline-md md:text-headline-lg mb-2 text-primary serif"><?php echo t6_esc($catName); ?></h1>
<p class="font-body-md text-body-md text-on-surface-variant/80"><?php echo t6_esc($catSubtitle); ?></p>
</div>
</section>
<?php endif; ?>

<section class="mb-lg md:mb-xl">
<div class="max-w-xl w-full md:ml-auto relative group">
<span class="absolute left-4 top-1/2 -translate-y-1/2 text-primary/60 group-focus-within:text-primary transition-colors">
<span class="material-symbols-outlined">search</span>
</span>
<input type="search" class="t6-page-search w-full h-12 md:h-14 pl-12 pr-4 bg-surface-container-low border-b border-primary/20 focus:border-primary focus:ring-0 text-on-surface font-body-md outline-none rounded-lg" placeholder="Search dishes..." autocomplete="off">
</div>
</section>

<?php if (empty($items)): ?>
<p class="text-center text-on-surface-variant py-xl font-body-lg">No items available in this category yet.</p>
<?php else: ?>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-lg">
<?php foreach ($items as $item):
    $img = t6_item_image($uploadBaseUrl ?? '', $item);
    $itemAvailable = ! isset($item['is_available']) || $item['is_available'];
    $canOrder = ! empty($supportsOrdering) && $itemAvailable;
?>
<article id="<?php echo t6_esc(t6_item_anchor($item)); ?>" class="t6-menu-item-card t6-category-box group flex flex-col rounded-xl border border-outline-variant/10 premium-shadow overflow-hidden bg-surface-container-high scroll-mt-28" data-t6-searchable data-t6-search-text="<?php echo t6_esc(($item['name'] ?? '').' '.($item['description'] ?? '')); ?>">
<?php if ($img): ?>
<div class="relative h-32 sm:h-36 md:h-40 overflow-hidden shrink-0 border-b border-outline-variant/10 bg-surface-container">
<img class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="<?php echo t6_esc($item['name'] ?? ''); ?>" src="<?php echo t6_esc($img); ?>" loading="lazy">
</div>
<?php else: ?>
<div class="relative h-28 sm:h-32 md:h-36 shrink-0 flex items-center justify-center border-b border-primary/10 bg-gradient-to-br from-surface-container-high via-surface-container to-surface-container-low">
<span class="material-symbols-outlined text-primary/40 text-3xl md:text-4xl">restaurant</span>
</div>
<?php endif; ?>
<div class="p-4 md:p-5 flex flex-col flex-1">
<div class="flex justify-between items-start gap-3 mb-2">
<h3 class="font-headline-md text-headline-md serif text-on-surface group-hover:text-primary transition-colors leading-tight min-w-0 flex-1"><?php echo t6_esc($item['name'] ?? ''); ?></h3>
<span class="font-label-lg text-label-lg text-primary shrink-0 pt-0.5"><?php echo t6_price($item['price'] ?? 0); ?></span>
</div>
<?php if (! empty($item['description'])): ?>
<p class="font-body-sm text-body-sm text-on-surface-variant text-justify leading-relaxed"><?php echo t6_esc($item['description']); ?></p>
<?php endif; ?>
<?php if (! empty($supportsOrdering)): ?>
<div class="mt-3 pt-2 border-t border-outline-variant/10">
<?php if ($canOrder): ?>
<button type="button" class="add-to-bag-btn inline-flex items-center gap-1 text-[10px] leading-tight font-semibold uppercase tracking-wide text-on-primary bg-primary px-2 py-0.5 rounded hover:opacity-90 active:scale-[0.98] transition-all" data-item-id="<?php echo (int) ($item['id'] ?? 0); ?>" data-item-name="<?php echo t6_esc($item['name'] ?? ''); ?>" data-item-price="<?php echo t6_esc((string) ($item['price'] ?? '')); ?>" data-item-image="<?php echo t6_esc($item['image'] ?? ''); ?>">
<span class="material-symbols-outlined text-[13px]">add_shopping_cart</span>
Add
</button>
<?php else: ?>
<p class="font-label-md text-label-md uppercase text-on-surface-variant/70 text-center">Currently unavailable</p>
<?php endif; ?>
</div>
<?php endif; ?>
</div>
</article>
<?php endforeach; ?>
</div>
<?php endif; ?>
</main>
<script>
(function(){
    var inp = document.querySelector('.t6-page-search');
    if (!inp) return;
    inp.addEventListener('input', function() {
        var q = this.value.toLowerCase().trim();
        document.querySelectorAll('[data-t6-searchable]').forEach(function(el) {
            var text = (el.getAttribute('data-t6-search-text') || '').toLowerCase();
            el.style.display = (!q || text.indexOf(q) !== -1) ? '' : 'none';
        });
    });
})();
</script>
