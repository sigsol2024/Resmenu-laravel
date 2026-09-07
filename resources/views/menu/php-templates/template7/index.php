<?php
/**
 * Template 7 — Ember Noir
 * Dark editorial restaurant menu with two views: home (landing) and section.
 */
require_once __DIR__ . '/helpers.php';

// ── View level ────────────────────────────────────────────────────────────────
$menuViewLevel = $menuViewLevel ?? 'home';
$GLOBALS['t7_is_template_preview'] = ! empty($isTemplatePreview);

// ── Variable defaults ─────────────────────────────────────────────────────────
$uploadBaseUrl        = $uploadBaseUrl        ?? '';
$templateAssetBaseUrl = $templateAssetBaseUrl ?? '';
$fullMenuUrl          = $fullMenuUrl          ?? '';
$sections             = $sections             ?? [];
$sectionsForNav       = $sectionsForNav       ?? [];
$restaurant           = $restaurant           ?? [];
$supportsOrdering     = $supportsOrdering     ?? false;
$supportsReservations = $supportsReservations ?? false;
$reservationUrl       = $reservationUrl       ?? '';
$activeSection        = $activeSection        ?? null;
$customization        = $customization        ?? [];
$currencySymbol       = '₦';
$primaryColor         = $customization['primary_color'] ?? '#500C19';

$t7BaseUrl = defined('SITE_URL') ? rtrim(SITE_URL, '/') : '';

// ── Page title ────────────────────────────────────────────────────────────────
$t7PageTitle = $restaurant['name'] ?? 'Menu';
if ($menuViewLevel === 'section' && ! empty($activeSection['name'])) {
    $t7PageTitle .= ' · ' . ($activeSection['name']);
}

// ── Asset URLs ────────────────────────────────────────────────────────────────
$t7PatternBg      = t7_pattern_url($templateAssetBaseUrl, 'bg_black.png');
$t7PatternBinding = t7_pattern_url($templateAssetBaseUrl, 'binding_dark.png');
$t7LogoUrl        = t7_logo_url($uploadBaseUrl, $restaurant);

