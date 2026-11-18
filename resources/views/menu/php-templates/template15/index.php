<?php
/**
 * Bold Flavours — Court5-inspired warm hospitality menu
 */
if (defined('UPLOAD_URL')) { $uploadBaseUrl = rtrim(UPLOAD_URL, '/'); } else {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
    $uploadBaseUrl = $protocol . ($_SERVER['HTTP_HOST'] ?? 'localhost') . (dirname(dirname(dirname($_SERVER['SCRIPT_NAME'] ?? ''))) ?: '') . '/uploads';
}
$baseUrl = defined('SITE_URL') ? rtrim(SITE_URL, '/') : '';
if (empty($isTemplatePreview)) {
    $reservationUrl = $baseUrl . '/restaurant/' . ($restaurant['slug'] ?? '') . '/reservation';
} else {
    $reservationUrl = $reservationUrl ?? (($fullMenuUrl ?? '') . '#reservation');
}
$currencySymbol = '₦';
$primaryColor = isset($customization['primary_color']) ? $customization['primary_color'] : '#DA2514';
$template15AssetBase = rtrim((string) ($templateAssetBaseUrl ?? ($template15BaseUrl ?? (config('app.url').'/templates/template15'))), '/');

function bf_price($p, $s = '₦') {
    return formatPrice($p, $s);
}

$activeCategories = [];
if (!empty($sections) && is_array($sections)) {
    foreach ($sections as $sec) {
        if (empty($sec['categories']) || !is_array($sec['categories'])) continue;
        foreach ($sec['categories'] as $c) {
            if (!empty($c['menu_items']) && is_array($c['menu_items']) && !empty($c['is_active'])) $activeCategories[] = $c;
        }
    }
}

$bfHeroUrl = '';
if (!empty($singleSectionView) && !empty($sections[0]['image'])) {
    $bfHeroUrl = resmenu_media_url($uploadBaseUrl, 'sections', $sections[0]['image']);
} elseif (!empty($restaurant['hero_image_url'])) {
    $bfHeroUrl = (string) $restaurant['hero_image_url'];
} elseif (!empty($restaurant['hero_image']) && empty($isTemplatePreview)) {
    $bfHeroUrl = resmenu_media_url($uploadBaseUrl, 'heroes', $restaurant['hero_image']);
}

