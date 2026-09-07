<?php
/**
 * Template 7 — Ember Noir | Home / Landing View
 *
 * Renders: hero → jump nav → editorial section grid → footer gap
 * Inspired by REFERENCES/landing.html with DESIGN_1 colour palette.
 * No hardcoded restaurant names or menu items — all dynamic.
 */

$t7SectionCount = count($sections ?? []);
?>

<!-- ══════════════════════ HERO (short, 30-40vh) ══════════════════════════════ -->
<section class="relative bg-gradient-to-b from-[#16141a] via-[#1a080e] to-[#0e0e10] border-b border-[rgba(203,178,136,0.28)] py-10 sm:py-14 overflow-hidden t7-pattern-shell">
  <!-- Radial burgundy glow -->
  <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_rgba(80,12,25,0.25)_0%,_transparent_65%)] pointer-events-none" aria-hidden="true"></div>

  <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
    <!-- Eyebrow -->
    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#38060F]/60 border border-[rgba(203,178,136,0.28)] mb-4">
      <span class="text-[#CBB288] text-[10px]">◆</span>
      <span class="text-[10px] sm:text-[11px] uppercase tracking-[0.26em] text-[#F6F1E8] font-medium">Digital Menu</span>
    </div>

    <!-- Restaurant name -->
    <h1 class="t7-serif text-3xl sm:text-5xl md:text-6xl tracking-wide uppercase text-white font-normal leading-tight">
      <?php echo t7_esc($restaurant['name'] ?? ''); ?>
    </h1>

    <?php if (! empty($restaurant['description'])): ?>
    <p class="t7-serif italic text-base sm:text-xl text-[#EEDDC0]/90 font-normal mt-2 mb-4">
      <?php echo t7_esc($restaurant['description']); ?>
    </p>
    <?php else: ?>
    <p class="t7-serif italic text-base sm:text-xl text-[#EEDDC0]/90 font-normal mt-2 mb-4">
      Restaurant Digital Menu
    </p>
    <?php endif; ?>

    <!-- Status pill -->
    <?php if (! empty($restaurant['opening_hours'])): ?>
    <div class="inline-flex items-center gap-2.5 px-4 py-1.5 rounded-full bg-[#1D1C21]/90 border border-[rgba(203,178,136,0.28)] text-[10px] sm:text-[11px] text-[#F6F1E8]/80 tracking-wider mb-2">
      <span class="w-2 h-2 rounded-full bg-emerald-400 inline-block flex-shrink-0"></span>
      <span class="font-medium text-[#CBB288]"><?php echo t7_esc($restaurant['opening_hours']); ?></span>
    </div>
    <?php endif; ?>

    <!-- CTA buttons -->
    <div class="flex flex-wrap items-center justify-center gap-3 mt-4">
      <a href="#t7-menu-index" class="inline-flex items-center gap-1.5 px-5 py-2.5 text-[11px] uppercase tracking-[0.18em] font-semibold text-white bg-[#500C19] hover:bg-[#6A1324] border border-[rgba(203,178,136,0.40)] rounded transition-all shadow-sm">
        <span>Explore Menu</span>
        <span class="t7-serif text-sm text-[#CBB288]">→</span>
      </a>
      <?php if (! empty($supportsReservations)): ?>
      <a href="<?php echo t7_esc($reservationUrl); ?>" class="inline-flex items-center gap-1.5 px-5 py-2.5 text-[11px] uppercase tracking-[0.18em] font-semibold text-[#F6F1E8] hover:text-white border border-[rgba(203,178,136,0.40)] hover:border-[#CBB288] rounded transition-all">
        <span>Reserve a Table</span>
      </a>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- ══════════════════════ JUMP NAV (sticky below header) ════════════════════ -->
