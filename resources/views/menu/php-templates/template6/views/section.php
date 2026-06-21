<?php
$section = $activeSection ?? ($sections[0] ?? null);
$categories = $section['categories'] ?? [];
$sectionName = $section['name'] ?? 'Menu';
$sectionHeroUrl = ! empty($section) ? t6_section_image($uploadBaseUrl ?? '', $section) : null;
?>
<?php if ($sectionHeroUrl): ?>
<section class="relative w-full h-[38vh] md:h-[42vh] min-h-[220px] max-h-[440px] overflow-hidden">
<img class="absolute inset-0 w-full h-full object-cover" alt="<?php echo t6_esc($sectionName); ?>" src="<?php echo t6_esc($sectionHeroUrl); ?>" loading="eager">
<div class="absolute inset-0 lusso-gradient"></div>
<div class="absolute inset-0 bg-surface/30"></div>
<div class="absolute bottom-0 left-0 right-0 px-4 md:px-gutter pb-8 md:pb-10 pt-24 max-w-container-max mx-auto">
<h1 class="font-display-lg text-headline-lg md:text-headline-xl text-primary serif mb-2"><?php echo t6_esc($sectionName); ?></h1>
<p class="font-body-md md:text-body-lg text-on-surface-variant max-w-xl">Experience the pinnacle of culinary artistry.</p>
</div>
</section>
<?php endif; ?>

<main class="<?php echo $sectionHeroUrl ? 't6-main-after-hero' : 't6-main-offset'; ?> pb-12 md:pb-xl px-4 md:px-gutter max-w-container-max mx-auto">
<?php if (! $sectionHeroUrl): ?>
<section class="mb-md md:mb-lg">
<div class="max-w-2xl mx-auto text-center mb-md">
<h1 class="font-display-lg text-headline-md md:text-headline-lg mb-3 text-primary serif"><?php echo t6_esc($sectionName); ?></h1>
<p class="font-body-md md:text-body-lg text-on-surface-variant">Experience the pinnacle of culinary artistry.</p>
</div>
</section>
<?php endif; ?>

<section class="mb-lg md:mb-xl">
<div class="max-w-xl mx-auto relative group">
<span class="absolute left-4 top-1/2 -translate-y-1/2 text-primary/60 group-focus-within:text-primary transition-colors">
<span class="material-symbols-outlined">search</span>
</span>
<input class="w-full h-12 md:h-14 pl-12 pr-4 bg-surface-container-low border-b border-primary/20 focus:border-primary focus:ring-0 text-on-surface font-body-md transition-all outline-none rounded-lg t6-page-search" placeholder="Search for a dish, cocktail or ingredient..." type="search" autocomplete="off">
</div>
</section>

<div class="grid grid-cols-2 md:grid-cols-4 md:grid-rows-3 gap-3 md:gap-6 grid-flow-dense">
<?php foreach ($categories as $i => $cat):
    $cslug = $cat['slug'] ?? ('cat-'.$i);
    $catUrl = t6_category_url($fullMenuUrl ?? '', $section['slug'] ?? '', $cslug);
    $catImg = t6_category_image($uploadBaseUrl ?? '', $cat);
    $grid = t6_section_category_grid_class($i);
    $gridClass = $grid['class'];
    $isLarge = $grid['isLarge'];
?>
<a href="<?php echo t6_esc($catUrl); ?>" class="<?php echo $gridClass; ?> relative category-card group cursor-pointer overflow-hidden rounded-xl shadow-xl block border border-outline-variant/10 <?php echo $catImg ? 'bg-surface-container' : 't6-category-box bg-surface-container-high'; ?>" data-t6-searchable data-t6-search-text="<?php echo t6_esc($cat['name'] ?? ''); ?>">
<?php if ($catImg): ?>
<div class="absolute inset-0 z-10 t6-category-img-overlay transition-all duration-500"></div>
<img class="w-full h-full object-cover transition-transform duration-700 absolute inset-0 group-hover:scale-105" alt="<?php echo t6_esc($cat['name'] ?? ''); ?>" src="<?php echo t6_esc($catImg); ?>" loading="lazy">
<div class="absolute bottom-0 left-0 p-3 md:p-6 z-20 w-full">
<h3 class="font-headline-md md:text-headline-lg text-primary mb-1 serif leading-tight"><?php echo t6_esc($cat['name'] ?? ''); ?></h3>
<?php if (! empty($cat['description']) && $isLarge): ?>
<p class="font-body-sm md:text-body-md text-on-surface-variant max-w-sm line-clamp-2"><?php echo t6_esc($cat['description']); ?></p>
<?php endif; ?>
</div>
<?php else: ?>
<div class="absolute inset-0 z-0 bg-gradient-to-br from-surface-container-high via-surface-container to-surface-container-low opacity-90"></div>
<div class="absolute inset-0 z-10 border border-primary/10 rounded-xl pointer-events-none"></div>
<div class="relative z-20 h-full flex flex-col justify-center items-center text-center p-3 md:p-6">
<span class="material-symbols-outlined text-primary/40 text-2xl md:text-4xl mb-1 md:mb-2">restaurant</span>
<h3 class="font-headline-md md:text-headline-lg text-primary serif leading-tight"><?php echo t6_esc($cat['name'] ?? ''); ?></h3>
<?php if (! empty($cat['description'])): ?>
<p class="font-body-sm text-on-surface-variant mt-1 line-clamp-2 max-w-[160px] md:max-w-xs"><?php echo t6_esc($cat['description']); ?></p>
<?php endif; ?>
</div>
<?php endif; ?>
</a>
<?php endforeach; ?>
</div>

<?php if (empty($categories)): ?>
<p class="text-center text-on-surface-variant py-xl font-body-lg">No categories available in this section yet.</p>
<?php endif; ?>

<?php if (! empty($supportsOrdering)): ?>
<section class="mt-lg">
<div class="bg-surface-container-high rounded-xl p-6 md:p-8 border border-outline-variant/10 flex flex-col md:flex-row items-center justify-between gap-6 overflow-hidden relative group">
<div class="absolute top-0 right-0 w-64 h-64 bg-primary/5 rounded-full -mr-20 -mt-20 blur-3xl group-hover:bg-primary/10 transition-all duration-700"></div>
<div class="relative z-10 text-center md:text-left">
<h2 class="font-headline-md md:text-headline-lg text-primary mb-2 serif">Order Online</h2>
<p class="font-body-sm md:text-body-md text-on-surface-variant max-w-lg">Browse our categories and add your favourites to your bag for a seamless dining experience.</p>
</div>
<div class="relative z-10">
<?php if (! empty($categories[0])): ?>
<a href="<?php echo t6_esc(t6_category_url($fullMenuUrl ?? '', $section['slug'] ?? '', $categories[0]['slug'] ?? '')); ?>" class="bg-primary text-on-primary px-6 md:px-8 py-3 rounded-full font-label-lg text-label-lg active:scale-95 duration-200 transition-all inline-block">Order Now</a>
<?php endif; ?>
</div>
</div>
</section>
<?php endif; ?>
<?php include __DIR__.'/../partials/reservation-embed.php'; ?>
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