$bfSingle = !empty($singleSectionView);
$bfFullUrl = (string) ($fullMenuUrl ?? '');
$bfSingleSlug = ($bfSingle && !empty($sections[0]['slug'])) ? (string) $sections[0]['slug'] : '';
$bfCategoryHref = function (string $catSlug) use ($bfSingle, $bfFullUrl, $bfSingleSlug) {
    return ($bfSingle && $bfFullUrl !== '' && $bfSingleSlug !== '')
        ? $bfFullUrl.'/'.$bfSingleSlug.'#'.$catSlug
        : '#'.$catSlug;
};
$bfBrandLogo = (!empty($isTemplatePreview)) ? null : resmenu_logo_url($uploadBaseUrl ?? '', $restaurant['logo'] ?? null);
$bfDotUrl = $template15AssetBase.'/orange-dot.webp';
$bfDotMenuUrl = $template15AssetBase.'/orange-dot-menu.webp';
$bfPatternUrl = $template15AssetBase.'/pattern-dark.png';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title><?php echo htmlspecialchars($restaurant['name']); ?><?php if (!empty($singleSectionView) && !empty($sections[0]['name'])): ?> - <?php echo htmlspecialchars($sections[0]['name']); ?><?php endif; ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com"/>
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script>
tailwind.config = {
  theme: {
    extend: {
      colors: {
        cream: '#F4F1EC',
        parchment: '#FCFBF9',
        ink: '#1C1C1C',
        tomato: '#DA2514',
        flame: '#FF800F',
      },
      fontFamily: {
        display: ['Cormorant Garamond', 'Georgia', 'serif'],
        sans: ['DM Sans', 'system-ui', 'sans-serif'],
      },
    },
  },
};
</script>
<style>
:root {
  --bf-tomato: #DA2514;
  --bf-flame: #FF800F;
  --bf-cream: #F4F1EC;
  --bf-ink: #1C1C1C;
}
html { scroll-behavior: smooth; }
body.bf-body {
  background-color: var(--bf-cream);
  color: var(--bf-ink);
  font-family: 'DM Sans', system-ui, sans-serif;
}
.bf-paper {
  background-color: #FCFBF9;
  background-image:
    url('<?php echo htmlspecialchars($bfPatternUrl, ENT_QUOTES, 'UTF-8'); ?>'),
    linear-gradient(180deg, rgba(252,251,249,0.97) 0%, rgba(244,241,236,0.98) 100%);
  background-size: 280px auto, auto;
  background-repeat: repeat, no-repeat;
  background-blend-mode: soft-light, normal;
}
.bf-hero {
  min-height: 72vh;
  min-height: 72dvh;
}
@media (min-width: 768px) {
  .bf-hero { min-height: 78vh; min-height: 78dvh; }
}
.bf-menu-list-line {
  flex-grow: 1;
  margin-left: 12px;
  margin-right: 12px;
  border-bottom: 1px dashed rgba(28, 28, 28, 0.22);
  height: 0.85em;
  min-width: 1.5rem;
}
.bf-dish-image {
  width: 72px;
  height: 72px;
  border-radius: 999px;
  overflow: hidden;
  background: #fff;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
  border: 1px solid rgba(0, 0, 0, 0.04);
  flex-shrink: 0;
}
.bf-dish-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
.bf-dish-fallback {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-family: 'Cormorant Garamond', Georgia, serif;
  font-size: 1.5rem;
  font-weight: 300;
  color: var(--bf-tomato);
  background: #fff;
}
.bf-pill {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border-radius: 999px;
  background: var(--bf-flame);
  color: #fff;
  font-size: 0.7rem;
  font-weight: 600;
  letter-spacing: 0.14em;
  text-transform: uppercase;
  padding: 0.65rem 1.5rem;
  transition: background 0.2s ease, transform 0.2s ease;
}
.bf-pill:hover { background: var(--bf-tomato); transform: translateY(-1px); }
.bf-order-btn {
  display: inline-flex;
  align-items: center;
  gap: 0.35rem;
  margin-top: 0.65rem;
  border-radius: 999px;
  border: 1px solid rgba(218, 37, 20, 0.35);
  color: var(--bf-tomato);
  font-size: 0.65rem;
  font-weight: 600;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  padding: 0.4rem 0.9rem;
  background: transparent;
  transition: background 0.2s ease, color 0.2s ease, border-color 0.2s ease;
}
.bf-order-btn:hover {
  background: var(--bf-tomato);
  border-color: var(--bf-tomato);
  color: #fff;
}
.bf-tab {
  position: relative;
  font-size: 0.75rem;
  font-weight: 600;
  letter-spacing: 0.16em;
  text-transform: uppercase;
  color: rgba(28, 28, 28, 0.55);
  padding-bottom: 0.4rem;
  transition: color 0.2s ease;
}
.bf-tab:hover, .bf-tab.is-active { color: var(--bf-tomato); }
.bf-tab.is-active::after {
  content: '';
  position: absolute;
  left: 0;
  right: 0;
  bottom: 0;
  height: 2px;
  background: var(--bf-tomato);
}
.bf-dot {
  width: 8px;
  height: 8px;
  background: url('<?php echo htmlspecialchars($bfDotUrl, ENT_QUOTES, 'UTF-8'); ?>') center / contain no-repeat;
  flex-shrink: 0;
  opacity: 0.9;
}
.bf-drawer {
  transform: translateX(100%);
  transition: transform 0.3s ease;
}
.bf-drawer.is-open { transform: translateX(0); }
.bf-overlay {
  opacity: 0;
  visibility: hidden;
  pointer-events: none;
  transition: opacity 0.2s ease, visibility 0.2s ease;
}
.bf-overlay.is-open {
  opacity: 1;
  visibility: visible;
  pointer-events: auto;
}
.hide-scrollbar::-webkit-scrollbar { display: none; }
.hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
@media (prefers-reduced-motion: reduce) {
  html { scroll-behavior: auto; }
  .bf-pill:hover, .bf-drawer, .bf-overlay { transition: none; }
}
</style>
</head>
<body class="bf-body antialiased selection:bg-flame/30 selection:text-ink">

