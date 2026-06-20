<?php if (! empty($supportsReservations)): ?>
<?php
if (empty($heroUrl)) {
    $heroUrl = $restaurant['hero_image_url'] ?? null;
    if (empty($heroUrl) && ! empty($sections[0]['image'])) {
        $heroUrl = t6_section_image($uploadBaseUrl ?? '', $sections[0]);
    }
}
$t6EmbedReservationUrl = ($reservationUrl ?? '').(str_contains($reservationUrl ?? '', '?') ? '&' : '?').'embed=1&theme=template6';
?>
<section id="reservation" class="py-12 md:py-xl scroll-mt-28">
<div class="max-w-container-max mx-auto px-4 md:px-gutter">
<div class="bg-surface-container rounded-xl overflow-hidden border border-outline-variant/10">
<div class="grid grid-cols-1 lg:grid-cols-2 lg:min-h-[560px]">
<div class="relative min-h-[280px] lg:min-h-full order-1">
<?php if ($heroUrl): ?>
<img class="absolute inset-0 w-full h-full object-cover" alt="" src="<?php echo t6_esc($heroUrl); ?>" loading="lazy">
<?php else: ?>
<div class="absolute inset-0 bg-surface-container-high"></div>
<?php endif; ?>
<div class="absolute inset-0 bg-gradient-to-r from-background/90 via-background/50 to-transparent lg:bg-gradient-to-r lg:from-background/80 lg:via-background/40 lg:to-transparent"></div>
<div class="relative z-10 h-full flex flex-col justify-end p-6 md:p-xl">
<h2 class="font-display-lg text-headline-xl mb-4 serif">Secure Your <br><span class="text-primary italic">Sanctuary</span></h2>
<p class="text-on-surface-variant font-body-md max-w-md">Experience the pinnacle of culinary excellence. Reservations are recommended to ensure your preferred seating.</p>
</div>
</div>
<div class="order-2 flex flex-col bg-surface-container p-4 md:p-6 lg:p-8">
<?php if (! empty($isTemplatePreview)): ?>
<p class="font-label-md text-label-md uppercase text-on-surface-variant text-center py-12">Reservation form (Demo Preview)</p>
<?php else: ?>
<iframe
    id="t6-reservation-iframe"
    src="<?php echo t6_esc($t6EmbedReservationUrl); ?>"
    title="Table reservation for <?php echo t6_esc($restaurant['name'] ?? ''); ?>"
    class="w-full border-0 bg-transparent block overflow-hidden"
    scrolling="no"
    style="min-height:420px;height:420px;"
    loading="lazy"
></iframe>
<script>
(function(){
    var iframe = document.getElementById('t6-reservation-iframe');
    if (!iframe) return;
    window.addEventListener('message', function(e) {
        if (!e.data || e.data.type !== 't6-reservation-resize') return;
        var h = parseInt(e.data.height, 10);
        if (h > 0) iframe.style.height = (h + 8) + 'px';
    });
})();
</script>
<?php endif; ?>
</div>
</div>
</div>
</div>
</section>
<?php endif; ?>