// ── Derived ───────────────────────────────────────────────────────────────────
$t7RestaurantName = $restaurant['name'] ?? '';
$t7RestaurantDesc = $restaurant['description'] ?? '';
$t7BackUrl = ($menuViewLevel === 'section') ? $fullMenuUrl : null;
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo t7_esc($t7PageTitle); ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <style>
    /* ── Typography ──────────────────────────────────────────────────────────── */
    *, *::before, *::after { box-sizing: border-box; }
    html { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; }
    .t7-serif { font-family: 'Playfair Display', Georgia, serif; }

    /* ── Brand palette ───────────────────────────────────────────────────────── */
    :root {
      --t7-burgundy:  #500C19;
      --t7-burgundy-7: #6A1324;
      --t7-burgundy-9: #38060F;
      --t7-onyx:      #121214;
      --t7-onyx-s:    #17171A;
      --t7-onyx-c:    #1D1C21;
      --t7-onyx-m:    #242228;
      --t7-ivory:     #F6F1E8;
      --t7-ivory-w:   #EEE6DA;
      --t7-champagne: #CBB288;
      --t7-champ-h:   rgba(203,178,136,0.28);
      --t7-champ-g:   rgba(203,178,136,0.45);
      --t7-champ-l:   #EEDDC0;
    }

    ::selection { background: var(--t7-burgundy); color: var(--t7-ivory); }

    /* ── Pattern overlay helper ──────────────────────────────────────────────── */
    .t7-pattern-shell {
      position: relative;
      isolation: isolate;
    }
    .t7-pattern-shell::before {
      content: '';
      position: absolute;
      inset: 0;
      z-index: 0;
      pointer-events: none;
      background-image: url('<?php echo t7_esc($t7PatternBg); ?>');
      background-repeat: repeat;
      background-size: 280px 280px;
      opacity: 0.26;
    }
    .t7-pattern-shell::after {
      content: '';
      position: absolute;
      inset: 0;
      z-index: 0;
      pointer-events: none;
      background-image: url('<?php echo t7_esc($t7PatternBinding); ?>');
      background-repeat: repeat;
      background-size: 168px 168px;
      opacity: 0.16;
      mix-blend-mode: soft-light;
    }
    .t7-pattern-shell > * { position: relative; z-index: 1; }

    /* ── Scrollbar ───────────────────────────────────────────────────────────── */
    .t7-noscroll::-webkit-scrollbar { display: none; }
    .t7-noscroll { -ms-overflow-style: none; scrollbar-width: none; }

    /* ── Scroll reveals ──────────────────────────────────────────────────────── */
    .t7-reveal {
      opacity: 0;
      transform: translateY(24px);
      transition: opacity 0.7s cubic-bezier(0.22,1,0.36,1), transform 0.7s cubic-bezier(0.22,1,0.36,1);
    }
    .t7-reveal.is-in { opacity: 1; transform: none; }
    .t7-reveal-d1 { transition-delay: 0.06s; }
    .t7-reveal-d2 { transition-delay: 0.12s; }
    .t7-reveal-d3 { transition-delay: 0.18s; }
    @media (prefers-reduced-motion: reduce) {
      .t7-reveal { opacity: 1; transform: none; transition: none; }
    }

    /* ── Section-page layout ─────────────────────────────────────────────────── */
    .t7-layout {
      display: grid;
      grid-template-columns: 1fr;
    }
    @media (min-width: 1024px) {
      .t7-layout { grid-template-columns: 260px 1fr; }
    }

    /* ── Grill belt (section slug: grill) ────────────────────────────────────── */
    .t7-grill-belt {
      position: absolute;
      left: 0;
      top: 0;
      bottom: 0;
      z-index: 2;
      width: 2.5rem;
      overflow: hidden;
      pointer-events: none;
      mask-image: linear-gradient(to bottom, transparent 0%, #000 8%, #000 92%, transparent 100%);
      -webkit-mask-image: linear-gradient(to bottom, transparent 0%, #000 8%, #000 92%, transparent 100%);
    }
    .t7-grill-belt-track {
      display: flex;
      flex-direction: column;
      animation: t7GrillScroll 10s linear infinite;
      will-change: transform;
    }
    .t7-grill-belt-word {
      writing-mode: vertical-rl;
      text-orientation: mixed;
      transform: rotate(180deg);
      font-family: 'Playfair Display', serif;
      font-size: 0.6rem;
      letter-spacing: 0.35em;
      text-transform: uppercase;
      color: rgba(203,178,136,0.45);
      padding: 0.75rem 0;
      white-space: nowrap;
    }
    @keyframes t7GrillScroll {
      0%   { transform: translateY(0); }
      100% { transform: translateY(-50%); }
    }

    /* ── Entree cream panel ──────────────────────────────────────────────────── */
    .t7-entree-panel {
      background: #FFFFF6;
      color: #1c1917;
      border: 1px solid rgba(80,12,25,0.10);
      border-radius: 6px;
    }

    /* ── Sidebar active state ────────────────────────────────────────────────── */
    .t7-sidebar-link.active {
      background: rgba(80,12,25,0.55);
      color: var(--t7-champagne);
      border-left-color: var(--t7-champagne);
    }

    /* ── Back-to-top ─────────────────────────────────────────────────────────── */
    #t7-back-top {
      position: fixed;
      right: 1.25rem;
      bottom: 5.5rem;
      z-index: 45;
      width: 2.75rem;
      height: 2.75rem;
      border-radius: 6px;
      background: var(--t7-burgundy-7);
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      opacity: 0;
      pointer-events: none;
      transform: translateY(8px);
      transition: opacity 0.3s, transform 0.3s;
      box-shadow: 0 8px 24px rgba(0,0,0,0.3);
    }
    #t7-back-top.visible { opacity: 1; pointer-events: auto; transform: none; }
  </style>
</head>

<body class="bg-[#0e0e10] text-[#F6F1E8] antialiased min-h-screen flex flex-col">

<!-- ═══════════════════════════════ HEADER ═══════════════════════════════════ -->
<header class="sticky top-0 z-50 bg-[#121214]/95 backdrop-blur-md border-b border-[rgba(203,178,136,0.28)] transition-all" id="t7-header">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 sm:h-[4.5rem] flex items-center justify-between gap-4">

    <!-- Brand -->
    <div class="flex items-center gap-3 min-w-0">
      <?php if ($t7BackUrl): ?>
      <a href="<?php echo t7_esc($t7BackUrl); ?>" class="flex-shrink-0 flex items-center justify-center w-9 h-9 rounded border border-[rgba(203,178,136,0.28)] hover:border-[#CBB288] text-[#CBB288] hover:text-white transition-colors mr-1" aria-label="Back to menu">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
      </a>
      <?php endif; ?>

      <?php if ($t7LogoUrl): ?>
      <a href="<?php echo t7_esc($fullMenuUrl ?: '#'); ?>" class="flex-shrink-0 p-1 bg-[#38060F]/80 border border-[rgba(203,178,136,0.28)] rounded hover:border-[#CBB288] transition-colors">
        <img src="<?php echo t7_esc($t7LogoUrl); ?>" alt="<?php echo t7_esc($t7RestaurantName); ?>" class="h-7 w-auto object-contain">
      </a>
      <?php endif; ?>

      <div class="truncate">
        <a href="<?php echo t7_esc($fullMenuUrl ?: '#'); ?>" class="t7-serif text-base sm:text-lg tracking-[0.14em] uppercase font-medium text-white hover:text-[#CBB288] transition-colors block leading-tight truncate">
          <?php echo t7_esc($t7RestaurantName); ?>
        </a>
        <?php if ($menuViewLevel === 'section' && ! empty($activeSection['name'])): ?>
        <span class="text-[10px] tracking-[0.20em] uppercase text-[#CBB288]/80 block truncate"><?php echo t7_esc($activeSection['name']); ?></span>
        <?php else: ?>
        <span class="text-[10px] tracking-[0.20em] uppercase text-[#CBB288]/80 block truncate">Digital Menu</span>
        <?php endif; ?>
      </div>
    </div>

    <!-- Center indicator -->
    <div class="hidden lg:flex items-center gap-2 px-3 py-1 rounded-full border border-[rgba(203,178,136,0.20)] bg-black/40">
      <span class="w-1.5 h-1.5 rounded-full bg-[#CBB288] animate-pulse"></span>
      <span class="text-[10px] uppercase tracking-[0.24em] font-semibold text-[#F6F1E8]/80">Restaurant Digital Menu</span>
    </div>

    <!-- Right nav -->
    <div class="flex items-center gap-2 sm:gap-4 flex-shrink-0">
      <?php if (! empty($sectionsForNav) && $menuViewLevel === 'home'): ?>
      <nav class="hidden md:flex items-center gap-4 text-[11px] uppercase tracking-[0.16em] text-[#F6F1E8]/70">
        <?php foreach (array_slice($sectionsForNav, 0, 4) as $navSec): ?>
        <a href="<?php echo t7_esc(t7_section_url($fullMenuUrl, $navSec['slug'] ?? '')); ?>" class="hover:text-[#CBB288] transition-colors whitespace-nowrap">
          <?php echo t7_esc($navSec['name'] ?? ''); ?>
        </a>
        <?php endforeach; ?>
      </nav>
      <?php endif; ?>

      <?php if (! empty($supportsReservations)): ?>
      <a href="<?php echo t7_esc($reservationUrl); ?>" class="inline-flex items-center gap-1.5 px-3 sm:px-4 py-2 text-[11px] uppercase tracking-[0.18em] font-semibold text-white bg-[#500C19] hover:bg-[#6A1324] border border-[rgba(203,178,136,0.40)] rounded transition-all shadow-sm active:translate-y-0.5">
        <span>Reserve</span>
      </a>
      <?php endif; ?>
    </div>
  </div>
</header>

<!-- ═══════════════════════════════ MAIN CONTENT ═══════════════════════════════ -->
<?php if ($menuViewLevel === 'section'): ?>
  <?php include __DIR__ . '/views/section.php'; ?>
<?php else: ?>
  <?php include __DIR__ . '/views/home.php'; ?>
<?php endif; ?>

<!-- ═══════════════════════════════ FOOTER ═══════════════════════════════════ -->
<footer class="bg-[#0b0a0d] border-t border-[rgba(203,178,136,0.28)] mt-12 py-10 text-[#F6F1E8]/60 text-xs">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6 pb-8 border-b border-white/10">
      <div>
        <div class="flex items-center gap-2 mb-1">
          <span class="t7-serif text-lg text-white uppercase tracking-wider"><?php echo t7_esc($t7RestaurantName); ?></span>
        </div>
        <?php if (! empty($restaurant['address'])): ?>
        <p class="text-[11px] text-[#F6F1E8]/50"><?php echo t7_esc($restaurant['address']); ?></p>
        <?php endif; ?>
      </div>
      <div class="flex flex-wrap items-center gap-5 text-[11px] uppercase tracking-wider">
        <?php if (! empty($restaurant['phone'])): ?>
        <a href="tel:<?php echo t7_esc(preg_replace('/\s+/', '', $restaurant['phone'])); ?>" class="text-[#CBB288] hover:underline font-mono"><?php echo t7_esc($restaurant['phone']); ?></a>
        <?php endif; ?>
        <?php if (! empty($restaurant['email'])): ?>
        <a href="mailto:<?php echo t7_esc($restaurant['email']); ?>" class="text-[#F6F1E8]/75 hover:text-[#CBB288] transition-colors"><?php echo t7_esc($restaurant['email']); ?></a>
        <?php endif; ?>
        <?php if (! empty($restaurant['website'])): ?>
        <a href="<?php echo t7_esc($restaurant['website']); ?>" target="_blank" rel="noopener" class="text-[#F6F1E8]/75 hover:text-[#CBB288] transition-colors"><?php echo t7_esc(parse_url($restaurant['website'], PHP_URL_HOST) ?: $restaurant['website']); ?></a>
        <?php endif; ?>
      </div>
    </div>
    <div class="pt-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-[10px] text-[#F6F1E8]/40 tracking-wider">
      <p>&copy; <?php echo date('Y'); ?> <?php echo t7_esc($t7RestaurantName); ?>. All rights reserved.</p>
      <p>Please inform your server of any allergies.</p>
    </div>
  </div>
</footer>

<!-- ═══════════════════════════════ BACK TO TOP ═══════════════════════════════ -->
<a id="t7-back-top" href="#" aria-label="Back to top">
  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M18 15l-6-6-6 6"/></svg>
</a>

<!-- ═══════════════════════════════ CART ════════════════════════════════════ -->
<?php if (! empty($supportsOrdering)): ?>
<link rel="stylesheet" href="<?php echo t7_esc($t7BaseUrl); ?>/legacy/assets/css/cart-modal.css">
<div id="resmenu-cart-widget" class="fixed bottom-6 left-6 z-50 hidden"></div>
<script src="<?php echo t7_esc($t7BaseUrl); ?>/assets/js/cart.js"></script>
<script src="<?php echo t7_esc($t7BaseUrl); ?>/assets/js/cart-widget.js"></script>
<script src="<?php echo t7_esc($t7BaseUrl); ?>/assets/js/cart-modal.js"></script>
<script>
(function() {
  var baseUrl = <?php echo json_encode($t7BaseUrl); ?>;
  var slug    = <?php echo json_encode($restaurant['slug'] ?? ''); ?>;
  var config  = {
    restaurantSlug: slug,
    currencySymbol: <?php echo json_encode($currencySymbol); ?>,
    uploadBaseUrl:  <?php echo json_encode($uploadBaseUrl ?? ''); ?>,
    checkoutUrl:    baseUrl + '/restaurant/' + slug + '/checkout',
    primaryColor:   <?php echo json_encode($primaryColor); ?>,
    deliveryFee: 0,
    taxRate: 0,
  };
  window.RESMENU_CART_CONFIG = config;
  if (window.RESMENU_CART_MODAL)  window.RESMENU_CART_MODAL.init(config);
  if (window.RESMENU_CART_WIDGET) window.RESMENU_CART_WIDGET.init(config);
  document.querySelectorAll('.add-to-bag-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
      var id    = this.getAttribute('data-item-id');
      var name  = this.getAttribute('data-item-name');
      var price = this.getAttribute('data-item-price');
      var image = this.getAttribute('data-item-image') || '';
      if (window.RESMENU_CART) window.RESMENU_CART.addItem(slug, { id: id, name: name, price: price, image: image }, 1);
    });
  });
})();
</script>
<?php endif; ?>