<!-- Sticky top bar -->
<header class="sticky top-0 z-40 border-b border-black/5 bg-cream/90 backdrop-blur-md">
  <div class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-4 py-3 md:px-6">
    <a href="<?php echo htmlspecialchars($bfFullUrl !== '' ? $bfFullUrl : '#'); ?>" class="flex min-w-0 items-center gap-3">
      <?php if ($bfBrandLogo): ?>
        <img src="<?php echo htmlspecialchars($bfBrandLogo); ?>" alt="<?php echo htmlspecialchars($restaurant['name']); ?>" class="h-10 w-auto max-w-[140px] object-contain md:h-12"/>
      <?php else: ?>
        <span class="font-display text-2xl font-light tracking-tight text-tomato md:text-3xl"><?php echo htmlspecialchars($restaurant['name']); ?></span>
      <?php endif; ?>
    </a>
    <div class="flex items-center gap-2 sm:gap-3">
      <?php if (!empty($supportsReservations)): ?>
        <a href="<?php echo htmlspecialchars($reservationUrl); ?>" class="bf-pill hidden sm:inline-flex">Book a table</a>
      <?php endif; ?>
      <button type="button" id="bf-menu-toggle" class="flex h-11 w-11 items-center justify-center rounded-full border border-ink/15 text-ink transition-colors hover:border-tomato hover:text-tomato" aria-label="Open menu" aria-expanded="false" aria-controls="bf-drawer">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
      </button>
    </div>
  </div>
</header>

<!-- Mobile / nav drawer -->
<div id="bf-overlay" class="bf-overlay fixed inset-0 z-50 bg-ink/50" aria-hidden="true"></div>
<aside id="bf-drawer" class="bf-drawer fixed right-0 top-0 z-[60] flex h-full w-[min(100vw-3rem,20rem)] flex-col bg-cream shadow-2xl" aria-label="Menu" aria-hidden="true">
  <div class="flex items-center justify-between border-b border-black/10 px-4 py-4">
    <span class="text-xs font-semibold uppercase tracking-[0.25em] text-ink/70">Navigate</span>
    <button type="button" id="bf-drawer-close" class="flex h-10 w-10 items-center justify-center rounded-full border border-ink/15 text-ink" aria-label="Close menu">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M18 6L6 18M6 6l12 12"/></svg>
    </button>
  </div>
  <nav class="flex flex-1 flex-col gap-1 overflow-y-auto px-3 py-4 pb-8 text-sm">
    <?php if ($bfSingle && $bfFullUrl !== ''): ?>
      <a href="<?php echo htmlspecialchars($bfFullUrl); ?>" class="bf-drawer-link rounded-lg px-3 py-3 text-center font-semibold uppercase tracking-widest text-tomato ring-1 ring-tomato/25 hover:bg-tomato/5">Full menu</a>
    <?php endif; ?>
    <a href="#menu" class="bf-drawer-link rounded-lg px-3 py-3 text-center font-semibold uppercase tracking-widest text-ink/80 ring-1 ring-ink/10 hover:text-tomato">View menu</a>
    <?php if (!empty($sectionsForNav) && is_array($sectionsForNav) && $bfFullUrl !== ''): ?>
      <?php foreach ($sectionsForNav as $navSection): ?>
        <a href="<?php echo htmlspecialchars($bfFullUrl); ?>#section-<?php echo htmlspecialchars($navSection['slug'] ?? ''); ?>" class="bf-drawer-link rounded-lg px-3 py-2.5 text-center text-xs font-semibold uppercase tracking-widest text-ink/60 ring-1 ring-ink/10 hover:text-tomato"><?php echo htmlspecialchars($navSection['name'] ?? ''); ?></a>
      <?php endforeach; ?>
    <?php endif; ?>
    <div class="my-2 flex items-center justify-center gap-2" aria-hidden="true">
      <span class="bf-dot"></span>
      <span class="h-px w-10 bg-ink/15"></span>
      <span class="bf-dot"></span>
    </div>
    <?php foreach ($activeCategories as $i => $cat):
      $s = isset($cat['slug']) ? $cat['slug'] : ('cat-'.$i);
    ?>
      <a href="<?php echo htmlspecialchars($bfCategoryHref($s)); ?>" class="bf-drawer-link rounded-lg px-3 py-2.5 text-center text-xs uppercase tracking-wide text-ink/55 ring-1 ring-ink/10 hover:text-tomato"><?php echo htmlspecialchars($cat['name']); ?></a>
    <?php endforeach; ?>
    <?php if (!empty($supportsReservations)): ?>
      <a href="<?php echo htmlspecialchars($reservationUrl); ?>" class="bf-drawer-link mt-3 bf-pill text-center">Book a table</a>
    <?php endif; ?>
  </nav>
