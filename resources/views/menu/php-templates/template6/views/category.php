<?php
$category = $activeCategory ?? null;
$items = $category['menu_items'] ?? [];
$itemCount = count($items);
$catName = $category['name'] ?? 'Menu';
$sectionSlug = $activeSection['slug'] ?? '';
?>
<main class="pt-20 md:pt-28 pb-12 md:pb-xl px-4 md:px-gutter max-w-container-max mx-auto min-h-screen">
<section class="flex flex-col md:flex-row md:items-end justify-between gap-md mb-lg">
<div>
<?php if (! empty($sectionMenuUrl)): ?>
<a href="<?php echo t6_esc($sectionMenuUrl); ?>" class="text-on-surface-variant hover:text-primary font-label-md text-label-md mb-2 inline-flex items-center gap-1">
<span class="material-symbols-outlined text-sm">arrow_back</span> Back to categories
</a>
<?php endif; ?>
<h1 class="font-display-lg text-display-lg-mobile md:text-display-lg serif mb-xs"><?php echo t6_esc($catName); ?></h1>
<p class="font-body-md text-body-md text-on-surface-variant/80 italic"><?php echo (int) $itemCount; ?> Selection<?php echo $itemCount === 1 ? '' : 's'; ?><?php if (! empty($category['description'])): ?> &bull; <?php echo t6_esc($category['description']); ?><?php endif; ?></p>
</div>
<div class="max-w-md w-full md:w-auto relative group">
<span class="absolute left-4 top-1/2 -translate-y-1/2 text-primary/60"><span class="material-symbols-outlined">search</span></span>
<input type="search" class="t6-page-search w-full h-12 pl-12 pr-4 bg-surface-container-low border-b border-primary/20 focus:border-primary focus:ring-0 text-on-surface font-body-md outline-none rounded-lg" placeholder="Search dishes..." autocomplete="off">
</div>
</section>

<?php if (empty($items)): ?>
<p class="text-center text-on-surface-variant py-xl font-body-lg">No items available in this category yet.</p>
<?php else: ?>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-md lg:gap-lg">
<?php foreach ($items as $item):
    $img = t6_item_image($uploadBaseUrl ?? '', $item);
    $itemAvailable = ! isset($item['is_available']) || $item['is_available'];
?>
<div class="group card-container relative" data-t6-searchable data-t6-search-text="<?php echo t6_esc(($item['name'] ?? '').' '.($item['description'] ?? '')); ?>">
<div class="relative overflow-hidden rounded-xl bg-surface-container aspect-[4/5] mb-md premium-shadow">
<?php if ($img): ?>
<img class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="<?php echo t6_esc($item['name'] ?? ''); ?>" src="<?php echo t6_esc($img); ?>" loading="lazy">
<?php else: ?>
<div class="w-full h-full bg-surface-container-high flex items-center justify-center text-on-surface-variant font-label-md">No image</div>
<?php endif; ?>
<div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-60"></div>
<?php if (! empty($supportsOrdering) && $itemAvailable): ?>
<button type="button" class="add-to-bag-btn absolute top-md right-md w-12 h-12 rounded-full bg-primary text-on-primary flex items-center justify-center transition-transform hover:scale-110 active:scale-90 shadow-xl z-10" data-item-id="<?php echo (int) ($item['id'] ?? 0); ?>" data-item-name="<?php echo t6_esc($item['name'] ?? ''); ?>" data-item-price="<?php echo t6_esc((string) ($item['price'] ?? '')); ?>" data-item-image="<?php echo t6_esc($item['image'] ?? ''); ?>" aria-label="Add to bag">
<span class="material-symbols-outlined">add</span>
</button>
<?php endif; ?>
</div>
<div class="flex justify-between items-start gap-4">
<div>
<h3 class="font-headline-md text-headline-md serif mb-xs group-hover:text-primary transition-colors"><?php echo t6_esc($item['name'] ?? ''); ?></h3>
<?php if (! empty($item['description'])): ?>
<p class="font-body-sm text-body-sm text-on-surface-variant"><?php echo t6_esc($item['description']); ?></p>
<?php endif; ?>
<?php if (! empty($supportsOrdering) && $itemAvailable): ?>
<button type="button" class="add-to-bag-btn mt-3 font-label-md text-label-md uppercase text-primary border border-primary/40 px-4 py-2 rounded-lg hover:bg-primary/10 transition-all" data-item-id="<?php echo (int) ($item['id'] ?? 0); ?>" data-item-name="<?php echo t6_esc($item['name'] ?? ''); ?>" data-item-price="<?php echo t6_esc((string) ($item['price'] ?? '')); ?>" data-item-image="<?php echo t6_esc($item['image'] ?? ''); ?>">Add to bag</button>
<?php endif; ?>
</div>
<span class="font-label-lg text-label-lg text-primary shrink-0"><?php echo t6_price($item['price'] ?? 0); ?></span>
</div>
</div>
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
