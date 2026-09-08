<?php
/**
 * Template 7 home — DESIGN_1-style hero + search, themed directory, reservation.
 */
$t7SectionCount = count($sections ?? []);
$menuSearchIndex = $menuSearchIndex ?? [];
$t7HeroImg = $restaurant['hero_image_url'] ?? null;
if (empty($t7HeroImg) && ! empty($sections[0])) {
    $t7HeroImg = t7_section_image($uploadBaseUrl, $sections[0]);
}
?>

<section class="relative min-h-[78vh] sm:min-h-[88vh] flex items-end justify-center pt-24 pb-14 px-4 sm:px-6 overflow-hidden section-pattern section-pattern--dark" id="hero">
  <?php if ($t7HeroImg): ?>
  <div class="absolute inset-0 z-0">
    <img alt="" class="w-full h-full object-cover object-center opacity-55" src="<?php echo t7_esc($t7HeroImg); ?>" loading="eager">
    <div class="absolute inset-0 bg-gradient-to-t from-onyx-surface via-onyx-surface/80 to-burgundy-deep/45"></div>
  </div>
  <?php else: ?>
  <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_rgba(80,12,25,0.42)_0%,_transparent_62%)] pointer-events-none z-0" aria-hidden="true"></div>
  <?php endif; ?>

  <div class="relative z-10 w-full max-w-3xl mx-auto text-center space-y-5">
    <h1 class="font-serif-luxury text-4xl sm:text-6xl md:text-7xl text-white font-normal tracking-tight leading-[1.1] break-words px-1">
      <?php echo t7_esc($restaurant['name'] ?? ''); ?>
    </h1>

    <?php if (! empty($restaurant['description'])): ?>
    <p class="type-desc text-sm sm:text-base text-stone-300 max-w-xl mx-auto leading-relaxed">
      <?php echo t7_esc($restaurant['description']); ?>
    </p>
    <?php else: ?>
    <p class="font-serif-luxury italic text-base sm:text-xl text-champagne-light/90">Restaurant Digital Menu</p>
    <?php endif; ?>

    <div class="relative max-w-md mx-auto pt-2">
      <div class="relative flex items-center bg-black/70 backdrop-blur-xl border border-white/20 rounded-full shadow-2xl p-1.5 focus-within:border-champagne-gold transition-all">
        <span class="pl-3 pr-2 text-champagne-gold" aria-hidden="true">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
        </span>
        <input class="w-full bg-transparent border-none text-xs sm:text-sm text-white placeholder-stone-400 focus:outline-none focus:ring-0"
               id="menu-search-input"
               placeholder="Search dishes, wine, cuts, cocktails..."
               type="search"
               autocomplete="off"
               aria-autocomplete="list"
               aria-controls="search-suggestions"
               role="combobox"
               aria-expanded="false">
        <button type="button" class="hidden text-stone-400 hover:text-white px-3 py-2 text-xs uppercase tracking-wider min-h-[44px]" id="clear-search-btn">Clear</button>
      </div>
      <div class="absolute left-0 right-0 top-full mt-2 hidden z-50 rounded-xl border border-onyx-border bg-onyx-card shadow-2xl max-h-72 overflow-y-auto text-left" id="search-suggestions" role="listbox"></div>
    </div>

    <div class="flex flex-wrap items-center justify-center gap-2 pt-2">
      <span class="px-3 py-1.5 rounded-md text-[11px] bg-white/10 text-stone-200 border border-white/10 inline-flex items-center gap-1.5">
        <span class="material-symbols-outlined text-[13px] text-champagne-gold" aria-hidden="true">wb_sunny</span>
        Breakfast 06:30 – 11:00
      </span>
      <span class="px-3 py-1.5 rounded-md text-[11px] bg-white/10 text-stone-200 border border-white/10 inline-flex items-center gap-1.5">
        <span class="material-symbols-outlined text-[13px] text-champagne-gold" aria-hidden="true">restaurant</span>
        All-Day Carte 12:00 – 23:00
      </span>
      <span class="px-3 py-1.5 rounded-md text-[11px] bg-white/10 text-stone-200 border border-white/10 inline-flex items-center gap-1.5">
        <span class="material-symbols-outlined text-[13px] text-champagne-gold" aria-hidden="true">room_service</span>
        In-Room Cloche 24 Hours
      </span>
    </div>

    <div class="flex flex-wrap items-center justify-center gap-3 pt-1">
      <a href="#t7-menu-index" class="inline-flex items-center gap-1.5 px-5 py-2.5 min-h-[44px] text-[11px] uppercase tracking-[0.18em] font-semibold text-white bg-burgundy-deep hover:bg-burgundy-wine border border-champagne-gold/40 rounded transition-all">
        Explore Menu <span class="font-serif-luxury text-sm text-champagne-gold">→</span>
      </a>
      <?php if (! empty($supportsReservations)): ?>
      <a href="#reservation" class="inline-flex items-center gap-1.5 px-5 py-2.5 min-h-[44px] text-[11px] uppercase tracking-[0.18em] font-semibold text-champagne-light hover:text-white border border-champagne-gold/40 hover:border-champagne-gold rounded transition-all">
        Reserve a Table
      </a>
      <?php endif; ?>
    </div>
  </div>
