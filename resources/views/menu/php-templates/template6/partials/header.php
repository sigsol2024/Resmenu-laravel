<?php
$t6Logo = t6_logo_url($uploadBaseUrl ?? '', $restaurant ?? []);
$t6MenuViewLevel = $menuViewLevel ?? 'home';
$t6ResLink = ! empty($supportsReservations) && in_array($t6MenuViewLevel, ['home', 'section'], true)
    ? '#reservation'
    : ($reservationUrl ?? '#');
$t6SectionCategories = ($t6MenuViewLevel === 'section') ? ($activeSection['categories'] ?? []) : [];
$t6ShowCategoryMenu = $t6MenuViewLevel === 'section' && ! empty($t6SectionCategories);
?>
<header class="fixed top-0 w-full z-50 bg-background/95 backdrop-blur-md border-b border-outline-variant/10 shadow-sm" id="t6-header">
<nav class="flex justify-between items-center px-4 md:px-gutter py-4 max-w-container-max mx-auto gap-3">
<div class="flex items-center gap-4 md:gap-8 min-w-0 flex-1">
<a href="<?php echo t6_esc($fullMenuUrl ?? '#'); ?>" class="flex items-center gap-2 min-w-0">
<?php if ($t6Logo): ?>
<img alt="<?php echo t6_esc($restaurant['name'] ?? ''); ?>" class="w-8 h-8 object-contain shrink-0" src="<?php echo t6_esc($t6Logo); ?>">
<?php endif; ?>
<span class="font-display-lg text-headline-md tracking-widest text-primary truncate"><?php echo t6_esc($restaurant['name'] ?? ''); ?></span>
</a>
<div class="hidden md:flex items-center gap-6 shrink-0">
<a class="font-label-lg text-label-lg text-primary font-bold border-b-2 border-primary pb-1" href="<?php echo t6_esc($fullMenuUrl ?? '#'); ?>">Menu</a>
<?php if (! empty($supportsReservations)): ?>
<a class="font-label-lg text-label-lg text-on-surface-variant hover:text-primary transition-colors t6-scroll-anchor" href="<?php echo t6_esc($t6ResLink); ?>">Reservations</a>
<?php endif; ?>
</div>
</div>
<div class="flex items-center gap-3 md:gap-4 shrink-0">
<?php if ($t6ShowCategoryMenu): ?>
<button type="button" id="t6-menu-toggle" class="material-symbols-outlined text-on-surface-variant hover:text-primary transition-colors" aria-label="Open category menu" aria-controls="t6-menu-drawer" aria-expanded="false">menu</button>
<?php else: ?>
<button type="button" id="t6-search-toggle" class="material-symbols-outlined text-on-surface-variant hover:text-primary transition-colors" aria-label="Search">search</button>
<?php endif; ?>
<?php if (! empty($supportsOrdering)): ?>
<button type="button" id="t6-header-cart" class="material-symbols-outlined text-on-surface-variant hover:text-primary transition-colors relative" aria-label="Cart">
shopping_bag
</button>
<?php endif; ?>
</div>
</nav>
<div id="t6-search-bar" class="border-t border-outline-variant/10 bg-background/95">
<div class="max-w-container-max mx-auto px-4 md:px-gutter py-3">
<input type="search" id="t6-search-input" class="w-full h-12 px-4 bg-surface-container-low border-b border-primary/20 focus:border-primary focus:ring-0 text-on-surface font-body-md outline-none rounded-lg" placeholder="Search dishes, categories..." autocomplete="off">
</div>
</div>
</header>