</aside>

<!-- Hero -->
<section class="bf-hero relative w-full overflow-hidden" aria-label="Restaurant cover" data-template-preview-hero>
  <?php if ($bfHeroUrl !== ''): ?>
    <div class="absolute inset-0 bg-cover bg-center bg-no-repeat" style="background-image:url('<?php echo htmlspecialchars($bfHeroUrl, ENT_QUOTES, 'UTF-8'); ?>')"></div>
  <?php else: ?>
    <div class="absolute inset-0 bg-gradient-to-br from-[#2a1a14] via-[#1c1c1c] to-[#3a2018]"></div>
    <div class="absolute inset-0 opacity-30" style="background-image:url('<?php echo htmlspecialchars($bfPatternUrl, ENT_QUOTES, 'UTF-8'); ?>'); background-size: 220px auto;"></div>
  <?php endif; ?>
  <div class="absolute inset-0 bg-gradient-to-t from-black/75 via-black/35 to-black/20"></div>
  <div class="relative mx-auto flex min-h-[inherit] max-w-4xl flex-col items-center justify-center px-6 pb-16 pt-20 text-center sm:pb-20 sm:pt-24">
    <?php if ($bfBrandLogo): ?>
      <div class="mb-5"><img src="<?php echo htmlspecialchars($bfBrandLogo); ?>" alt="<?php echo htmlspecialchars($restaurant['name']); ?>" class="mx-auto h-16 w-auto max-w-[200px] object-contain drop-shadow md:h-20"/></div>
    <?php else: ?>
      <h1 class="font-display text-5xl font-light leading-none tracking-tight text-white sm:text-6xl md:text-7xl"><?php echo htmlspecialchars($restaurant['name']); ?></h1>
    <?php endif; ?>
    <?php if (!empty($restaurant['description'])): ?>
      <p class="mt-5 max-w-xl text-sm font-light leading-relaxed text-white/85 sm:text-base"><?php echo htmlspecialchars($restaurant['description']); ?></p>
    <?php endif; ?>
    <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
      <a href="#menu" class="bf-pill">Explore menu</a>
      <?php if (!empty($supportsReservations)): ?>
        <a href="<?php echo htmlspecialchars($reservationUrl); ?>" class="inline-flex items-center justify-center rounded-full border border-white/50 px-6 py-2.5 text-[0.7rem] font-semibold uppercase tracking-[0.14em] text-white transition-colors hover:border-white hover:bg-white/10">Reserve</a>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- Menu -->
