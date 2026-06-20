<?php
$section = $activeSection ?? ($sections[0] ?? null);
$categories = $section['categories'] ?? [];
$sectionName = $section['name'] ?? 'Menu';
?>
<main class="pt-[100px] pb-xl px-gutter max-w-container-max mx-auto">
<section class="mb-lg">
<div class="max-w-2xl mx-auto text-center mb-md">
<h2 class="font-display-lg text-headline-xl mb-4 text-primary serif"><?php echo t6_esc($sectionName); ?></h2>
<p class="font-body-lg text-body-lg text-on-surface-variant">Experience the pinnacle of culinary artistry.</p>
</div>
<div class="max-w-xl mx-auto relative group">
<span class="absolute left-4 top-1/2 -translate-y-1/2 text-primary/60 group-focus-within:text-primary transition-colors">
<span class="material-symbols-outlined">search</span>
</span>
<input class="w-full h-14 pl-12 pr-4 bg-surface-container-low border-b border-primary/20 focus:border-primary focus:ring-0 text-on-surface font-body-md transition-all outline-none rounded-lg t6-page-search" placeholder="Search for a dish, cocktail or ingredient..." type="search" autocomplete="off">
</div>
</section>

<?php if (count($categories) > 1): ?>
<div class="sticky top-[73px] z-40 lusso-glass py-4 -mx-gutter px-gutter mb-lg border-y border-outline-variant/10 overflow-x-auto whitespace-nowrap hide-scrollbar flex justify-center gap-8">
<?php foreach ($categories as $i => $cat):
    $cslug = $cat['slug'] ?? ('cat-'.$i);
?>
<a href="<?php echo t6_esc(t6_category_url($fullMenuUrl ?? '', $section['slug'] ?? '', $cslug)); ?>" class="font-label-lg text-label-lg text-on-surface-variant hover:text-primary transition-colors"><?php echo t6_esc($cat['name'] ?? ''); ?></a>
<?php endforeach; ?>
</div>
<?php endif; ?>

<div class="grid grid-cols-1 md:grid-cols-4 md:grid-rows-3 gap-6">
<?php
$gridClasses = [
    'md:col-span-2 md:row-span-2 relative category-card group cursor-pointer overflow-hidden rounded-xl bg-surface-container shadow-xl min-h-[300px]',
    'md:col-span-2 relative category-card group cursor-pointer overflow-hidden rounded-xl bg-surface-container shadow-xl min-h-[300px]',
    'md:col-span-1 relative category-card group cursor-pointer overflow-hidden rounded-xl bg-surface-container shadow-xl min-h-[300px]',
    'md:col-span-1 relative category-card group cursor-pointer overflow-hidden rounded-xl bg-surface-container shadow-xl min-h-[300px]',
    'md:col-span-2 md:row-span-1 relative category-card group cursor-pointer overflow-hidden rounded-xl bg-surface-container shadow-xl min-h-[240px]',
    'md:col-span-1 relative category-card group cursor-pointer overflow-hidden rounded-xl bg-surface-container shadow-xl min-h-[240px]',
];
foreach ($categories as $i => $cat):
    $cslug = $cat['slug'] ?? ('cat-'.$i);
    $catUrl = t6_category_url($fullMenuUrl ?? '', $section['slug'] ?? '', $cslug);
    $catImg = t6_category_image($uploadBaseUrl ?? '', $cat);
    $gridClass = $gridClasses[$i % count($gridClasses)];
?>
<a href="<?php echo t6_esc($catUrl); ?>" class="<?php echo $gridClass; ?> block" data-t6-searchable data-t6-search-text="<?php echo t6_esc($cat['name'] ?? ''); ?>">
<div class="absolute inset-0 z-10 card-overlay transition-colors duration-500 bg-gradient-to-t from-background via-transparent to-transparent"></div>
<?php if ($catImg): ?>
<img class="w-full h-full object-cover transition-transform duration-700 absolute inset-0" alt="<?php echo t6_esc($cat['name'] ?? ''); ?>" src="<?php echo t6_esc($catImg); ?>" loading="lazy">
<?php else: ?>
<div class="absolute inset-0 bg-surface-container-high"></div>
<?php endif; ?>
<div class="absolute bottom-0 left-0 p-8 z-20 w-full">
<h3 class="font-display-lg text-headline-xl text-primary mb-2 serif"><?php echo t6_esc($cat['name'] ?? ''); ?></h3>
<?php if (! empty($cat['description'])): ?>
<p class="font-body-md text-body-md text-on-surface-variant max-w-sm line-clamp-2"><?php echo t6_esc($cat['description']); ?></p>
<?php endif; ?>
</div>
</a>
<?php endforeach; ?>
</div>

<?php if (empty($categories)): ?>
<p class="text-center text-on-surface-variant py-xl font-body-lg">No categories available in this section yet.</p>
<?php endif; ?>

<?php if (! empty($supportsOrdering)): ?>
<section class="mt-lg">
<div class="bg-surface-container-high rounded-xl p-8 border border-outline-variant/10 flex flex-col md:flex-row items-center justify-between gap-6 overflow-hidden relative group">
<div class="absolute top-0 right-0 w-64 h-64 bg-primary/5 rounded-full -mr-20 -mt-20 blur-3xl group-hover:bg-primary/10 transition-all duration-700"></div>
<div class="relative z-10">
<h2 class="font-display-lg text-headline-lg text-primary mb-2 serif">Order Online</h2>
<p class="font-body-md text-body-md text-on-surface-variant max-w-lg">Browse our categories and add your favourites to your bag for a seamless dining experience.</p>
</div>
<div class="relative z-10">
<?php if (! empty($categories[0])): ?>
<a href="<?php echo t6_esc(t6_category_url($fullMenuUrl ?? '', $section['slug'] ?? '', $categories[0]['slug'] ?? '')); ?>" class="bg-primary text-on-primary px-8 py-3 rounded-full font-label-lg text-label-lg active:scale-95 duration-200 transition-all inline-block">Order Now</a>
<?php endif; ?>
</div>
</div>
</section>
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
