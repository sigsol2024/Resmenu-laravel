<?php
/**
 * Template 7 — Ember Noir | Section View
 *
 * Renders: sidebar nav + main content (categories → items)
 * Special visual shells driven by SECTION SLUG only (not restaurant name):
 *   entree        → self-service cream panels; description lines → option lists
 *   national-menu → heritage-style dark panel with burgundy accent
 *   grill         → vertical "GRILLS" belt animation on left edge
 *   drinks        → category-image photo shell on dark ambient background
 */

$t7ActiveSection = $activeSection ?? ($sections[0] ?? null);
$t7SectionSlug   = strtolower(trim((string)($t7ActiveSection['slug'] ?? '')));
$t7SectionName   = $t7ActiveSection['name'] ?? '';
$t7Categories    = $t7ActiveSection['categories'] ?? [];

// Filter to visible categories with items
$t7VisibleCats = array_values(array_filter($t7Categories, function($cat) {
    return ! empty($cat['is_active']) && ! empty($cat['menu_items']);
}));

// Section-slug special modes
$t7IsEntree     = ($t7SectionSlug === 'entree');
$t7IsNational   = ($t7SectionSlug === 'national-menu');
$t7IsGrill      = ($t7SectionSlug === 'grill');
$t7IsDrinks     = ($t7SectionSlug === 'drinks');

// Section hero image
$t7SectionHero  = t7_section_image($uploadBaseUrl, $t7ActiveSection ?? []);

// Body bg for special sections
$t7SectionBodyBg = '#0e0e10';
$t7SectionBodyClass = '';
if ($t7IsEntree) {
    $t7SectionBodyBg   = '#2D1A0D'; // warm terra cotta dark
    $t7SectionBodyClass = 'bg-[#2D1A0D]';
} elseif ($t7IsNational) {
    $t7SectionBodyBg   = '#0e0e10';
    $t7SectionBodyClass = '';
} elseif ($t7IsGrill) {
    $t7SectionBodyBg   = '#0c0c0e';
    $t7SectionBodyClass = 'bg-[#0c0c0e]';
}
?>

<!-- ══════════════════════ SECTION HERO / TITLE BAR ═══════════════════════════ -->
<?php if ($t7SectionHero): ?>
<div class="relative w-full h-[30vh] sm:h-[36vh] min-h-[180px] max-h-[360px] overflow-hidden border-b border-[rgba(203,178,136,0.28)]">
  <img src="<?php echo t7_esc($t7SectionHero); ?>"
       alt="<?php echo t7_esc($t7SectionName); ?>"
       class="absolute inset-0 w-full h-full object-cover object-center"
       loading="eager">
  <div class="absolute inset-0 bg-gradient-to-t from-[#0e0e10] via-[#0e0e10]/50 to-transparent"></div>
  <?php if ($t7IsGrill): ?>
  <div class="absolute inset-0 bg-gradient-to-r from-[#0c0c0e]/80 via-transparent to-transparent"></div>
  <?php endif; ?>
  <div class="absolute bottom-0 left-0 right-0 px-4 sm:px-6 pb-6 sm:pb-8 max-w-7xl mx-auto">
    <p class="text-[10px] uppercase tracking-[0.26em] text-[#CBB288] font-semibold mb-1"><?php echo t7_esc($restaurant['name'] ?? ''); ?></p>
    <h1 class="t7-serif text-2xl sm:text-4xl text-white font-normal"><?php echo t7_esc($t7SectionName); ?></h1>
  </div>
</div>

<?php else: ?>
<!-- Title bar (no hero image) -->
<div class="border-b border-[rgba(203,178,136,0.28)] py-6 sm:py-8 bg-gradient-to-b from-[#16141a] to-[#0e0e10] t7-pattern-shell">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 relative z-10">
    <p class="text-[10px] uppercase tracking-[0.26em] text-[#CBB288] font-semibold mb-1"><?php echo t7_esc($restaurant['name'] ?? ''); ?></p>
    <h1 class="t7-serif text-2xl sm:text-4xl text-white font-normal"><?php echo t7_esc($t7SectionName); ?></h1>
  </div>
</div>
<?php endif; ?>