<main id="menu" class="bf-paper relative">
  <div class="mx-auto max-w-6xl px-4 py-12 md:px-6 md:py-16">
    <div class="mb-10 text-center md:mb-14">
      <div class="mb-4 flex items-center justify-center gap-3" aria-hidden="true">
        <span class="bf-dot"></span>
        <p class="text-[0.7rem] font-semibold uppercase tracking-[0.28em] text-flame">Explore our delicious</p>
        <span class="bf-dot"></span>
      </div>
      <h2 class="font-display text-4xl font-light text-tomato md:text-5xl">Menu</h2>
      <?php if (!empty($bfDotMenuUrl)): ?>
        <img src="<?php echo htmlspecialchars($bfDotMenuUrl); ?>" alt="" class="mx-auto mt-4 h-3 w-auto opacity-80" aria-hidden="true"/>
      <?php endif; ?>
    </div>

    <?php if (!empty($activeCategories)): ?>
    <nav class="mb-12 flex flex-wrap items-center justify-center gap-x-3 gap-y-3 hide-scrollbar overflow-x-auto pb-1" aria-label="Categories">
      <?php foreach ($activeCategories as $i => $cat):
        $s = isset($cat['slug']) ? $cat['slug'] : ('cat-'.$i);
      ?>
        <?php if ($i > 0): ?><span class="bf-dot hidden sm:inline-block" aria-hidden="true"></span><?php endif; ?>
        <a href="<?php echo htmlspecialchars($bfCategoryHref($s)); ?>" class="bf-tab whitespace-nowrap"><?php echo htmlspecialchars($cat['name']); ?></a>
      <?php endforeach; ?>
    </nav>
    <?php endif; ?>

    <?php foreach ($sections as $section):
      if (empty($section['categories']) || !is_array($section['categories'])) continue;
    ?>
    <div id="section-<?php echo htmlspecialchars($section['slug']); ?>" class="mb-16">
      <h2 class="mb-10 text-center font-display text-3xl font-light text-tomato md:text-4xl">
        <?php if ($bfFullUrl !== '' && empty($singleSectionView)): ?>
          <a href="<?php echo htmlspecialchars($bfFullUrl.'/'.$section['slug']); ?>" class="hover:underline"><?php echo htmlspecialchars($section['name']); ?></a>
        <?php else: ?>
          <?php echo htmlspecialchars($section['name']); ?>
        <?php endif; ?>
      </h2>

      <?php foreach ($section['categories'] as $catIndex => $category):
        $slug = isset($category['slug']) ? $category['slug'] : ('cat-'.$catIndex);
        $items = isset($category['menu_items']) ? $category['menu_items'] : [];
        if (empty($items)) continue;
      ?>
      <section class="mb-14" id="<?php echo htmlspecialchars($slug); ?>">
        <h3 class="rm-category-title mb-8 text-center font-display text-2xl font-light uppercase tracking-[0.08em] text-tomato md:text-3xl"><?php echo htmlspecialchars($category['name']); ?></h3>
        <div class="grid grid-cols-1 gap-x-10 gap-y-8 md:grid-cols-2">
          <?php foreach ($items as $item):
            $itemName = (string) ($item['name'] ?? '');
            $initial = $itemName !== '' ? mb_strtoupper(mb_substr($itemName, 0, 1)) : '•';
            $itemImg = !empty($item['image']) ? resmenu_media_url($uploadBaseUrl, 'menu-items', $item['image']) : '';
            $available = !isset($item['is_available']) || !empty($item['is_available']);
          ?>
          <article class="flex gap-4 <?php echo $available ? '' : 'opacity-55'; ?>">
            <div class="bf-dish-image">
              <?php if ($itemImg !== ''): ?>
                <img src="<?php echo htmlspecialchars($itemImg); ?>" alt="<?php echo htmlspecialchars($itemName); ?>" loading="lazy"/>
              <?php else: ?>
                <div class="bf-dish-fallback" aria-hidden="true"><?php echo htmlspecialchars($initial); ?></div>
              <?php endif; ?>
            </div>
            <div class="min-w-0 flex-1 pt-0.5">
              <div class="flex items-baseline">
                <h4 class="rm-item-title shrink font-sans text-sm font-medium uppercase tracking-[0.12em] text-tomato sm:text-[0.95rem]"><?php echo htmlspecialchars($itemName); ?></h4>
                <span class="bf-menu-list-line" aria-hidden="true"></span>
                <span class="rm-price shrink-0 font-sans text-sm font-semibold tabular-nums text-ink"><?php echo bf_price($item['price']); ?></span>
              </div>
              <?php if (!empty($item['description'])): ?>
                <p class="rm-description mt-1.5 text-sm leading-relaxed text-ink/55"><?php echo htmlspecialchars($item['description']); ?></p>
              <?php endif; ?>
              <?php if (!$available): ?>
                <span class="mt-2 inline-block text-xs font-semibold uppercase tracking-wider text-tomato/80">Unavailable</span>
              <?php elseif (!empty($supportsOrdering)): ?>
                <button type="button" class="add-to-bag-btn bf-order-btn"
                  data-item-id="<?php echo (int) $item['id']; ?>"
                  data-item-name="<?php echo htmlspecialchars($itemName); ?>"
                  data-item-price="<?php echo htmlspecialchars($item['price']); ?>"
                  data-item-image="<?php echo !empty($item['image']) ? htmlspecialchars($item['image']) : ''; ?>">
                  Order
                  <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
                </button>
              <?php endif; ?>
            </div>
          </article>
          <?php endforeach; ?>
        </div>
      </section>
      <?php endforeach; ?>
    </div>
    <?php endforeach; ?>
  </div>
