<?php if (! empty($supportsReservations)): ?>
<?php
if (empty($heroUrl)) {
    $heroUrl = $restaurant['hero_image_url'] ?? null;
    if (empty($heroUrl) && ! empty($sections[0]['image'])) {
        $heroUrl = t6_section_image($uploadBaseUrl ?? '', $sections[0]);
    }
}
?>
<section id="reservation" class="py-12 md:py-xl scroll-mt-28">
<div class="max-w-container-max mx-auto px-4 md:px-gutter">
<div class="bg-surface-container rounded-xl border border-outline-variant/10">
<div class="grid grid-cols-1 lg:grid-cols-2 lg:items-start">
<div class="relative h-[260px] sm:h-[300px] lg:h-auto lg:min-h-[560px] overflow-hidden rounded-t-xl lg:rounded-l-xl lg:rounded-tr-none">
<?php if ($heroUrl): ?>
<img class="absolute inset-0 w-full h-full object-cover" alt="" src="<?php echo t6_esc($heroUrl); ?>" loading="lazy">
<?php else: ?>
<div class="absolute inset-0 bg-surface-container-high"></div>
<?php endif; ?>
<div class="absolute inset-0 t6-reservation-img-overlay"></div>
<div class="relative z-10 flex flex-col justify-start p-6 md:p-8 lg:p-8 lg:pt-8 h-full">
<h2 class="font-display-lg text-headline-xl mb-4 serif">Secure Your <br><span class="text-primary italic">Sanctuary</span></h2>
<p class="text-on-surface-variant font-body-md max-w-md">Experience the pinnacle of culinary excellence. Reservations are recommended to ensure your preferred seating.</p>
</div>
</div>
<div class="p-4 md:p-6 lg:p-8 lg:pt-8">
<?php if (! empty($isTemplatePreview)): ?>
<p class="font-label-md text-label-md uppercase text-on-surface-variant text-center py-12">Reservation form (Demo Preview)</p>
<?php else: ?>
<?php include __DIR__.'/reservation-form.php'; ?>
<?php endif; ?>
</div>
</div>
</div>
</div>
</section>
<?php endif; ?>
