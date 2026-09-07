<?php
/**
 * Template 7 home — section directory.
 * Visual pattern: REFERENCES/landing.html + DESIGN_1 tokens.
 * No menu-item listing — sections only (dynamic).
 */

$t7SectionCount = count($sections ?? []);
?>

<section class="relative border-b border-onyx-border py-10 sm:py-14 overflow-hidden section-pattern section-pattern--dark">
  <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_rgba(80,12,25,0.28)_0%,_transparent_65%)] pointer-events-none" aria-hidden="true"></div>
  <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-burgundy-dark/70 border border-champagne-gold/30 mb-4">
      <span class="text-champagne-gold text-[10px]">◆</span>
      <span class="text-[10px] sm:text-[11px] uppercase tracking-[0.26em] text-champagne-light font-medium">Digital Menu</span>
    </div>
    <h1 class="font-serif-luxury text-3xl sm:text-5xl md:text-6xl tracking-wide uppercase text-white font-normal leading-tight">
      <?php echo t7_esc($restaurant['name'] ?? ''); ?>
    </h1>
    <?php if (! empty($restaurant['description'])): ?>
    <p class="font-serif-luxury italic text-base sm:text-xl text-champagne-light/90 font-normal mt-2 mb-4">
      <?php echo t7_esc($restaurant['description']); ?>
    </p>
    <?php else: ?>
    <p class="font-serif-luxury italic text-base sm:text-xl text-champagne-light/90 font-normal mt-2 mb-4">
      Restaurant Digital Menu
    </p>
    <?php endif; ?>
    <?php if (! empty($restaurant['opening_hours'])): ?>
    <div class="inline-flex items-center gap-2.5 px-4 py-1.5 rounded-full bg-onyx-card/90 border border-champagne-gold/30 text-[10px] sm:text-[11px] text-white/80 tracking-wider mb-2">
      <span class="w-2 h-2 rounded-full bg-emerald-400 inline-block flex-shrink-0"></span>
      <span class="font-medium text-champagne-gold"><?php echo t7_esc($restaurant['opening_hours']); ?></span>
    </div>
    <?php endif; ?>
    <div class="flex flex-wrap items-center justify-center gap-3 mt-4">
      <a href="#t7-menu-index" class="inline-flex items-center gap-1.5 px-5 py-2.5 text-[11px] uppercase tracking-[0.18em] font-semibold text-white bg-burgundy-deep hover:bg-burgundy-wine border border-champagne-gold/40 rounded transition-all shadow-sm">
        <span>Explore Menu</span>
        <span class="font-serif-luxury text-sm text-champagne-gold">→</span>
      </a>
      <?php if (! empty($supportsReservations)): ?>
      <a href="<?php echo t7_esc($reservationUrl); ?>" class="inline-flex items-center gap-1.5 px-5 py-2.5 text-[11px] uppercase tracking-[0.18em] font-semibold text-champagne-light hover:text-white border border-champagne-gold/40 hover:border-champagne-gold rounded transition-all">
        Reserve a Table
      </a>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php if (! empty($sectionsForNav) && count($sectionsForNav) > 1): ?>
<div class="sticky top-[4rem] sm:top-[4.5rem] z-40 bg-onyx-surface/95 backdrop-blur border-b border-onyx-border shadow-md">
  <div class="max-w-7xl mx-auto px-4 sm:px-6">
    <div class="flex items-center gap-2 overflow-x-auto py-3 t7-noscroll scroll-smooth">
      <span class="text-[10px] uppercase tracking-[0.20em] text-champagne-gold/60 font-semibold pl-1 pr-2 whitespace-nowrap hidden md:inline">Jump To:</span>
      <?php foreach ($sectionsForNav as $navSec):
        $navSlug = strtolower(trim((string) ($navSec['slug'] ?? '')));
        $navName = $navSec['name'] ?? '';
        $chip = match ($navSlug) {
            'drinks' => 'bg-champagne-gold/15 border-champagne-gold/50 text-champagne-light',
            'national-menu', 'grill', 'vcp-specials' => 'bg-burgundy-deep/80 border-burgundy-wine text-white',
            default => 'bg-white/5 border-white/10 text-white/90 hover:bg-burgundy-deep',
        };
      ?>
      <a href="<?php echo t7_esc(t7_section_url($fullMenuUrl, $navSlug)); ?>"
         class="px-3 py-1.5 text-[11px] uppercase tracking-[0.14em] font-medium rounded border whitespace-nowrap transition-colors <?php echo t7_esc($chip); ?>">
        <?php echo t7_esc($navName); ?>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</div>
<?php endif; ?>