</main>

<footer class="border-t border-black/10 bg-cream px-4 py-12 text-center md:px-6">
  <?php if ($bfBrandLogo): ?>
    <img src="<?php echo htmlspecialchars($bfBrandLogo); ?>" alt="" class="mx-auto mb-4 h-10 w-auto object-contain opacity-90" aria-hidden="true"/>
  <?php else: ?>
    <p class="mb-3 font-display text-2xl font-light text-tomato"><?php echo htmlspecialchars($restaurant['name'] ?? ''); ?></p>
  <?php endif; ?>
  <?php if (!empty($restaurant['footer_content'])): ?>
    <p class="mx-auto mb-3 max-w-xl text-sm leading-relaxed text-ink/60"><?php echo nl2br(htmlspecialchars($restaurant['footer_content'])); ?></p>
  <?php endif; ?>
  <?php if (!empty($restaurant['address'])): ?>
    <p class="text-sm text-ink/50"><?php echo htmlspecialchars($restaurant['address']); ?></p>
  <?php endif; ?>
  <?php if (!empty($restaurant['phone']) || !empty($restaurant['email'])): ?>
    <div class="mt-3 flex flex-wrap items-center justify-center gap-x-4 gap-y-1 text-sm text-ink/55">
      <?php if (!empty($restaurant['phone'])): ?>
        <a href="tel:<?php echo htmlspecialchars(preg_replace('/\s+/', '', $restaurant['phone'])); ?>" class="text-tomato hover:underline"><?php echo htmlspecialchars($restaurant['phone']); ?></a>
      <?php endif; ?>
      <?php if (!empty($restaurant['email'])): ?>
        <a href="mailto:<?php echo htmlspecialchars($restaurant['email']); ?>" class="text-tomato hover:underline"><?php echo htmlspecialchars($restaurant['email']); ?></a>
      <?php endif; ?>
    </div>
  <?php endif; ?>
  <?php echo resmenu_social_links($restaurant, 'mt-6 text-tomato'); ?>
</footer>