</section>

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
  <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-12 gap-3 sm:gap-5 lg:gap-6">
    <?php foreach ($sections as $i => $section):
      $secSlot    = t7_home_grid_slot($i, $t7SectionCount);
      $secImg     = t7_section_image($uploadBaseUrl, $section);
      $secUrl     = t7_section_url($fullMenuUrl, $section['slug'] ?? '');
      $secName    = (string) ($section['name'] ?? '');
      $secDesc    = $section['description'] ?? '';
      $secSlug    = strtolower(trim((string) ($section['slug'] ?? '')));
      $colSpan    = $secSlot['main'];
      $counter    = $secSlot['counter'];
      $isFeatured = ! empty($secSlot['featured']);
      $secItemCount = (int) ($section['item_count'] ?? 0);
      $cardSkin   = t7_landing_card_classes($secSlug);
      $nameLen    = function_exists('mb_strlen') ? mb_strlen($secName) : strlen($secName);
      $hasImage   = (bool) $secImg;
      // Image-led / featured / long titles = full width; only short text-only tiles share a row.
      $isWideMobile = $isFeatured
        || $hasImage
        || $nameLen > 14
        || in_array($secSlug, ['drinks', 'world', 'national-menu', 'grill'], true);
      $mobileSpan = $isWideMobile ? 'col-span-2' : 'col-span-1';
      $lgSpan     = ($secSlug === 'world' || $secSlug === 'drinks') ? 'lg:col-span-12' : $colSpan;
      $horiz      = $hasImage && $nameLen > 18 && ! $isFeatured;
      $layoutCls  = $horiz
        ? 'flex-row items-stretch'
        : (($hasImage || $isFeatured) ? 'flex-col' : 'flex-col justify-between');
    ?>
    <a href="<?php echo t7_esc($secUrl); ?>"
       class="group <?php echo t7_esc($mobileSpan.' '.$lgSpan.' '.$cardSkin['card']); ?> border rounded overflow-hidden flex <?php echo t7_esc($layoutCls); ?> transition-all duration-300 hover:shadow-xl hover:shadow-burgundy-dark/30 reveal min-h-[44px]"
       aria-label="<?php echo t7_esc($secName); ?>">
      <?php if ($hasImage): ?>
      <div class="relative overflow-hidden bg-black/20 flex-shrink-0 <?php echo $horiz ? 'w-[42%] min-h-[7.5rem]' : (($isFeatured || $secSlug === 'drinks' || $secSlug === 'world') ? 'h-44 sm:h-60' : 'h-32 sm:h-48'); ?>">
        <img src="<?php echo t7_esc($secImg); ?>" alt="<?php echo t7_esc($secName); ?>"
             class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-700 opacity-85" loading="lazy">
        <div class="absolute inset-0 bg-gradient-to-t from-black/55 via-transparent to-transparent"></div>
        <?php if ($counter && ! $horiz): ?>
        <span class="absolute top-3 right-3 text-xs text-champagne-gold/80 font-mono"><?php echo t7_esc($counter); ?></span>
        <?php endif; ?>
      </div>
      <?php endif; ?>
      <div class="p-3.5 sm:p-6 flex-grow flex flex-col justify-between min-w-0 <?php echo $horiz ? 'py-3' : ''; ?>">
        <div>
          <div class="flex items-start justify-between mb-1.5 gap-2">
            <h3 class="font-serif-luxury <?php echo $mobileSpan === 'col-span-1' ? 'text-sm sm:text-xl' : 'text-base sm:text-2xl'; ?> leading-snug break-words <?php echo t7_esc($cardSkin['title']); ?> group-hover:opacity-90 transition-colors">
              <?php echo t7_esc($secName); ?>
            </h3>
            <?php if (! $hasImage && $counter): ?>
            <span class="text-xs font-mono shrink-0 <?php echo t7_esc($cardSkin['meta']); ?>"><?php echo t7_esc($counter); ?></span>
            <?php endif; ?>
          </div>
          <?php if ($secDesc && ($isWideMobile || ! $horiz)): ?>
          <p class="type-desc text-[11px] sm:text-sm mb-2 leading-relaxed line-clamp-2 <?php echo t7_esc($cardSkin['body']); ?>"><?php echo t7_esc($secDesc); ?></p>
          <?php endif; ?>
          <?php if ($secItemCount > 0): ?>
          <p class="text-[10px] uppercase tracking-[0.16em] font-medium <?php echo t7_esc($cardSkin['meta']); ?>">
            <?php echo (int) $secItemCount; ?> item<?php echo $secItemCount !== 1 ? 's' : ''; ?>
          </p>
          <?php endif; ?>
        </div>
        <div class="mt-3 pt-2.5 border-t flex items-center justify-between gap-2 <?php echo t7_esc($cardSkin['cta']); ?>">
          <span class="text-[10px] sm:text-[11px] uppercase tracking-[0.14em]">Explore</span>
          <span class="font-serif-luxury text-champagne-gold">→</span>
        </div>
      </div>
    </a>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</main>

<?php include __DIR__ . '/../partials/reservation-embed.php'; ?>
