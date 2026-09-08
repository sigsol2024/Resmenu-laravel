<?php
/**
 * Template 7 section view — DESIGN_1 per-slug shells.
 * Data: $activeSection → categories → menu_items (dynamic).
 */

$t7ActiveSection = $activeSection ?? ($sections[0] ?? null);
$t7SectionSlug   = strtolower(trim((string) ($t7ActiveSection['slug'] ?? '')));
$t7SectionName   = $t7ActiveSection['name'] ?? '';
$t7Categories    = $t7ActiveSection['categories'] ?? [];
$theme           = t7_section_theme($t7SectionSlug);
$t7SectionHero   = t7_section_image($uploadBaseUrl, $t7ActiveSection ?? []);

$t7VisibleCats = array_values(array_filter($t7Categories, static function ($cat) {
    return ! empty($cat['is_active']) && ! empty($cat['menu_items']);
}));

$isLight   = $theme['tone'] === 'light';
$isDark    = in_array($theme['tone'], ['dark', 'national'], true);
$isEntree  = $theme['tone'] === 'entree';
$isDrinks  = $theme['tone'] === 'drinks';
$isGrill   = $t7SectionSlug === 'grill';
$isNational = $t7SectionSlug === 'national-menu';

$shellClasses = $theme['shell'];
if ($theme['photo'] && $t7SectionHero) {
    $shellClasses .= ' section-photo-bg';
}

$navMuted = $isLight || $isDrinks ? 'text-stone-500' : 'text-white/50';
$navLink  = $isLight || $isDrinks
    ? 'text-stone-700 hover:text-burgundy-deep border-transparent hover:border-burgundy-deep hover:bg-burgundy-deep/5'
    : 'text-stone-300 hover:text-champagne-light border-transparent hover:border-champagne-gold hover:bg-white/10';
$titleColor = ($isLight || $isDrinks) ? 'text-stone-900' : 'text-white';
$subColor   = ($isLight || $isDrinks) ? 'text-burgundy-deep' : 'text-champagne-gold';
?>

<section
  class="py-10 sm:py-16 <?php echo t7_esc($shellClasses); ?> relative <?php echo ($isEntree || $isDrinks) ? 'overflow-x-clip overflow-y-visible' : 'overflow-hidden'; ?> min-h-[60vh]"
  <?php if ($theme['photo'] && $t7SectionHero): ?>style="--photo:url('<?php echo t7_esc($t7SectionHero); ?>')"<?php endif; ?>
