<?php if (! empty($supportsReservations)): ?>
<?php
$t6EmbedReservationUrl = ($reservationUrl ?? '').(str_contains($reservationUrl ?? '', '?') ? '&' : '?').'embed=1';
?>
<section id="reservation" class="py-12 md:py-xl scroll-mt-28">
<div class="max-w-container-max mx-auto px-4 md:px-gutter">
<div class="bg-surface-container rounded-xl overflow-hidden border border-outline-variant/10">
<div class="grid grid-cols-1 lg:grid-cols-2">
<div class="p-6 md:p-xl flex flex-col justify-center order-2 lg:order-1">
<h2 class="font-display-lg text-headline-xl mb-6 serif">Secure Your <br><span class="text-primary italic">Sanctuary</span></h2>
<p class="text-on-surface-variant font-body-md mb-6 max-w-md">Experience the pinnacle of culinary excellence. Reservations are recommended to ensure your preferred seating.</p>
<?php if (! empty($isTemplatePreview)): ?>
<span class="inline-block bg-primary/20 text-primary px-8 py-4 font-label-lg text-label-lg uppercase tracking-widest w-fit">Reserve (Demo Preview)</span>
<?php else: ?>
<p class="font-label-md text-label-md uppercase text-on-surface-variant mb-4">Complete your booking below</p>
<?php endif; ?>
</div>
<div class="relative min-h-[480px] sm:min-h-[520px] lg:min-h-[600px] order-1 lg:order-2 bg-surface-container-high">
<?php if (! empty($isTemplatePreview)): ?>
<?php if (! empty($heroUrl ?? null)): ?>
<img class="w-full h-full object-cover absolute inset-0 min-h-[280px]" alt="" src="<?php echo t6_esc($heroUrl); ?>" loading="lazy">
<div class="absolute inset-0 bg-gradient-to-t from-surface-container via-surface-container/60 to-transparent"></div>
<?php endif; ?>
<?php else: ?>
<iframe
    src="<?php echo t6_esc($t6EmbedReservationUrl); ?>"
    title="Table reservation for <?php echo t6_esc($restaurant['name'] ?? ''); ?>"
    class="w-full h-full min-h-[480px] sm:min-h-[520px] lg:min-h-[600px] border-0 absolute inset-0 bg-surface-container-low"
    loading="lazy"
></iframe>
<?php endif; ?>
</div>
</div>
</div>
</div>
</section>
<?php endif; ?>