<!-- ═══════════════════════════════ SCRIPTS ══════════════════════════════════ -->
<script>
(function() {
  // ── Back-to-top (only after 50% scroll) ───────────────────────────────────
  var btn = document.getElementById('t7-back-top');
  if (btn) {
    window.addEventListener('scroll', function() {
      var scrolled = window.pageYOffset || document.documentElement.scrollTop;
      var docH     = document.documentElement.scrollHeight - window.innerHeight;
      if (docH > 0 && scrolled >= docH * 0.5) {
        btn.classList.add('visible');
      } else {
        btn.classList.remove('visible');
      }
    }, { passive: true });
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  // ── Scroll reveals ────────────────────────────────────────────────────────
  var reveals = document.querySelectorAll('.t7-reveal');
  if ('IntersectionObserver' in window && reveals.length) {
    var obs = new IntersectionObserver(function(entries) {
      entries.forEach(function(entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-in');
          obs.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
    reveals.forEach(function(el) { obs.observe(el); });
  } else {
    reveals.forEach(function(el) { el.classList.add('is-in'); });
  }

  // ── Section sidebar active highlight (section view only) ──────────────────
  var sideLinks = document.querySelectorAll('.t7-sidebar-link');
  if (sideLinks.length) {
    var sideObserver = new IntersectionObserver(function(entries) {
      entries.forEach(function(entry) {
        var id = entry.target.getAttribute('id');
        var link = document.querySelector('.t7-sidebar-link[href="#' + id + '"]');
        if (link) {
          if (entry.isIntersecting) {
            sideLinks.forEach(function(l) { l.classList.remove('active'); });
            link.classList.add('active');
          }
        }
      });
    }, { rootMargin: '-20% 0px -60% 0px', threshold: 0 });
    document.querySelectorAll('.t7-cat-section').forEach(function(sec) {
      sideObserver.observe(sec);
    });
  }
})();
</script>

</body>
</html>