>
  <?php if ($theme['photo'] && $t7SectionHero): ?>
  <div class="section-photo-layer" aria-hidden="true"></div>
  <div class="section-photo-veil" aria-hidden="true"></div>
  <?php endif; ?>

  <?php if ($isGrill): ?>
  <div class="grill-belt" aria-hidden="true">
    <div class="grill-belt-track">
      <?php for ($g = 0; $g < 8; $g++): ?><span>GRILLS</span><?php endfor; ?>
      <?php for ($g = 0; $g < 8; $g++): ?><span>GRILLS</span><?php endfor; ?>
    </div>
  </div>
  <?php endif; ?>

  <div class="t7-section-inner max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-[1] <?php echo $isEntree ? 'md:pr-16 lg:pr-24' : ''; ?> <?php echo $isGrill ? 'pl-10 sm:pl-12 md:pl-20 lg:pl-24' : ''; ?>">
    <!-- Section title -->
    <div class="mb-8 sm:mb-12">
      <?php if ($isEntree): ?>
      <span class="text-xs uppercase tracking-[0.25em] text-white/70 font-semibold">Entree · Build your own</span>
      <h1 class="font-serif-luxury text-3xl sm:text-5xl text-white mt-1 reveal reveal-fade">
        <?php echo t7_esc($t7SectionName); ?>
      </h1>
      <?php elseif ($isNational): ?>
      <p class="text-[10px] uppercase tracking-[0.26em] font-semibold mb-2 <?php echo t7_esc($subColor); ?>">
        <?php echo t7_esc($restaurant['name'] ?? ''); ?>
      </p>
      <h1 class="font-serif-luxury text-3xl sm:text-5xl font-normal tracking-wide <?php echo t7_esc($titleColor); ?>">
        <?php echo t7_esc($t7SectionName); ?>
      </h1>
      <p class="mt-2 text-sm text-champagne-light/80 font-light max-w-xl">Protein + accompaniment, prepared à la minute.</p>
      <?php else: ?>
      <p class="text-[10px] uppercase tracking-[0.26em] font-semibold mb-2 <?php echo t7_esc($subColor); ?>">
        <?php echo t7_esc($restaurant['name'] ?? ''); ?>
      </p>
      <h1 class="font-serif-luxury text-3xl sm:text-5xl font-normal tracking-wide <?php echo t7_esc($titleColor); ?> reveal reveal-fade">
        <?php echo t7_esc($t7SectionName); ?>
      </h1>
      <?php endif; ?>
    </div>

    <!-- Mobile category jump -->
    <?php if (count($t7VisibleCats) > 1): ?>
    <div class="lg:hidden overflow-x-auto t7-noscroll mb-8 -mx-4 px-4">
      <div class="flex gap-2 pb-1">
        <?php foreach ($t7VisibleCats as $mcat):
          $mcatSlug = $mcat['slug'] ?? ('cat-'.($mcat['id'] ?? 0));
        ?>
        <a href="#cat-<?php echo t7_esc($mcatSlug); ?>"
           class="flex-shrink-0 px-3 py-1.5 text-[11px] uppercase tracking-[0.14em] font-medium rounded border whitespace-nowrap transition-colors <?php echo $isLight || $isDrinks ? 'bg-white border-stone-200 text-stone-700 hover:border-burgundy-deep' : 'bg-white/5 border-white/10 text-stone-200 hover:border-champagne-gold'; ?>">
          <?php echo t7_esc($mcat['name'] ?? ''); ?>
        </a>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

    <div class="<?php echo ($isEntree || $isGrill) ? 'block' : 't7-layout gap-0 lg:gap-10'; ?>">
      <?php if (! $isEntree && ! $isGrill): ?>
      <!-- Sidebar -->
      <aside class="hidden lg:block">
        <nav class="sticky top-28 flex flex-col gap-1" aria-label="Categories">
          <p class="text-[10px] uppercase tracking-[0.22em] font-semibold px-3 mb-2 <?php echo t7_esc($navMuted); ?>">In This Section</p>
          <?php foreach ($t7VisibleCats as $sidecat):
            $sideCatSlug = $sidecat['slug'] ?? ('cat-'.($sidecat['id'] ?? 0));
          ?>
          <a href="#cat-<?php echo t7_esc($sideCatSlug); ?>"
             class="t7-sidebar-link block py-2.5 px-3 text-[11px] uppercase tracking-[0.14em] font-medium border-l-2 rounded-r transition-all <?php echo t7_esc($navLink); ?>">
            <?php echo t7_esc($sidecat['name'] ?? ''); ?>
          </a>
          <?php endforeach; ?>
          <?php if (! empty($fullMenuUrl)): ?>
          <div class="mt-4 pt-4 border-t <?php echo $isLight || $isDrinks ? 'border-stone-200' : 'border-white/10'; ?>">
            <a href="<?php echo t7_esc($fullMenuUrl); ?>" class="block py-2.5 px-3 text-[11px] uppercase tracking-[0.14em] font-medium <?php echo t7_esc($navMuted); ?> hover:opacity-80">
              ← All Sections
            </a>
          </div>
          <?php endif; ?>
        </nav>
      </aside>
      <?php endif; ?>

      <!-- Categories + items -->
      <div>
        <?php if (empty($t7VisibleCats)): ?>
        <p class="<?php echo t7_esc($navMuted); ?> py-16 text-center font-light">No items in this section yet.</p>
        <?php endif; ?>

        <?php foreach ($t7VisibleCats as $catIndex => $cat):
          $catSlug  = $cat['slug'] ?? ('cat-'.($cat['id'] ?? 0));
          $catName  = $cat['name'] ?? '';
          $catDesc  = $cat['description'] ?? '';
          $catImg   = t7_category_image($uploadBaseUrl, $cat);
          $catItems = array_values(array_filter($cat['menu_items'] ?? [], static function ($it) {
              return ! empty($it['is_available']);
          }));
          $drinkTheme = $isDrinks ? t7_drink_cat_theme($catSlug) : '';
          $useBurgundyAccent = (! $isEntree && ! $isDrinks && ($catIndex % 5 === 2));
        ?>

        <section id="cat-<?php echo t7_esc($catSlug); ?>" class="t7-cat-section mb-12 sm:mb-16 scroll-mt-28">
          <?php if ($isDrinks): ?>
          <div class="drink-cat <?php echo t7_esc($drinkTheme); ?> relative overflow-visible rounded-2xl mb-4 p-5 sm:p-8 pb-6 sm:pb-10">
            <div class="relative z-10 mb-6 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
              <div>
                <p class="text-[10px] uppercase tracking-[0.22em] font-semibold mb-1 opacity-70">Drinks</p>
                <h2 class="font-serif-luxury text-2xl sm:text-3xl"><?php echo t7_esc($catName); ?></h2>
                <?php if ($catDesc): ?><p class="type-desc text-sm mt-1 opacity-80"><?php echo t7_esc($catDesc); ?></p><?php endif; ?>
              </div>
              <?php if ($catImg): ?>
              <div class="drink-hero-panel <?php echo str_contains($drinkTheme, 'dark') || str_contains($drinkTheme, 'fresh') || str_contains($drinkTheme, 'soft') ? 'drink-hero-panel--dark' : ''; ?> rounded-xl p-2 self-start">
                <img src="<?php echo t7_esc($catImg); ?>" alt="" class="w-24 h-24 sm:w-32 sm:h-32 object-cover rounded-lg" loading="lazy">
              </div>
              <?php endif; ?>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 relative z-10 pb-1">
              <?php foreach ($catItems as $itemIndex => $item):
                $itemName = $item['name'] ?? '';
                $itemDesc = trim((string) ($item['description'] ?? ''));
                $itemPrice = $item['price'] ?? 0;
                $itemImg = t7_item_image($uploadBaseUrl, $item);
                $cardClass = (str_contains($drinkTheme, 'dark') || str_contains($drinkTheme, 'fresh') || str_contains($drinkTheme, 'soft') || str_contains($drinkTheme, 'smoothie'))
                  ? (($itemIndex % 4 === 1) ? 'card-burgundy' : 'card-dark')
                  : (($itemIndex % 4 === 1) ? 'card-ivory' : 'card-white');
              ?>
              <article id="<?php echo t7_esc(t7_item_anchor($item)); ?>" class="menu-item-card scroll-mt-28 <?php echo t7_esc($cardClass); ?> rounded-xl border p-4 sm:p-5 flex gap-3 reveal" data-name="<?php echo t7_esc($itemName); ?>" data-price="<?php echo t7_esc((string) $itemPrice); ?>">
                <?php if ($itemImg): ?>
                <img src="<?php echo t7_esc($itemImg); ?>" alt="<?php echo t7_esc($itemName); ?>" class="w-16 h-16 sm:w-20 sm:h-20 object-cover rounded-lg flex-shrink-0" loading="lazy">
                <?php endif; ?>
                <div class="min-w-0 flex-1 flex flex-col">
                  <div class="flex items-start justify-between gap-2">
                    <h3 class="dish-name font-serif-luxury text-base sm:text-lg leading-snug break-words"><?php echo t7_esc($itemName); ?></h3>
                    <span class="font-serif-luxury text-sm flex-shrink-0 text-champagne-gold tabular-nums"><?php echo t7_price($itemPrice); ?></span>
                  </div>
                  <?php if ($itemDesc !== ''): ?>
                  <p class="type-desc text-xs mt-1 opacity-80"><?php echo t7_esc($itemDesc); ?></p>
                  <?php endif; ?>
                  <?php if (! empty($supportsOrdering)): ?>
                  <button type="button" class="add-to-bag-btn add-dish-btn mt-auto pt-3 self-end text-xs font-semibold"
                          data-item-id="<?php echo t7_esc((string) ($item['id'] ?? '')); ?>"
                          data-item-name="<?php echo t7_esc($itemName); ?>"
                          data-item-price="<?php echo t7_esc((string) $itemPrice); ?>"
                          data-item-image="<?php echo t7_esc($item['image'] ?? ''); ?>">+ Order</button>
                  <?php endif; ?>
                </div>
              </article>
              <?php endforeach; ?>
            </div>
          </div>

          <?php else: ?>
          <?php if (! $isEntree): ?>
          <!-- Category header -->
          <div class="flex items-end justify-between gap-3 mb-5 pb-3 border-b <?php echo $isLight ? 'border-stone-300' : 'border-white/15'; ?>">
            <div>
              <h2 class="font-serif-luxury text-xl sm:text-2xl <?php echo t7_esc($titleColor); ?>"><?php echo t7_esc($catName); ?></h2>
              <?php if ($catDesc): ?><p class="type-desc text-xs mt-1 opacity-70"><?php echo t7_esc($catDesc); ?></p><?php endif; ?>
            </div>
            <span class="text-[10px] uppercase tracking-[0.18em] <?php echo t7_esc($navMuted); ?>"><?php echo count($catItems); ?> items</span>
          </div>
          <?php endif; ?>

          <?php if ($isEntree): ?>
          <?php
            $item = $catItems[0] ?? null;
            $itemName = $item['name'] ?? $catName;
            $itemDesc = (string) ($item['description'] ?? '');
            $itemPrice = $item['price'] ?? 0;
            $itemImg = $item ? (t7_item_image($uploadBaseUrl, $item) ?: t7_category_image($uploadBaseUrl, $cat)) : null;
            $parsed = t7_parse_entree_description($itemDesc);
            $intro = $parsed['intro'];
            $optionBlocks = $parsed['blocks'];
            $alignRight = ($catIndex % 2 === 1);
            $foodSide = $alignRight ? 'entree-food--left' : 'entree-food--right';
            $revealDir = $alignRight ? 'reveal-right' : 'reveal-left';
            $heroTitle = $catName !== '' ? $catName : $itemName;
          ?>
          <?php if ($item): ?>
          <div id="<?php echo t7_esc(t7_item_anchor($item)); ?>" class="menu-item-card mb-14 sm:mb-16 relative scroll-mt-28" data-name="<?php echo t7_esc($itemName); ?>" data-price="<?php echo t7_esc((string) $itemPrice); ?>">
            <div class="entree-panel entree-hero-card rounded-2xl p-4 sm:p-7 md:w-[72%] reveal <?php echo t7_esc($revealDir); ?><?php echo $alignRight ? ' md:ml-auto' : ''; ?>">
              <div class="flex items-start gap-3 sm:gap-5">
                <?php if ($alignRight && $itemImg): ?>
                <img alt="<?php echo t7_esc($heroTitle); ?>" class="entree-food <?php echo t7_esc($foodSide); ?> order-2 md:order-none" src="<?php echo t7_esc($itemImg); ?>" loading="lazy">
                <?php endif; ?>
                <div class="flex-1 min-w-0 <?php echo $alignRight ? 'pl-1 md:pl-28 md:text-right order-1 md:order-none' : 'pr-1 md:pr-28'; ?>">
                  <h3 class="font-serif-luxury text-2xl sm:text-4xl entree-title dish-name break-words"><?php echo t7_esc($heroTitle); ?></h3>
                  <?php if ($intro !== ''): ?>
                  <p class="type-desc text-stone-600 mt-1.5 text-xs sm:text-sm max-w-md<?php echo $alignRight ? ' md:ml-auto' : ''; ?>"><?php echo t7_esc($intro); ?></p>
                  <?php endif; ?>
                  <p class="font-serif-luxury text-lg sm:text-xl entree-title font-bold mt-3"><?php echo t7_price($itemPrice); ?></p>
                </div>
                <?php if (! $alignRight && $itemImg): ?>
                <img alt="<?php echo t7_esc($heroTitle); ?>" class="entree-food <?php echo t7_esc($foodSide); ?>" src="<?php echo t7_esc($itemImg); ?>" loading="lazy">
                <?php endif; ?>
              </div>
            </div>

            <div class="entree-panel rounded-2xl p-3.5 sm:p-6 border border-black/5 mt-4 sm:mt-5 relative reveal reveal-up">
              <?php if ($optionBlocks !== []): ?>
              <div class="entree-options-grid relative">
                <?php foreach ($optionBlocks as $block): ?>
                <div>
                  <p class="label"><?php echo t7_esc($block['label']); ?></p>
                  <?php if ($block['choose'] !== ''): ?><p class="choose"><?php echo t7_esc($block['choose']); ?></p><?php endif; ?>
                  <?php if (! empty($block['items'])): ?>
                  <ul class="type-desc space-y-1">
                    <?php foreach ($block['items'] as $opt): ?>
                    <li><?php echo t7_esc($opt); ?></li>
                    <?php endforeach; ?>
                  </ul>
                  <?php endif; ?>
                </div>
                <?php endforeach; ?>
              </div>
              <?php elseif (trim($itemDesc) !== ''): ?>
              <p class="type-desc text-sm text-stone-600"><?php echo nl2br(t7_esc($itemDesc)); ?></p>
              <?php endif; ?>
              <?php if (! empty($supportsOrdering)): ?>
              <div class="mt-4 pt-3 border-t border-stone-200 flex justify-end">
                <button type="button" class="add-to-bag-btn px-4 py-2 rounded-md bg-burgundy-wine text-white text-xs font-semibold hover:bg-burgundy-soft transition"
                        data-item-id="<?php echo t7_esc((string) ($item['id'] ?? '')); ?>"
                        data-item-name="<?php echo t7_esc($itemName); ?>"
                        data-item-price="<?php echo t7_esc((string) $itemPrice); ?>"
                        data-item-image="<?php echo t7_esc($item['image'] ?? ''); ?>">+ Order</button>
              </div>
              <?php endif; ?>
            </div>
          </div>
          <?php endif; ?>

          <?php elseif ($isNational): ?>
          <div class="space-y-5">
            <?php foreach ($catItems as $item):
              $itemName = $item['name'] ?? '';
              $itemDesc = (string) ($item['description'] ?? '');
              $itemPrice = $item['price'] ?? 0;
              $optionBlocks = t7_parse_option_blocks($itemDesc);
            ?>
            <article id="<?php echo t7_esc(t7_item_anchor($item)); ?>" class="menu-item-card scroll-mt-28 rounded-2xl border border-white/10 bg-black/30 p-5 sm:p-8 reveal reveal-up" data-name="<?php echo t7_esc($itemName); ?>" data-price="<?php echo t7_esc((string) $itemPrice); ?>">
              <div class="flex items-start justify-between gap-3 mb-6">
                <h3 class="font-serif-luxury text-xl sm:text-2xl text-white dish-name break-words min-w-0"><?php echo t7_esc($itemName); ?></h3>
                <span class="font-serif-luxury text-champagne-gold text-lg shrink-0 tabular-nums"><?php echo t7_price($itemPrice); ?></span>
              </div>
              <?php if ($optionBlocks !== []): ?>
              <div class="grid grid-cols-3 gap-2 sm:gap-5">
                <?php foreach ($optionBlocks as $step => $block): ?>
                <div class="rounded-xl border border-white/10 bg-white/5 p-2.5 sm:p-4 min-w-0">
                  <div class="flex items-center gap-1.5 sm:gap-2 mb-2 sm:mb-3">
                    <span class="inline-flex items-center justify-center w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-champagne-gold text-burgundy-deep text-[10px] sm:text-xs font-bold shrink-0"><?php echo (int) $step + 1; ?></span>
                    <p class="text-[9px] sm:text-[11px] uppercase tracking-[0.12em] text-champagne-light font-semibold leading-snug break-words"><?php echo t7_esc($block['label']); ?></p>
                  </div>
                  <?php if ($block['choose'] !== ''): ?><p class="text-[10px] sm:text-xs text-white/50 mb-2"><?php echo t7_esc($block['choose']); ?></p><?php endif; ?>
                  <ul class="space-y-1 sm:space-y-1.5 text-[11px] sm:text-sm text-stone-200">
                    <?php foreach ($block['items'] as $opt): ?>
                    <li class="border-b border-white/5 pb-1 break-words"><?php echo t7_esc($opt); ?></li>
                    <?php endforeach; ?>
                  </ul>
                </div>
                <?php endforeach; ?>
              </div>
              <?php elseif (trim($itemDesc) !== ''): ?>
              <p class="type-desc text-sm text-stone-300 whitespace-pre-line"><?php echo t7_esc($itemDesc); ?></p>
              <?php endif; ?>
              <?php if (! empty($supportsOrdering)): ?>
              <button type="button" class="add-to-bag-btn mt-6 inline-flex items-center gap-1 px-4 py-2 rounded bg-champagne-gold text-burgundy-deep text-xs font-semibold"
                      data-item-id="<?php echo t7_esc((string) ($item['id'] ?? '')); ?>"
                      data-item-name="<?php echo t7_esc($itemName); ?>"
                      data-item-price="<?php echo t7_esc((string) $itemPrice); ?>"
                      data-item-image="<?php echo t7_esc($item['image'] ?? ''); ?>">+ Order</button>
              <?php endif; ?>
            </article>
            <?php endforeach; ?>
          </div>

          <?php else: ?>
          <!-- Default grids: DESIGN_1 horizontal image strip when image exists -->
          <div class="grid grid-cols-1 <?php echo $isLight ? 'md:grid-cols-2' : 'sm:grid-cols-2'; ?> gap-4 sm:gap-6">
            <?php foreach ($catItems as $itemIndex => $item):
              $itemName = $item['name'] ?? '';
              $itemDesc = trim((string) ($item['description'] ?? ''));
              $itemPrice = $item['price'] ?? 0;
              $itemImg = t7_item_image($uploadBaseUrl, $item);
              $cardVariant = $theme['card'];
              if ($useBurgundyAccent && $itemIndex === 0) {
                  $cardVariant = 'card-burgundy';
              } elseif ($isLight && $itemIndex % 7 === 3) {
                  $cardVariant = 'card-ivory';
              }
              $span = ($isLight && $itemIndex % 5 === 0 && $itemImg) ? 'md:col-span-2' : '';
              $revealClass = ['reveal-up', 'reveal-left', 'reveal-right', 'reveal-up'][$itemIndex % 4];
              $delayClass = 'reveal-delay-'.(($itemIndex % 3) + 1);
              $hasMedia = (bool) $itemImg;
            ?>
            <article id="<?php echo t7_esc(t7_item_anchor($item)); ?>" class="menu-item-card scroll-mt-28 <?php echo t7_esc($cardVariant); ?> <?php echo t7_esc($span); ?> rounded-xl border overflow-hidden flex <?php echo $hasMedia ? 'flex-col sm:flex-row' : 'flex-col'; ?> reveal <?php echo t7_esc($revealClass.' '.$delayClass); ?> hover:border-burgundy-wine/60 transition-all" data-name="<?php echo t7_esc($itemName); ?>" data-price="<?php echo t7_esc((string) $itemPrice); ?>">
              <?php if ($hasMedia): ?>
              <div class="dish-media">
                <img src="<?php echo t7_esc($itemImg); ?>" alt="<?php echo t7_esc($itemName); ?>" loading="lazy">
              </div>
              <?php endif; ?>
              <div class="p-5 flex-1 flex flex-col justify-between min-w-0">
                <div class="flex justify-between items-start gap-3">
                  <div class="min-w-0">
            <h3 class="dish-name font-serif-luxury text-lg leading-snug break-words"><?php echo t7_esc($itemName); ?></h3>
                    <?php if ($itemDesc !== ''): ?>
                    <p class="type-desc mt-2 leading-relaxed text-xs sm:text-sm opacity-80"><?php echo t7_esc($itemDesc); ?></p>
                    <?php endif; ?>
                  </div>
                  <span class="font-serif-luxury text-lg font-bold whitespace-nowrap shrink-0 tabular-nums <?php echo t7_esc($theme['price']); ?>"><?php echo t7_price($itemPrice); ?></span>
                </div>
                <?php if (! empty($supportsOrdering)): ?>
                <div class="pt-3 mt-auto border-t <?php echo $isLight ? 'border-stone-100' : 'border-white/10'; ?> flex justify-end">
                  <button type="button" class="add-to-bag-btn add-dish-btn mt-3 inline-flex items-center gap-1 text-xs font-semibold <?php echo $isLight ? 'text-burgundy-wine' : 'text-white'; ?>"
                          data-item-id="<?php echo t7_esc((string) ($item['id'] ?? '')); ?>"
                          data-item-name="<?php echo t7_esc($itemName); ?>"
                          data-item-price="<?php echo t7_esc((string) $itemPrice); ?>"
                          data-item-image="<?php echo t7_esc($item['image'] ?? ''); ?>">+ Order</button>
                </div>
                <?php endif; ?>
              </div>
            </article>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>
          <?php endif; ?>
        </section>
        <?php endforeach; ?>

        <?php if (! empty($fullMenuUrl)): ?>
        <div class="pt-6 border-t <?php echo $isLight || $isDrinks ? 'border-stone-200' : 'border-white/10'; ?> flex flex-wrap gap-4">
          <a href="<?php echo t7_esc($fullMenuUrl); ?>" class="text-xs uppercase tracking-[0.16em] font-medium <?php echo t7_esc($navMuted); ?> hover:opacity-80">← Back to menu directory</a>
          <?php if (! empty($supportsReservations)): ?>
          <a href="<?php echo t7_esc($reservationUrl); ?>" class="text-xs uppercase tracking-[0.16em] font-semibold text-white bg-burgundy-deep hover:bg-burgundy-wine px-4 py-2 rounded border border-champagne-gold/40">Reserve a Table</a>
          <?php endif; ?>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>
