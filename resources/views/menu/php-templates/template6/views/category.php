<?php
$category = $activeCategory ?? null;
$items = $category['menu_items'] ?? [];
$itemCount = count($items);
$catName = $category['name'] ?? 'Menu';
?>
<main class="t6-main-offset pb-12 md:pb-xl px-4 md:px-gutter max-w-container-max mx-auto min-h-screen">
<section class="flex flex-col md:flex-row md:items-end justify-between gap-md mb-lg md:mb-xl">
<div>
<h1 class="font-display-lg text-headline-lg md:text-headline-xl serif mb-2 text-primary"><?php echo t6_esc($catName); ?></h1>
<p class="font-body-md text-body-md text-on-surface-variant/80"><?php echo (int) $itemCount; ?> Selection<?php echo $itemCount === 1 ? '' : 's'; ?><?php if (! empty($category['description'])): ?> &bull; <?php echo t6_esc($category['description']); ?><?php endif; ?></p>
</div>
<div class="max-w-xl w-full md:w-auto relative group">
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
<article class="t6-menu-item-card t6-category-box group flex flex-col rounded-xl border border-outline-variant/10 premium-shadow overflow-hidden bg-surface-container-high" data-t6-searchable data-t6-search-text="<?php echo t6_esc(($item['name'] ?? '').' '.($item['description'] ?? '')); ?>">
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
<div class="mt-4 pt-3 border-t border-outline-variant/10">
<?php if ($canOrder): ?>
<button type="button" class="add-to-bag-btn w-full inline-flex items-center justify-center gap-2 font-label-md text-label-md uppercase text-on-primary bg-primary px-4 py-2.5 rounded-lg hover:opacity-90 active:scale-[0.98] transition-all" data-item-id="<?php echo (int) ($item['id'] ?? 0); ?>" data-item-name="<?php echo t6_esc($item['name'] ?? ''); ?>" data-item-price="<?php echo t6_esc((string) ($item['price'] ?? '')); ?>" data-item-image="<?php echo t6_esc($item['image'] ?? ''); ?>">
<span class="material-symbols-outlined text-lg">add_shopping_cart</span>
Add to bag
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