<?php if ($t7IsGrill): ?>
<!-- ── Grill: global belt styles injected here ──────────────────────────────── -->
<style>
  .t7-grill-section { position: relative; }
  .t7-grill-section > .t7-grill-inner { padding-left: 3rem; }
  @media (min-width: 640px) { .t7-grill-section > .t7-grill-inner { padding-left: 3.5rem; } }
</style>
<?php endif; ?>

<!-- ══════════════════════ TWO-COLUMN LAYOUT ════════════════════════════════ -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 t7-layout py-8 sm:py-10 gap-0 lg:gap-10 <?php echo $t7SectionBodyClass; ?>">

  <!-- ── LEFT SIDEBAR (desktop) ──────────────────────────────────────────── -->
  <aside class="hidden lg:block">
    <nav class="sticky top-28 flex flex-col gap-1" aria-label="Categories in <?php echo t7_esc($t7SectionName); ?>">
      <p class="text-[10px] uppercase tracking-[0.22em] text-[#CBB288]/60 font-semibold px-3 mb-2">In This Section</p>
      <?php foreach ($t7VisibleCats as $sidecat):
        $sideCatSlug = $sidecat['slug'] ?? ('cat-' . ($sidecat['id'] ?? 0));
      ?>
      <a href="#cat-<?php echo t7_esc($sideCatSlug); ?>"
         class="t7-sidebar-link block py-2.5 px-3 text-[11px] uppercase tracking-[0.14em] font-medium text-[#F6F1E8]/70 hover:text-[#CBB288] border-l-2 border-transparent hover:border-[#CBB288] hover:bg-[#500C19]/20 rounded-r transition-all">
        <?php echo t7_esc($sidecat['name'] ?? ''); ?>
      </a>
      <?php endforeach; ?>

      <?php if (! empty($fullMenuUrl)): ?>
      <div class="mt-4 pt-4 border-t border-white/10">
        <a href="<?php echo t7_esc($fullMenuUrl); ?>" class="block py-2.5 px-3 text-[11px] uppercase tracking-[0.14em] font-medium text-[#F6F1E8]/50 hover:text-[#CBB288] transition-colors">
          ← All Sections
        </a>
      </div>
      <?php endif; ?>
    </nav>
  </aside>

  <!-- ── MAIN CONTENT ──────────────────────────────────────────────────────── -->
  <main class="min-w-0">

    <!-- Mobile category tabs -->
    <?php if (count($t7VisibleCats) > 1): ?>
    <div class="lg:hidden overflow-x-auto t7-noscroll mb-8">
      <div class="flex gap-2 pb-1">
        <?php foreach ($t7VisibleCats as $mcat):
          $mcatSlug = $mcat['slug'] ?? ('cat-' . ($mcat['id'] ?? 0));
        ?>
        <a href="#cat-<?php echo t7_esc($mcatSlug); ?>"
           class="flex-shrink-0 px-3 py-1.5 text-[11px] uppercase tracking-[0.14em] font-medium rounded bg-white/5 hover:bg-[#500C19] text-[#F6F1E8] hover:text-white border border-white/10 hover:border-[rgba(203,178,136,0.40)] transition-colors whitespace-nowrap">
          <?php echo t7_esc($mcat['name'] ?? ''); ?>
        </a>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

    <?php if (empty($t7VisibleCats)): ?>
    <p class="text-[#F6F1E8]/50 py-12 text-center font-light">No items available in this section yet.</p>
    <?php endif; ?>

    <?php foreach ($t7VisibleCats as $catIdx => $cat):
      $catSlug  = $cat['slug'] ?? ('cat-' . ($cat['id'] ?? $catIdx));
      $catName  = $cat['name'] ?? '';
      $catDesc  = $cat['description'] ?? '';
      $catItems = $cat['menu_items'] ?? [];
      $catImg   = t7_category_image($uploadBaseUrl, $cat);

      // ── CATEGORY LEVEL: determine rendering mode ─────────────────────────
      // drinks: if has image → photo shell
      $isDrinksCatWithPhoto = $t7IsDrinks && ! empty($catImg);
    ?>

    <!-- ── CATEGORY SECTION ─────────────────────────────────────────────── -->
    <section id="cat-<?php echo t7_esc($catSlug); ?>"
             class="t7-cat-section mb-12 sm:mb-16 scroll-mt-32 <?php if ($t7IsGrill) echo 't7-grill-section'; ?>">

      <?php if ($t7IsGrill): ?>
      <!-- Grill belt on left -->
      <div class="t7-grill-belt" aria-hidden="true">
        <div class="t7-grill-belt-track">
          <?php for ($gi = 0; $gi < 18; $gi++): ?>
          <span class="t7-grill-belt-word">GRILLS</span>
          <?php endfor; ?>
          <?php for ($gi = 0; $gi < 18; $gi++): ?>
          <span class="t7-grill-belt-word">GRILLS</span>
          <?php endfor; ?>
        </div>
      </div>
      <?php endif; ?>

      <!-- ── Category header ──────────────────────────────────────────────── -->
      <?php if ($isDrinksCatWithPhoto): ?>
      <!-- Drinks: atmospheric photo header for category -->
      <div class="relative mb-6 rounded overflow-hidden h-32 sm:h-40 border border-[rgba(203,178,136,0.28)]">
        <img src="<?php echo t7_esc($catImg); ?>"
             alt="<?php echo t7_esc($catName); ?>"
             class="absolute inset-0 w-full h-full object-cover object-center opacity-50"
             loading="lazy">
        <div class="absolute inset-0 bg-gradient-to-r from-[#0e0e10]/95 via-[#0e0e10]/70 to-transparent"></div>
        <div class="absolute inset-0 flex items-center px-5 sm:px-6">
          <div>
            <?php if ($catIdx > 0 || ! empty($catDesc)): ?>
            <p class="text-[10px] uppercase tracking-[0.22em] text-[#CBB288] font-semibold mb-1">Drinks</p>
            <?php endif; ?>
            <h2 class="t7-serif text-xl sm:text-2xl text-white font-normal"><?php echo t7_esc($catName); ?></h2>
            <?php if ($catDesc): ?><p class="text-xs text-[#F6F1E8]/60 mt-1 line-clamp-1"><?php echo t7_esc($catDesc); ?></p><?php endif; ?>
          </div>
        </div>
      </div>

      <?php elseif ($t7IsNational): ?>
      <!-- National menu: heritage-style heading bar -->
      <div class="mb-6 p-4 sm:p-5 bg-[#38060F]/60 border border-[#6A1324]/50 rounded relative overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_right,_rgba(80,12,25,0.3)_0%,_transparent_70%)] pointer-events-none"></div>
        <div class="relative z-10">
          <span class="text-[10px] uppercase tracking-[0.26em] text-[#CBB288]/70 font-semibold">Heritage Recipe</span>
          <h2 class="t7-serif text-xl sm:text-2xl text-white font-normal mt-0.5"><?php echo t7_esc($catName); ?></h2>
          <?php if ($catDesc): ?><p class="text-xs text-[#F6F1E8]/60 mt-1"><?php echo t7_esc($catDesc); ?></p><?php endif; ?>
        </div>
      </div>

      <?php elseif ($t7IsEntree): ?>
      <!-- Entree: warm section heading -->
      <div class="mb-6 border-b border-[rgba(80,12,25,0.25)] pb-4">
        <h2 class="t7-serif text-xl sm:text-2xl text-[#FFFFF6] font-normal"><?php echo t7_esc($catName); ?></h2>
        <?php if ($catDesc): ?><p class="text-xs text-[#FFFFF6]/60 mt-1"><?php echo t7_esc($catDesc); ?></p><?php endif; ?>
      </div>

      <?php else: ?>
      <!-- Default category heading -->
      <div class="mb-6 pb-4 border-b border-[rgba(203,178,136,0.28)] flex items-end justify-between gap-4">
        <div>
          <h2 class="t7-serif text-xl sm:text-2xl text-white font-normal"><?php echo t7_esc($catName); ?></h2>
          <?php if ($catDesc): ?><p class="text-xs text-[#F6F1E8]/60 mt-1"><?php echo t7_esc($catDesc); ?></p><?php endif; ?>
        </div>
        <span class="text-[10px] uppercase tracking-[0.20em] text-[#CBB288]/50 font-medium whitespace-nowrap"><?php echo count($catItems); ?> item<?php echo count($catItems) !== 1 ? 's' : ''; ?></span>
      </div>
      <?php endif; ?>

      <!-- ── Items grid ────────────────────────────────────────────────────── -->
      <?php if ($t7IsEntree): ?>
      <!-- ENTREE: cream self-service panels -->
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <?php foreach ($catItems as $item):
          $itemAvailable = ! isset($item['is_available']) || $item['is_available'];
          $itemImg  = t7_item_image($uploadBaseUrl, $item);
          $itemName = $item['name'] ?? '';
          $itemPrice= $item['price'] ?? '';
          $itemDesc = trim((string)($item['description'] ?? ''));
          // Split description into option lines (each non-empty line is an option)
          $descLines = array_values(array_filter(array_map('trim', explode("\n", $itemDesc))));
          $hasOptions = count($descLines) > 1;
        ?>
        <div class="t7-entree-panel p-4 sm:p-5 t7-reveal <?php echo $itemAvailable ? '' : 'opacity-50'; ?>">
          <div class="flex items-start gap-3">
            <?php if ($itemImg): ?>
            <img src="<?php echo t7_esc($itemImg); ?>"
                 alt="<?php echo t7_esc($itemName); ?>"
                 class="w-16 h-16 sm:w-20 sm:h-20 flex-shrink-0 object-cover rounded"
                 loading="lazy">
            <?php endif; ?>
            <div class="flex-1 min-w-0">
              <div class="flex items-baseline justify-between gap-2 mb-1">
                <h3 class="text-sm sm:text-base font-semibold text-[#1c1917] leading-snug"><?php echo t7_esc($itemName); ?></h3>
                <span class="t7-serif text-sm font-medium text-[#500C19] flex-shrink-0"><?php echo t7_price($itemPrice); ?></span>
              </div>
              <?php if ($hasOptions): ?>
              <!-- Render description lines as option list -->
              <p class="text-[10px] uppercase tracking-[0.18em] font-semibold text-[#78716c] mb-1">Options:</p>
              <ul class="text-xs text-[#44403c] space-y-0.5 list-disc list-inside">
                <?php foreach ($descLines as $dl): ?>
                <li><?php echo t7_esc($dl); ?></li>
                <?php endforeach; ?>
              </ul>
              <?php elseif ($itemDesc): ?>
              <p class="text-xs text-[#57534e] leading-relaxed"><?php echo t7_esc($itemDesc); ?></p>
              <?php endif; ?>
            </div>
          </div>
          <?php if (! empty($supportsOrdering) && $itemAvailable): ?>
          <div class="mt-3 flex justify-end">
            <button type="button"
                    class="add-to-bag-btn text-[11px] uppercase tracking-[0.14em] font-semibold text-[#500C19] border border-[#500C19] px-3 py-1.5 rounded hover:bg-[#500C19] hover:text-white transition-colors"
                    data-item-id="<?php echo (int)($item['id'] ?? 0); ?>"
                    data-item-name="<?php echo t7_esc($itemName); ?>"
                    data-item-price="<?php echo t7_esc((string)$itemPrice); ?>"
                    data-item-image="<?php echo t7_esc($item['image'] ?? ''); ?>">
              Add to bag
            </button>
          </div>
          <?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>

      <?php elseif ($t7IsNational): ?>
      <!-- NATIONAL MENU: heritage card grid -->
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
        <?php foreach ($catItems as $item):
          $itemAvailable = ! isset($item['is_available']) || $item['is_available'];
          $itemImg  = t7_item_image($uploadBaseUrl, $item);
          $itemName = $item['name'] ?? '';
          $itemPrice= $item['price'] ?? '';
          $itemDesc = trim((string)($item['description'] ?? ''));
        ?>
        <div class="group bg-[#1D1C21] border border-[rgba(203,178,136,0.28)] hover:border-[rgba(203,178,136,0.60)] rounded p-4 sm:p-5 transition-colors t7-reveal <?php echo $itemAvailable ? '' : 'opacity-40'; ?>">
          <?php if ($itemImg): ?>
          <div class="relative h-32 mb-3 overflow-hidden rounded">
            <img src="<?php echo t7_esc($itemImg); ?>"
                 alt="<?php echo t7_esc($itemName); ?>"
                 class="w-full h-full object-cover group-hover:scale-103 transition-transform duration-500"
                 loading="lazy">
            <div class="absolute inset-0 bg-gradient-to-t from-[#1D1C21]/80 to-transparent"></div>
          </div>
          <?php endif; ?>
          <div class="flex items-baseline justify-between gap-2 mb-1.5">
            <h3 class="t7-serif text-base sm:text-lg text-white font-normal leading-snug"><?php echo t7_esc($itemName); ?></h3>
            <span class="t7-serif text-sm font-medium text-[#CBB288] flex-shrink-0"><?php echo t7_price($itemPrice); ?></span>
          </div>
          <?php if ($itemDesc): ?>
          <p class="text-xs text-[#F6F1E8]/60 leading-relaxed"><?php echo t7_esc($itemDesc); ?></p>
          <?php endif; ?>
          <?php if (! empty($supportsOrdering) && $itemAvailable): ?>
          <div class="mt-3">
            <button type="button"
                    class="add-to-bag-btn text-[11px] uppercase tracking-[0.14em] font-semibold text-[#CBB288] border border-[rgba(203,178,136,0.40)] px-3 py-1.5 rounded hover:bg-[#500C19] hover:text-white hover:border-[#500C19] transition-colors"
                    data-item-id="<?php echo (int)($item['id'] ?? 0); ?>"
                    data-item-name="<?php echo t7_esc($itemName); ?>"
                    data-item-price="<?php echo t7_esc((string)$itemPrice); ?>"
                    data-item-image="<?php echo t7_esc($item['image'] ?? ''); ?>">
              Add to bag
            </button>
          </div>
          <?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>

      <?php elseif ($t7IsDrinks): ?>
      <!-- DRINKS: ambient dark item list -->
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
        <?php foreach ($catItems as $item):
          $itemAvailable = ! isset($item['is_available']) || $item['is_available'];
          $itemImg  = t7_item_image($uploadBaseUrl, $item);
          $itemName = $item['name'] ?? '';
          $itemPrice= $item['price'] ?? '';
          $itemDesc = trim((string)($item['description'] ?? ''));
        ?>
        <div class="flex gap-3 border-b border-white/10 pb-3 items-start t7-reveal <?php echo $itemAvailable ? '' : 'opacity-40'; ?>">
          <?php if ($itemImg): ?>
          <img src="<?php echo t7_esc($itemImg); ?>"
               alt="<?php echo t7_esc($itemName); ?>"
               class="w-12 h-12 flex-shrink-0 object-cover rounded"
               loading="lazy">
          <?php endif; ?>
          <div class="flex-1 min-w-0">
            <div class="flex items-baseline justify-between gap-2 mb-0.5">
              <h3 class="text-sm font-medium text-[#F6F1E8] leading-snug"><?php echo t7_esc($itemName); ?></h3>
              <span class="t7-serif text-sm text-[#CBB288] flex-shrink-0"><?php echo t7_price($itemPrice); ?></span>
            </div>
            <?php if ($itemDesc): ?>
            <p class="text-xs text-[#F6F1E8]/50 leading-relaxed"><?php echo t7_esc($itemDesc); ?></p>
            <?php endif; ?>
          </div>
          <?php if (! empty($supportsOrdering) && $itemAvailable): ?>
          <button type="button"
                  class="add-to-bag-btn flex-shrink-0 text-[10px] uppercase tracking-wider font-semibold text-[#CBB288] border border-[rgba(203,178,136,0.40)] px-2 py-1 rounded hover:bg-[#500C19] hover:text-white hover:border-[#500C19] transition-colors"
                  data-item-id="<?php echo (int)($item['id'] ?? 0); ?>"
                  data-item-name="<?php echo t7_esc($itemName); ?>"
                  data-item-price="<?php echo t7_esc((string)$itemPrice); ?>"
                  data-item-image="<?php echo t7_esc($item['image'] ?? ''); ?>">
            Add
          </button>
          <?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>

      <?php else: ?>
      <!-- DEFAULT: 2-column item card grid -->
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5 <?php echo $t7IsGrill ? 't7-grill-inner' : ''; ?>">
        <?php foreach ($catItems as $item):
          $itemAvailable = ! isset($item['is_available']) || $item['is_available'];
          $itemImg  = t7_item_image($uploadBaseUrl, $item);
          $itemName = $item['name'] ?? '';
          $itemPrice= $item['price'] ?? '';
          $itemDesc = trim((string)($item['description'] ?? ''));
        ?>
        <div class="group bg-[#1D1C21] border border-[rgba(203,178,136,0.18)] hover:border-[rgba(203,178,136,0.50)] rounded p-4 sm:p-5 transition-all t7-reveal <?php echo $itemAvailable ? '' : 'opacity-40'; ?> <?php echo $t7IsGrill ? 'border-l-2 border-l-[#500C19]/40 hover:border-l-[#CBB288]/60' : ''; ?>">
          <?php if ($itemImg): ?>
          <div class="relative h-36 sm:h-40 mb-3 overflow-hidden rounded -mx-0.5">
            <img src="<?php echo t7_esc($itemImg); ?>"
                 alt="<?php echo t7_esc($itemName); ?>"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                 loading="lazy">
            <div class="absolute inset-0 bg-gradient-to-t from-[#1D1C21]/90 to-transparent"></div>
          </div>
          <?php endif; ?>
          <div class="flex items-baseline justify-between gap-2 mb-1.5">
            <h3 class="t7-serif text-base sm:text-lg text-white font-normal leading-snug group-hover:text-[#EEDDC0] transition-colors"><?php echo t7_esc($itemName); ?></h3>
            <span class="t7-serif text-sm font-medium text-[#CBB288] flex-shrink-0 whitespace-nowrap"><?php echo t7_price($itemPrice); ?></span>
          </div>
          <?php if ($itemDesc): ?>
          <p class="text-xs text-[#F6F1E8]/60 leading-relaxed mb-2"><?php echo t7_esc($itemDesc); ?></p>
          <?php endif; ?>
          <?php if (! empty($supportsOrdering) && $itemAvailable): ?>
          <div class="mt-2 pt-2 border-t border-white/10">
            <button type="button"
                    class="add-to-bag-btn text-[11px] uppercase tracking-[0.14em] font-semibold text-[#CBB288] border border-[rgba(203,178,136,0.40)] px-3 py-1.5 rounded hover:bg-[#500C19] hover:text-white hover:border-[#500C19] transition-colors"
                    data-item-id="<?php echo (int)($item['id'] ?? 0); ?>"
                    data-item-name="<?php echo t7_esc($itemName); ?>"
                    data-item-price="<?php echo t7_esc((string)$itemPrice); ?>"
                    data-item-image="<?php echo t7_esc($item['image'] ?? ''); ?>">
              Add to bag
            </button>
          </div>
          <?php elseif (! $itemAvailable): ?>
          <p class="mt-2 text-[10px] uppercase tracking-wider text-[#F6F1E8]/30">Currently unavailable</p>
          <?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; // end item render modes ?>

    </section><!-- /t7-cat-section -->
    <?php endforeach; // end categories loop ?>

    <!-- Back to sections link (mobile bottom) -->
    <?php if (! empty($fullMenuUrl)): ?>
    <div class="mt-10 pt-8 border-t border-[rgba(203,178,136,0.28)] flex items-center justify-between">
      <a href="<?php echo t7_esc($fullMenuUrl); ?>" class="inline-flex items-center gap-2 text-xs uppercase tracking-[0.16em] font-medium text-[#F6F1E8]/60 hover:text-[#CBB288] transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
        All Sections
      </a>
      <?php if (! empty($supportsReservations)): ?>
      <a href="<?php echo t7_esc($reservationUrl); ?>" class="inline-flex items-center gap-1.5 px-4 py-2 text-[11px] uppercase tracking-[0.16em] font-semibold text-white bg-[#500C19] hover:bg-[#6A1324] border border-[rgba(203,178,136,0.40)] rounded transition-all">
        Reserve a Table
      </a>
      <?php endif; ?>
    </div>
    <?php endif; ?>

  </main><!-- /main -->
</div><!-- /t7-layout -->
