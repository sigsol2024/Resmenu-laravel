<?php
$heroUrl = $restaurant['hero_image_url'] ?? null;
if (empty($heroUrl) && ! empty($sections[0]['image'])) {
    $heroUrl = t6_section_image($uploadBaseUrl ?? '', $sections[0]);
}
$t6SectionCount = count($sections ?? []);
if ($t6SectionCount === 1) {
    $t6SectionsGridClass = 'grid grid-cols-1 gap-3 md:gap-gutter max-w-2xl mx-auto';
} elseif ($t6SectionCount === 2) {
    $t6SectionsGridClass = 'grid grid-cols-2 gap-3 md:gap-gutter max-w-4xl mx-auto';
} elseif ($t6SectionCount === 3) {
    $t6SectionsGridClass = 'grid grid-cols-2 sm:grid-cols-3 gap-3 md:gap-gutter';
} else {
    $t6SectionsGridClass = 'grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-gutter';
}
?>
<main class="pb-8">
<section class="relative min-h-[85vh] md:min-h-[95vh] w-full flex flex-col justify-end overflow-hidden">
<div class="absolute inset-0 z-0">
<?php if ($heroUrl): ?>
<div class="w-full h-full bg-cover bg-center" style="background-image: url('<?php echo t6_esc($heroUrl); ?>')"></div>
<?php else: ?>
<div class="w-full h-full bg-surface-container-high"></div>
<?php endif; ?>
<div class="absolute inset-0 lusso-gradient"></div>
</div>
<div class="relative z-10 max-w-container-max mx-auto px-4 md:px-gutter w-full pb-12 md:pb-xl pt-24">
<div class="flex flex-wrap items-center gap-3 mb-6">
<span class="bg-surface-container-highest text-primary border border-primary/20 px-3 py-1 rounded-full font-label-md text-label-md flex items-center gap-1.5 uppercase">
<span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
<?php echo t6_esc(t6_rating_display($restaurant)); ?>
</span>
</div>
<h1 class="font-display-lg text-[clamp(2rem,8vw,4.5rem)] mb-6 md:mb-8 max-w-3xl leading-[1.05] serif">
<?php echo t6_esc($restaurant['name'] ?? ''); ?> <span class="italic text-primary">Fine Dining</span>
</h1>
<?php if (! empty($restaurant['description'])): ?>
<p class="font-body-lg text-body-lg text-on-surface-variant max-w-2xl mb-6 md:mb-8 line-clamp-4 md:line-clamp-none"><?php echo t6_esc($restaurant['description']); ?></p>
<?php endif; ?>
<div class="flex flex-wrap gap-3 md:gap-4">
<a href="#sections" class="t6-scroll-anchor bg-primary text-on-primary px-6 md:px-8 py-3 md:py-4 font-label-lg text-label-lg uppercase tracking-widest hover:scale-[0.98] transition-transform duration-300 inline-block text-center">Explore Menu</a>
<?php if (! empty($supportsReservations)): ?>
<a href="#reservation" class="t6-scroll-anchor border border-primary text-on-background px-6 md:px-8 py-3 md:py-4 font-label-lg text-label-lg uppercase tracking-widest hover:bg-primary/10 transition-colors duration-300 inline-block text-center">Book a Table</a>
<?php endif; ?>
</div>
</div>
</section>

<section id="sections" class="py-12 md:py-xl max-w-container-max mx-auto px-4 md:px-gutter scroll-mt-28">
<h2 class="font-display-lg text-headline-lg mb-8 md:mb-12 text-center serif">Curated Experiences</h2>
<div class="<?php echo t6_esc($t6SectionsGridClass); ?>">
<?php foreach ($sections as $i => $section):
    $secImg = t6_section_image($uploadBaseUrl ?? '', $section);
    $secUrl = t6_section_url($fullMenuUrl ?? '', $section['slug'] ?? '');
    if ($t6SectionCount === 1) {
        $cardClass = 'relative aspect-[16/10] sm:aspect-[2/1] group overflow-hidden rounded-lg min-h-[200px] block';
    } elseif ($t6SectionCount === 2) {
        $cardClass = 'relative aspect-square group overflow-hidden rounded-lg min-h-[140px] block';
    } elseif ($t6SectionCount === 3) {
        $cardClass = ($i === 2)
            ? 'relative col-span-2 sm:col-span-1 aspect-[2/1] sm:aspect-square group overflow-hidden rounded-lg min-h-[140px] sm:min-h-[180px] block'
            : 'relative aspect-square group overflow-hidden rounded-lg min-h-[140px] block';
    } else {
        $cardClass = 'relative aspect-square group overflow-hidden rounded-lg min-h-[140px] block';
    }
?>
<a href="<?php echo t6_esc($secUrl); ?>" class="<?php echo t6_esc($cardClass); ?>" data-t6-searchable data-t6-search-text="<?php echo t6_esc($section['name'] ?? ''); ?>">
<?php if ($secImg): ?>
<img class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000" alt="<?php echo t6_esc($section['name'] ?? ''); ?>" src="<?php echo t6_esc($secImg); ?>" loading="lazy">
<?php else: ?>
<div class="w-full h-full bg-surface-container-high"></div>
<?php endif; ?>
<div class="absolute inset-0 t6-section-card-overlay"></div>
<div class="absolute inset-0 flex items-center justify-center p-3 md:p-4 text-center z-10">
<span class="font-display-lg text-[clamp(0.875rem,3vw,1.5rem)] text-on-background tracking-widest uppercase leading-tight drop-shadow-[0_2px_8px_rgba(0,0,0,0.85)]"><?php echo t6_esc($section['name'] ?? ''); ?></span>
</div>
</a>
<?php endforeach; ?>
</div>
</section>

<?php include __DIR__.'/../partials/reservation-embed.php'; ?>

<section class="py-12 md:py-xl bg-background border-t border-outline-variant/10">
<div class="max-w-container-max mx-auto px-4 md:px-gutter text-center">
<p class="text-primary font-label-lg text-label-lg uppercase tracking-[0.3em] mb-4">Our Philosophy</p>
<h2 class="font-display-lg text-headline-xl mb-6 md:mb-8 serif">Modern African Luxury</h2>
<div class="max-w-3xl mx-auto">
<p class="font-body-lg text-body-lg text-on-surface-variant leading-relaxed"><?php echo t6_esc($restaurant['footer_content'] ?? $restaurant['description'] ?? 'A curated dining experience crafted with heritage and contemporary refinement.'); ?></p>
</div>
</div>
</section>
</main>