<?php if (!empty($supportsOrdering)): ?>
<link rel="stylesheet" href="<?php echo rtrim(defined('SITE_URL') ? SITE_URL : $baseUrl, '/'); ?>/legacy/assets/css/cart-modal.css">
<div id="resmenu-cart-widget" class="fixed bottom-6 left-6 z-50 hidden"></div>
<script src="<?php echo rtrim(defined('SITE_URL') ? SITE_URL : $baseUrl, '/'); ?>/assets/js/cart.js"></script>
<script src="<?php echo rtrim(defined('SITE_URL') ? SITE_URL : $baseUrl, '/'); ?>/assets/js/cart-widget.js"></script>
<script src="<?php echo rtrim(defined('SITE_URL') ? SITE_URL : $baseUrl, '/'); ?>/assets/js/cart-modal.js"></script>
<script>
(function(){
  var baseUrl=<?php echo json_encode($baseUrl); ?>;
  var slug=<?php echo json_encode($restaurant['slug']??''); ?>;
  var config={
    restaurantSlug:slug,
    currencySymbol:<?php echo json_encode($currencySymbol); ?>,
    uploadBaseUrl:<?php echo json_encode($uploadBaseUrl??''); ?>,
    checkoutUrl:baseUrl+'/restaurant/'+slug+'/checkout',
    primaryColor:<?php echo json_encode($primaryColor); ?>,
    deliveryFee:0,
    taxRate:0
  };
  window.RESMENU_CART_CONFIG=config;
  if(window.RESMENU_CART_MODAL)window.RESMENU_CART_MODAL.init(config);
  if(window.RESMENU_CART_WIDGET)window.RESMENU_CART_WIDGET.init(config);
  document.querySelectorAll('.add-to-bag-btn').forEach(function(btn){
    btn.addEventListener('click',function(){
      var id=this.getAttribute('data-item-id'),
          name=this.getAttribute('data-item-name'),
          price=this.getAttribute('data-item-price'),
          image=this.getAttribute('data-item-image')||'';
      if(window.RESMENU_CART)window.RESMENU_CART.addItem(slug,{id:id,name:name,price:price,image:image},1);
    });
  });
})();
</script>
<?php endif; ?>

<a id="scrollToTop" href="#" aria-label="Scroll to top" style="position:fixed;bottom:24px;right:24px;z-index:30;width:48px;height:48px;display:flex;align-items:center;justify-content:center;border-radius:50%;background:var(--bf-flame);color:#fff;opacity:0;visibility:hidden;transform:translateY(10px);transition:opacity 0.3s,visibility 0.3s,transform 0.3s;box-shadow:0 4px 12px rgba(0,0,0,0.2);">
  <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 15l-6-6-6 6"/></svg>
</a>
<script>
(function(){
  var btn=document.getElementById('scrollToTop');
  if(btn){
    window.addEventListener('scroll',function(){
      var st=window.pageYOffset||document.documentElement.scrollTop;
      var dh=document.documentElement.scrollHeight-window.innerHeight;
      if(dh>0&&st>=dh*0.3){btn.style.opacity='1';btn.style.visibility='visible';btn.style.transform='translateY(0)';}
      else{btn.style.opacity='0';btn.style.visibility='hidden';btn.style.transform='translateY(10px)';}
    });
    btn.addEventListener('click',function(e){e.preventDefault();window.scrollTo({top:0,behavior:'smooth'});});
  }

  var drawer=document.getElementById('bf-drawer');
  var overlay=document.getElementById('bf-overlay');
  var toggle=document.getElementById('bf-menu-toggle');
  var closeBtn=document.getElementById('bf-drawer-close');
  function openDrawer(){
    if(!drawer||!overlay||!toggle)return;
    drawer.classList.add('is-open');
    overlay.classList.add('is-open');
    drawer.setAttribute('aria-hidden','false');
    overlay.setAttribute('aria-hidden','false');
    toggle.setAttribute('aria-expanded','true');
    document.body.style.overflow='hidden';
  }
  function closeDrawer(){
    if(!drawer||!overlay||!toggle)return;
    drawer.classList.remove('is-open');
    overlay.classList.remove('is-open');
    drawer.setAttribute('aria-hidden','true');
    overlay.setAttribute('aria-hidden','true');
    toggle.setAttribute('aria-expanded','false');
    document.body.style.overflow='';
  }
  if(toggle)toggle.addEventListener('click',openDrawer);
  if(closeBtn)closeBtn.addEventListener('click',closeDrawer);
  if(overlay)overlay.addEventListener('click',closeDrawer);
  document.querySelectorAll('.bf-drawer-link').forEach(function(link){
    link.addEventListener('click',closeDrawer);
  });
})();
</script>
</body>
</html>
