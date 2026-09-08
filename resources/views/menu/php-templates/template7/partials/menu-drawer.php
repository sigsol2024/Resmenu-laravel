<?php
$isHomeDrawer = (($menuViewLevel ?? '') === 'home');
$drawerRestName = trim((string) ($restaurant['name'] ?? 'Menu'));

if ($isHomeDrawer) {
    $drawerSections = ! empty($sectionsForNav) ? $sectionsForNav : ($sections ?? []);
    if (empty($drawerSections)) {
        return;
    }
} else {
    $drawerSectionName = trim((string) ($activeSection['name'] ?? 'Section'));
    $drawerCategories = [];
    if (! empty($activeSection['categories']) && is_array($activeSection['categories'])) {
        $drawerCategories = array_values(array_filter($activeSection['categories'], static function ($cat) {
            return ! empty($cat['is_active']) && ! empty($cat['menu_items']);
        }));
    }
    if ($drawerCategories === []) {
        return;
    }
}
?>
<div id="t7-menu-backdrop" class="hidden fixed inset-0 z-[90] bg-black/60 backdrop-blur-sm" aria-hidden="true"></div>

<aside id="t7-menu-drawer"
       class="hidden fixed right-0 top-0 h-full w-full sm:w-96 md:w-[420px] bg-onyx-card shadow-2xl z-[100] border-l border-onyx-border overflow-y-auto t7-noscroll"
       aria-labelledby="t7-menu-drawer-title"
       role="dialog"
       aria-modal="true">
  <div class="px-5 py-4 flex items-center justify-between sticky top-0 bg-onyx-card/95 backdrop-blur-md z-20 border-b border-onyx-border">
    <div class="min-w-0">
      <h2 id="t7-menu-drawer-title" class="font-serif-luxury text-lg text-champagne-light truncate">
        <?php echo t7_esc($isHomeDrawer ? $drawerRestName : ($drawerSectionName ?? 'Menu')); ?>
      </h2>
      <p class="text-[10px] uppercase tracking-[0.22em] text-white/50">
        <?php echo $isHomeDrawer ? 'Sections' : 'On this page'; ?>
      </p>
    </div>
    <button type="button" id="t7-menu-close" class="flex items-center justify-center w-11 h-11 rounded border border-onyx-border text-champagne-gold hover:text-white hover:border-champagne-gold transition-colors" aria-label="Close menu">
      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M18 6L6 18M6 6l12 12"/></svg>
    </button>
  </div>

  <div class="px-4 py-4 pb-10 space-y-1">
    <?php if ($isHomeDrawer): ?>
      <a href="#t7-menu-index" class="t7-menu-item-jump block py-3 px-3 rounded-lg text-[11px] uppercase tracking-[0.16em] text-champagne-gold/80 hover:text-champagne-gold hover:bg-white/5 transition-colors">
        Browse directory
      </a>
      <?php foreach ($drawerSections as $section):
        $secSlug = strtolower(trim((string) ($section['slug'] ?? '')));
        $secUrl = t7_section_url($fullMenuUrl ?? '', $secSlug);
      ?>
      <a href="<?php echo t7_esc($secUrl); ?>"
         class="block py-3 px-3 rounded-lg text-sm font-medium text-white/85 hover:text-champagne-gold hover:bg-white/5 transition-colors">
        <?php echo t7_esc($section['name'] ?? ''); ?>
      </a>
      <?php endforeach; ?>

    <?php else: ?>
      <?php if (! empty($fullMenuUrl)): ?>
      <a href="<?php echo t7_esc($fullMenuUrl); ?>" class="block py-3 px-3 rounded-lg text-[11px] uppercase tracking-[0.16em] text-champagne-gold/80 hover:text-champagne-gold hover:bg-white/5 transition-colors">
        ← Menu directory
      </a>
      <?php endif; ?>

      <?php foreach ($drawerCategories as $dcat):
        $dcatSlug = $dcat['slug'] ?? ('cat-'.($dcat['id'] ?? 0));
        $dcatItems = array_values(array_filter($dcat['menu_items'] ?? [], static function ($it) {
            return ! empty($it['is_available']);
        }));
      ?>
      <a href="#cat-<?php echo t7_esc($dcatSlug); ?>"
         class="t7-menu-item-jump block py-3 px-3 rounded-lg text-sm font-medium text-champagne-light hover:bg-burgundy-deep/50 transition-colors">
        <?php echo t7_esc($dcat['name'] ?? ''); ?>
      </a>
      <?php if (count($dcatItems) > 0): ?>
      <div class="ml-2 mb-3 space-y-0.5 border-l border-white/10 pl-3">
        <?php foreach ($dcatItems as $ditem):
          $itemAnchor = t7_item_anchor($ditem);
          $itemLabel = (string) ($ditem['name'] ?? '');
          if ($itemLabel === '') {
              continue;
          }
        ?>
        <a href="#<?php echo t7_esc($itemAnchor); ?>"
           class="t7-menu-item-jump block py-2 px-2 text-[12px] text-white/55 hover:text-champagne-light transition-colors min-h-[40px] flex items-center leading-snug">
          <?php echo t7_esc($itemLabel); ?>
        </a>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</aside>