<main class="flex-grow py-10 sm:py-14 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full" id="t7-menu-index">
  <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 sm:mb-10 pb-4 border-b border-onyx-border gap-3">
    <div>
      <span class="text-[10px] uppercase tracking-[0.24em] font-semibold text-champagne-gold block mb-1">Catalog &amp; Selections</span>
      <h2 class="font-serif-luxury text-2xl sm:text-4xl text-white font-normal">Explore Our Menu</h2>
    </div>
    <p class="text-xs sm:text-sm text-white/60 font-light max-w-md">
      Choose a section to explore dishes and selections.
      <?php if (! empty($supportsOrdering)): ?> All items can be added to your bag.<?php endif; ?>
    </p>
  </div>

  <?php if (empty($sections)): ?>
  <p class="text-center text-white/50 py-16 font-light">No menu sections available yet.</p>
  <?php else: ?>
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-5 sm:gap-6">
    <?php foreach ($sections as $i => $section):
      $secSlot    = t7_home_grid_slot($i, $t7SectionCount);
      $secImg     = t7_section_image($uploadBaseUrl, $section);
      $secUrl     = t7_section_url($fullMenuUrl, $section['slug'] ?? '');
      $secName    = $section['name'] ?? '';
      $secDesc    = $section['description'] ?? '';
      $secSlug    = strtolower(trim((string) ($section['slug'] ?? '')));
      $colSpan    = $secSlot['main'];
      $counter    = $secSlot['counter'];
      $isFeatured = $secSlot['featured'];
      $secItemCount = (int) ($section['item_count'] ?? 0);
      $tileAccent = t7_landing_tile_accent($secSlug);
      $worldWide = ($secSlug === 'world' || $secSlug === 'drinks') ? 'lg:col-span-12' : $colSpan;
    ?>
    <a href="<?php echo t7_esc($secUrl); ?>"
       class="group <?php echo t7_esc($worldWide); ?> <?php echo t7_esc($tileAccent); ?> bg-onyx-card border border-onyx-border hover:border-champagne-gold/80 rounded overflow-hidden flex flex-col justify-between transition-all duration-300 hover:shadow-xl hover:shadow-burgundy-dark/40 reveal"
       aria-label="<?php echo t7_esc($secName); ?>">
      <?php if ($secImg): ?>
      <div class="relative <?php echo $isFeatured || $secSlug === 'drinks' || $secSlug === 'world' ? 'h-52 sm:h-64' : 'h-44 sm:h-52'; ?> overflow-hidden bg-onyx-surface">
        <img src="<?php echo t7_esc($secImg); ?>" alt="<?php echo t7_esc($secName); ?>"
             class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-700 opacity-80" loading="lazy">
        <div class="absolute inset-0 bg-gradient-to-t from-onyx-card via-onyx-card/40 to-transparent"></div>
        <?php if ($counter): ?>
        <span class="absolute top-3 right-3 text-xs text-champagne-gold/80 font-mono"><?php echo t7_esc($counter); ?></span>
        <?php endif; ?>
      </div>
      <?php endif; ?>
      <div class="p-5 sm:p-6 flex-grow flex flex-col justify-between">
        <div>
          <div class="flex items-center justify-between mb-2 gap-2">
            <h3 class="font-serif-luxury text-xl sm:text-2xl text-white group-hover:text-champagne-gold transition-colors"><?php echo t7_esc($secName); ?></h3>
            <?php if (! $secImg && $counter): ?><span class="text-xs text-champagne-gold/80 font-mono"><?php echo t7_esc($counter); ?></span><?php endif; ?>
          </div>
          <?php if ($secDesc): ?>
          <p class="type-desc text-xs sm:text-sm text-white/70 mb-4 leading-relaxed line-clamp-2"><?php echo t7_esc($secDesc); ?></p>
          <?php endif; ?>
          <?php if ($secItemCount > 0): ?>
          <p class="text-[10px] uppercase tracking-[0.18em] text-champagne-gold/60 font-medium"><?php echo (int) $secItemCount; ?> item<?php echo $secItemCount !== 1 ? 's' : ''; ?></p>
          <?php endif; ?>
        </div>
        <div class="mt-4 pt-3 border-t border-white/10 flex items-center justify-between">
          <span class="text-[11px] uppercase tracking-[0.16em] text-champagne-light/80 group-hover:text-champagne-gold transition-colors">Explore <?php echo t7_esc($secName); ?></span>
          <span class="font-serif-luxury text-champagne-gold">→</span>
        </div>
      </div>
    </a>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</main>

<?php if (! empty($supportsReservations)): ?>
<section class="border-t border-onyx-border py-10 sm:py-12 bg-onyx-surface">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
    <p class="font-serif-luxury text-lg sm:text-xl text-champagne-light">Ready to dine with us?</p>
    <a href="<?php echo t7_esc($reservationUrl); ?>" class="flex-shrink-0 inline-flex items-center gap-2 px-6 py-3 text-[11px] uppercase tracking-[0.18em] font-semibold text-white bg-burgundy-deep hover:bg-burgundy-wine border border-champagne-gold/40 rounded transition-all">
      Reserve a Table
    </a>
  </div>
</section>
<?php endif; ?>