<?php if (! empty($sectionsForNav) && count($sectionsForNav) > 1): ?>
<div class="sticky top-[4rem] sm:top-[4.5rem] z-40 bg-[#121214]/95 backdrop-blur border-b border-[rgba(203,178,136,0.28)] shadow-md">
  <div class="max-w-7xl mx-auto px-4 sm:px-6">
    <div class="flex items-center gap-2 overflow-x-auto py-3 t7-noscroll scroll-smooth">
      <span class="text-[10px] uppercase tracking-[0.20em] text-[#CBB288]/60 font-semibold pl-1 pr-2 whitespace-nowrap hidden md:inline">Jump To:</span>
      <?php foreach ($sectionsForNav as $navSec):
        $navSlug = $navSec['slug'] ?? '';
        $navName = $navSec['name'] ?? '';
      ?>
      <a href="<?php echo t7_esc(t7_section_url($fullMenuUrl, $navSlug)); ?>"
         class="px-3 py-1.5 text-[11px] uppercase tracking-[0.14em] font-medium rounded bg-white/5 hover:bg-[#500C19] text-[#F6F1E8] hover:text-white border border-white/10 hover:border-[rgba(203,178,136,0.40)] whitespace-nowrap transition-colors">
        <?php echo t7_esc($navName); ?>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</div>
<?php endif; ?>

