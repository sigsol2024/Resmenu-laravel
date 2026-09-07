<?php if (empty($supportsReservations)) {
    return;
}

$heroUrl = $restaurant['hero_image_url'] ?? null;
if (empty($heroUrl) && ! empty($sections[0])) {
    $heroUrl = t7_section_image($uploadBaseUrl ?? '', $sections[0]);
}
?>
<section id="reservation" class="t7-reservation scroll-mt-28 border-t border-onyx-border py-12 sm:py-16 section-pattern section-pattern--dark relative overflow-hidden">
  <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_bottom_right,_rgba(80,12,25,0.35)_0%,_transparent_60%)] pointer-events-none" aria-hidden="true"></div>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
    <div class="rounded-2xl border border-champagne-gold/25 overflow-hidden bg-onyx-card/90">
      <div class="grid grid-cols-1 lg:grid-cols-2 lg:items-stretch">
        <div class="relative min-h-[240px] sm:min-h-[300px] lg:min-h-[560px] overflow-hidden">
          <?php if ($heroUrl): ?>
          <img class="absolute inset-0 w-full h-full object-cover" alt="" src="<?php echo t7_esc($heroUrl); ?>" loading="lazy">
          <?php else: ?>
          <div class="absolute inset-0 bg-gradient-to-br from-burgundy-deep via-onyx-surface to-burgundy-dark"></div>
          <?php endif; ?>
          <div class="absolute inset-0 bg-gradient-to-t from-onyx-surface via-onyx-surface/70 to-burgundy-deep/40"></div>
          <div class="relative z-10 flex flex-col justify-end p-6 sm:p-8 h-full min-h-[240px]">
            <p class="text-[10px] uppercase tracking-[0.24em] text-champagne-gold mb-2">Reservations</p>
            <h2 class="font-serif-luxury text-3xl sm:text-4xl text-white leading-tight">
              Secure Your<br><span class="italic text-champagne-gold">Table</span>
            </h2>
            <p class="type-desc text-sm text-stone-300 mt-3 max-w-md">
              Reservations are recommended to ensure your preferred seating.
            </p>
          </div>
        </div>
        <div class="p-4 sm:p-6 lg:p-8 bg-onyx-card">
          <?php if (! empty($isTemplatePreview)): ?>
          <p class="text-center text-white/50 py-16 text-sm uppercase tracking-[0.18em]">Reservation form (Demo Preview)</p>
          <?php else: ?>
          <?php
            $t6Helpers = dirname(__DIR__, 2).DIRECTORY_SEPARATOR.'template6'.DIRECTORY_SEPARATOR.'helpers.php';
            $t6Form = dirname(__DIR__, 2).DIRECTORY_SEPARATOR.'template6'.DIRECTORY_SEPARATOR.'partials'.DIRECTORY_SEPARATOR.'reservation-form.php';
            $t6Ready = is_readable($t6Helpers) && is_readable($t6Form);
            if ($t6Ready) {
                require_once $t6Helpers;
            }
            if ($t6Ready && function_exists('t6_esc') && ! empty($reservationFormData) && is_array($reservationFormData)) {
                include $t6Form;
            } else {
                $fallbackReserve = $reservationUrl ?? '';
                echo '<p class="text-center text-white/55 py-12 text-sm">';
                if ($fallbackReserve !== '') {
                    echo '<a class="inline-flex items-center gap-2 px-5 py-3 min-h-[44px] uppercase tracking-[0.16em] text-xs font-semibold text-white bg-burgundy-deep border border-champagne-gold/40 rounded" href="'.t7_esc($fallbackReserve).'">Reserve a Table</a>';
                } else {
                    echo 'Reservation form unavailable.';
                }
                echo '</p>';
            }
          ?>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</section>