<!-- ══════════════════════ MAIN SECTION GRID ═════════════════════════════════ -->
<main class="flex-grow py-10 sm:py-14 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full" id="t7-menu-index">

  <!-- Section intro header -->
  <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 sm:mb-10 pb-4 border-b border-[rgba(203,178,136,0.28)] gap-3">
    <div>
      <span class="text-[10px] uppercase tracking-[0.24em] font-semibold text-[#CBB288] block mb-1">Catalog &amp; Selections</span>
      <h2 class="t7-serif text-2xl sm:text-4xl text-white font-normal">Explore Our Menu</h2>
    </div>
    <p class="text-xs sm:text-sm text-[#F6F1E8]/60 font-light max-w-md">
      Choose a section to explore our dishes and selections.
      <?php if (! empty($supportsOrdering)): ?>All items can be added to your bag.<?php endif; ?>
    </p>
  </div>

  <?php if (empty($sections)): ?>
  <p class="text-center text-[#F6F1E8]/50 py-16 font-light">No menu sections available yet.</p>

  <?php else: ?>
  <!-- Editorial grid -->
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-5 sm:gap-6">
    <?php foreach ($sections as $i => $section):
      $secSlot    = t7_home_grid_slot($i, $t7SectionCount);
      $secImg     = t7_section_image($uploadBaseUrl, $section);
      $secUrl     = t7_section_url($fullMenuUrl, $section['slug'] ?? '');
      $secName    = $section['name'] ?? '';
      $secDesc    = $section['description'] ?? '';
      $colSpan    = $secSlot['main'];
      $counter    = $secSlot['counter'];
      $isFeatured = $secSlot['featured'];

      // Available item count attached by MenuService::sectionsForHome (menu_items stripped).
      $secItemCount = (int) ($section['item_count'] ?? 0);

      // Card variant: with image or text-only
      if ($secImg): ?>
    <!-- Section card WITH image -->
    <a href="<?php echo t7_esc($secUrl); ?>"
       class="group <?php echo $colSpan; ?> bg-[#1D1C21] border border-[rgba(203,178,136,0.28)] hover:border-[rgba(203,178,136,0.80)] rounded overflow-hidden flex flex-col justify-between transition-all duration-300 hover:shadow-xl hover:shadow-[#26040A]/40 t7-reveal"
       aria-label="<?php echo t7_esc($secName); ?>">
      <!-- Image -->
      <div class="relative <?php echo $isFeatured ? 'h-52 sm:h-64' : 'h-44 sm:h-52'; ?> overflow-hidden bg-[#17171A]">
        <img
          src="<?php echo t7_esc($secImg); ?>"
          alt="<?php echo t7_esc($secName); ?>"
          class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-700 opacity-80"
          loading="lazy">
        <div class="absolute inset-0 bg-gradient-to-t from-[#1D1C21] via-[#1D1C21]/40 to-transparent"></div>
        <?php if ($counter): ?>
        <span class="absolute top-3 right-3 text-xs text-[#CBB288]/80 font-mono"><?php echo $counter; ?></span>
        <?php endif; ?>
      </div>
      <!-- Content -->
      <div class="p-5 sm:p-6 flex-grow flex flex-col justify-between">
        <div>
          <div class="flex items-center justify-between mb-2">
            <h3 class="t7-serif text-xl sm:text-2xl text-white group-hover:text-[#CBB288] transition-colors"><?php echo t7_esc($secName); ?></h3>
          </div>
          <?php if ($secDesc): ?>
          <p class="text-xs sm:text-sm text-[#F6F1E8]/70 mb-4 leading-relaxed line-clamp-2"><?php echo t7_esc($secDesc); ?></p>
          <?php endif; ?>
          <?php if ($secItemCount > 0): ?>
          <p class="text-[10px] uppercase tracking-[0.18em] text-[#CBB288]/60 font-medium"><?php echo $secItemCount; ?> item<?php echo $secItemCount !== 1 ? 's' : ''; ?></p>
          <?php endif; ?>
        </div>
        <div class="mt-5 pt-3 border-t border-white/10 flex items-center justify-between text-xs font-semibold uppercase tracking-[0.16em] text-[#CBB288] group-hover:text-white transition-colors">
          <span>Explore <?php echo t7_esc($secName); ?></span>
          <span class="transform group-hover:translate-x-1.5 transition-transform">→</span>
        </div>
      </div>
    </a>

      <?php else: ?>
    <!-- Section card WITHOUT image (text editorial) -->
    <a href="<?php echo t7_esc($secUrl); ?>"
       class="group <?php echo $colSpan; ?> bg-[#17171A] border border-[rgba(203,178,136,0.28)] hover:border-[rgba(203,178,136,0.80)] p-6 sm:p-7 rounded flex flex-col justify-between transition-all duration-300 hover:shadow-xl hover:shadow-[#26040A]/40 t7-reveal"
       aria-label="<?php echo t7_esc($secName); ?>">
      <div>
        <div class="flex items-center justify-between mb-3">
          <span class="text-[10px] uppercase tracking-[0.22em] text-[#CBB288] font-semibold">Section</span>
          <?php if ($counter): ?><span class="text-xs text-[#CBB288]/80 font-mono"><?php echo $counter; ?></span><?php endif; ?>
        </div>
        <h3 class="t7-serif text-xl sm:text-2xl text-white group-hover:text-[#CBB288] transition-colors mb-2"><?php echo t7_esc($secName); ?></h3>
        <?php if ($secDesc): ?>
        <p class="text-xs sm:text-sm text-[#F6F1E8]/70 mb-4 leading-relaxed"><?php echo t7_esc($secDesc); ?></p>
        <?php endif; ?>
        <?php if ($secItemCount > 0): ?>
        <p class="text-[10px] uppercase tracking-[0.18em] text-[#CBB288]/60 font-medium mt-2"><?php echo $secItemCount; ?> item<?php echo $secItemCount !== 1 ? 's' : ''; ?></p>
        <?php endif; ?>
      </div>
      <div class="mt-6 pt-3 border-t border-white/10 flex items-center justify-between text-xs font-semibold uppercase tracking-[0.16em] text-[#CBB288] group-hover:text-white transition-colors">
        <span>Explore <?php echo t7_esc($secName); ?></span>
        <span class="transform group-hover:translate-x-1.5 transition-transform">→</span>
      </div>
    </a>
      <?php endif; ?>

    <?php endforeach; ?>
  </div><!-- /grid -->
  <?php endif; ?>

  <!-- Reservation CTA strip -->
  <?php if (! empty($supportsReservations)): ?>
  <div class="mt-12 rounded border border-[rgba(203,178,136,0.28)] bg-[#1D1C21] p-6 sm:p-8 flex flex-col sm:flex-row items-center justify-between gap-4 t7-reveal">
    <div>
      <p class="text-[10px] uppercase tracking-[0.22em] text-[#CBB288] font-semibold mb-1">Dining Reservations</p>
      <h3 class="t7-serif text-xl text-white font-normal">Book Your Table</h3>
    </div>
    <a href="<?php echo t7_esc($reservationUrl); ?>" class="flex-shrink-0 inline-flex items-center gap-2 px-6 py-3 text-[11px] uppercase tracking-[0.18em] font-semibold text-white bg-[#500C19] hover:bg-[#6A1324] border border-[rgba(203,178,136,0.40)] rounded transition-all">
      Reserve a Table <span class="text-[#CBB288]">→</span>
    </a>
  </div>
  <?php endif; ?>

</main>
