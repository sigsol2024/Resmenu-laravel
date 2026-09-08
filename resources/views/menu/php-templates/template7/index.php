<?php
/**
 * Template 7 — The Art Fusion
 * Visual source of truth: public/REFERENCES/DESIGN_1/code.html (+ landing.html for home).
 * Data remains fully dynamic — no restaurant-specific hardcoding.
 */
require_once __DIR__ . '/helpers.php';

$menuViewLevel = $menuViewLevel ?? 'home';
$GLOBALS['t7_is_template_preview'] = ! empty($isTemplatePreview);

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

$t7PageTitle = $restaurant['name'] ?? 'Menu';
if ($menuViewLevel === 'section' && ! empty($activeSection['name'])) {
    $t7PageTitle .= ' · ' . ($activeSection['name']);
}

$t7PatternBg      = t7_pattern_url($templateAssetBaseUrl, 'bg_black.png');
$t7PatternBinding = t7_pattern_url($templateAssetBaseUrl, 'binding_dark.png');
$t7LogoUrl        = t7_logo_url($uploadBaseUrl, $restaurant);
$t7RestaurantName = $restaurant['name'] ?? '';
$t7BackUrl = ($menuViewLevel === 'section') ? $fullMenuUrl : null;

$bodyClass = $menuViewLevel === 'home'
    ? 'bg-onyx-surface text-stone-200'
    : 'bg-onyx-surface text-stone-200';
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo t7_esc($t7PageTitle); ?></title>
  <?php if ($t7LogoUrl): ?>
  <link rel="icon" href="<?php echo t7_esc($t7LogoUrl); ?>">
  <link rel="apple-touch-icon" href="<?php echo t7_esc($t7LogoUrl); ?>">
  <?php endif; ?>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;1,9..40,400&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            "burgundy-deep": "#500C19",
            "burgundy-wine": "#6a1324",
            "burgundy-dark": "#36050e",
            "burgundy-soft": "#8b1e32",
            "onyx-surface": "#121214",
            "onyx-card": "#141417",
            "onyx-border": "#24242b",
            "ivory-warm": "#F4EEE5",
            "ivory-muted": "#EDE6DB",
            "champagne-gold": "#C5A880",
            "champagne-light": "#E8D8C3",
            "surface": "#121214",
            "surface-container": "#1a1a1f",
            "surface-container-low": "#16161a",
            "surface-container-high": "#222228",
            "surface-container-highest": "#2a2a32",
            "on-surface": "#F4EEE5",
            "on-surface-variant": "#B8AFA3",
            "outline-variant": "#3a3a44",
            "primary": "#C5A880",
          },
          fontFamily: {
            display: ['Playfair Display', 'serif'],
            sans: ['Plus Jakarta Sans', 'sans-serif'],
            desc: ['DM Sans', 'sans-serif'],
            headline: ['Playfair Display', 'serif'],
            label: ['Plus Jakarta Sans', 'sans-serif'],
            body: ['DM Sans', 'sans-serif'],
          }
        }
      }
    }
  </script>
  <style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; overscroll-behavior-y: none; -webkit-tap-highlight-color: transparent; }
    .font-serif-luxury, .t7-serif { font-family: 'Playfair Display', serif; }
    .type-desc { font-family: 'DM Sans', sans-serif; font-weight: 400; line-height: 1.55; }
    ::selection { background: #500C19; color: #F4EEE5; }
    .t7-noscroll::-webkit-scrollbar { display: none; }
    .t7-noscroll { -ms-overflow-style: none; scrollbar-width: none; }

    /* Patterned section shells — DESIGN_1 */
    .section-pattern { position: relative; isolation: isolate; }
    .section-pattern > .max-w-7xl,
    .section-pattern > .t7-section-inner { position: relative; z-index: 1; }
    .section-pattern::before {
      content: ''; position: absolute; inset: 0; z-index: 0; pointer-events: none;
      background-repeat: repeat; background-position: 0 0;
      background-image: url('<?php echo t7_esc($t7PatternBg); ?>');
      background-size: 280px 280px;
    }
    .section-pattern--dark { background-color: #121214; }
    .section-pattern--dark::before { opacity: 0.32; }
    .section-pattern--dark::after {
      content: ''; position: absolute; inset: 0; z-index: 0; pointer-events: none;
      background-image: url('<?php echo t7_esc($t7PatternBinding); ?>');
      background-repeat: repeat; background-size: 168px 168px;
      opacity: 0.22; mix-blend-mode: soft-light;
    }
    .section-pattern--light { background-color: #F4EEE5; color: #1c1917; }
    .section-pattern--light::before { opacity: 0.07; }
    .section-pattern--white { background-color: #FFFFFF; color: #1c1917; }
    .section-pattern--white::before { opacity: 0.045; }
    .section-national { background-color: #500C19 !important; }

    .section-photo-bg { position: relative; isolation: isolate; overflow: hidden; }
    .section-photo-bg > .max-w-7xl,
    .section-photo-bg > .t7-section-inner { position: relative; z-index: 1; }
    .section-photo-bg .section-photo-layer {
      position: absolute; inset: 0; z-index: -1; pointer-events: none;
      background-image: var(--photo, none); background-size: cover; background-position: center; opacity: 0.42;
    }
    .section-photo-bg .section-photo-veil {
      position: absolute; inset: 0; z-index: -1; pointer-events: none;
      background: linear-gradient(100deg, rgba(18,18,20,0.88) 0%, rgba(18,18,20,0.62) 48%, rgba(18,18,20,0.86) 100%);
    }

    /* Cards */
    .menu-item-card.card-burgundy {
      background-color: #500C19 !important; border-color: rgba(139,30,50,0.55) !important; color: #f5f5f4;
    }
    .menu-item-card.card-burgundy .dish-name,
    .menu-item-card.card-burgundy h3 { color: #fff !important; }
    .menu-item-card.card-burgundy .type-desc { color: rgba(231,229,228,0.88) !important; }
    .menu-item-card.card-burgundy .text-champagne-gold,
    .menu-item-card.card-burgundy .text-burgundy-deep { color: #E8D8C3 !important; }

    .menu-item-card.card-ivory {
      background-color: #F4EEE5 !important; border-color: rgba(80,12,25,0.12) !important; color: #1c1917;
    }
    .menu-item-card.card-ivory .dish-name,
    .menu-item-card.card-ivory h3 { color: #1c1917 !important; }
    .menu-item-card.card-ivory .type-desc { color: #57534e !important; }
    .menu-item-card.card-ivory .text-champagne-gold { color: #500C19 !important; }

    .menu-item-card.card-white {
      background-color: #fff !important; border-color: #e7e5e4 !important; color: #1c1917;
      box-shadow: 0 1px 2px rgba(28,25,23,0.04);
    }
    .menu-item-card.card-white .dish-name,
    .menu-item-card.card-white h3 { color: #1c1917 !important; }
    .menu-item-card.card-white .type-desc { color: #57534e !important; }
    .menu-item-card.card-white .text-champagne-gold,
    .menu-item-card.card-white .text-burgundy-deep { color: #500C19 !important; }

    .menu-item-card.card-dark {
      background-color: #141417 !important; border-color: #24242b !important;
    }
    .menu-item-card.card-dark .dish-name,
    .menu-item-card.card-dark h3 { color: #fff !important; }
    .menu-item-card.card-dark .type-desc { color: #d6d3d1 !important; }
    .menu-item-card.card-dark .text-champagne-gold { color: #C5A880 !important; }

    /* Entree */
    .section-entree {
      background-color: #794A2A; color: #fff; position: relative; isolation: isolate;
    }
    .section-entree::before {
      content: ''; position: absolute; inset: 0; z-index: 0; pointer-events: none;
      background-image: url('<?php echo t7_esc($t7PatternBg); ?>');
      background-repeat: repeat; background-size: 280px 280px; opacity: 0.18;
    }
    .section-entree > .t7-section-inner { position: relative; z-index: 1; }
    .entree-panel { background: #FFFFF6; color: #1c1917; }
    .entree-title { color: #500C19; }
    .float-deco {
      position: absolute; pointer-events: none; user-select: none; z-index: 2; opacity: 0.85;
    }
    .entree-hero-card { position: relative; overflow: visible; }
    .entree-hero-card .entree-food {
      width: 7rem; height: auto; object-fit: contain; flex-shrink: 0;
      filter: drop-shadow(0 12px 24px rgba(0,0,0,0.35));
      opacity: 0; transition: opacity 0.85s ease;
    }
    .entree-hero-card.is-in .entree-food {
      opacity: 1; transition-delay: 0.95s;
    }
    @media (prefers-reduced-motion: reduce) {
      .entree-hero-card .entree-food,
      .entree-hero-card.is-in .entree-food {
        opacity: 1; transition: none; transition-delay: 0s;
      }
    }
    @media (min-width: 640px) {
      .entree-hero-card .entree-food { width: 11rem; }
    }
    @media (min-width: 768px) {
      .entree-hero-card .entree-food {
        position: absolute; top: 50%; transform: translateY(-50%);
        width: 14rem; z-index: 3;
      }
      .entree-hero-card .entree-food--right { right: -3.5rem; }
      .entree-hero-card .entree-food--left { left: -3.5rem; }
    }
    @media (min-width: 1024px) {
      .entree-hero-card .entree-food { width: 16rem; }
      .entree-hero-card .entree-food--right { right: -4.5rem; }
      .entree-hero-card .entree-food--left { left: -4.5rem; }
    }
    .entree-options-grid {
      display: grid;
      grid-template-columns: repeat(3, minmax(0, 1fr));
      gap: 0.5rem 0.75rem;
    }
    .entree-options-grid p.label {
      font-size: 0.625rem; letter-spacing: 0.06em; text-transform: uppercase;
      font-weight: 600; color: #78716c;
    }
    .entree-options-grid p.choose {
      font-size: 0.6875rem; font-weight: 600; color: #500C19; margin-top: 0.125rem;
    }
    .entree-options-grid ul {
      margin-top: 0.5rem; font-size: 0.6875rem; line-height: 1.35; color: #44403c;
      list-style: none; padding: 0;
      overflow-wrap: anywhere; word-break: break-word;
    }
    .entree-options-grid ul li { padding: 0.15rem 0; }
    @media (min-width: 640px) {
      .entree-options-grid { gap: 1.25rem; }
      .entree-options-grid p.label { font-size: 0.75rem; }
      .entree-options-grid p.choose { font-size: 0.8125rem; }
      .entree-options-grid ul { font-size: 0.8125rem; line-height: 1.45; }
    }
    /* Horizontal dish image strip — DESIGN_1 card media */
    .menu-item-card .dish-media {
      width: 100%; height: 11rem; flex-shrink: 0; overflow: hidden;
    }
    @media (min-width: 640px) {
      .menu-item-card .dish-media {
        width: 10rem; height: auto; min-height: 9rem; align-self: stretch;
      }
    }
    @media (min-width: 768px) {
      .menu-item-card .dish-media { width: 11rem; }
    }
    .menu-item-card .dish-media img {
      width: 100%; height: 100%; object-fit: cover; display: block;
    }

    /* Grill belt — DESIGN_1 monumental */
    .grill-section { position: relative; }
    .grill-section .grill-inner { padding-left: 2.75rem; }
    @media (min-width: 640px) { .grill-section .grill-inner { padding-left: 3.5rem; } }
    @media (min-width: 1024px) { .grill-section .grill-inner { padding-left: 4.25rem; } }
    .grill-belt {
      position: absolute; left: 0.15rem; top: 0; bottom: 0; z-index: 2; width: 2.75rem;
      overflow: hidden; pointer-events: none;
      mask-image: linear-gradient(to bottom, transparent 0%, #000 8%, #000 92%, transparent 100%);
      -webkit-mask-image: linear-gradient(to bottom, transparent 0%, #000 8%, #000 92%, transparent 100%);
    }
    @media (min-width: 640px) { .grill-belt { left: 0.35rem; width: 3.25rem; } }
    @media (min-width: 1024px) { .grill-belt { left: 0.5rem; width: 3.75rem; } }
    .grill-belt-track {
      display: flex; flex-direction: column; align-items: center; gap: 1.75rem;
      width: 100%; will-change: transform; animation: grill-belt-scroll 36s linear infinite;
    }
    .grill-belt-track span {
      writing-mode: vertical-rl; transform: rotate(180deg);
      font-family: 'Playfair Display', serif;
      font-size: clamp(2.1rem, 6.5vw, 6.25rem);
      letter-spacing: 0.42em; color: rgba(255,255,255,0.15);
      text-shadow: 0 0 40px rgba(0,0,0,0.35); line-height: 1; user-select: none; flex-shrink: 0;
    }
    @keyframes grill-belt-scroll {
      from { transform: translateY(0); }
      to { transform: translateY(-50%); }
    }
    @media (prefers-reduced-motion: reduce) {
      .grill-belt-track { animation: none; }
    }

    /* Drinks themes */
    .drink-cat--warm { background: linear-gradient(160deg, #F4EEE5 0%, #fff 55%, #f7f0e8 100%); }
    .drink-cat--fresh { background-color: #132018; color: #f5f5f4; }
    .drink-cat--soft { background-color: #1a1520; color: #f5f5f4; }
    .drink-cat--smoothie { background: linear-gradient(145deg, #794A2A 0%, #5c351c 100%); color: #fff; }
    .drink-cat--juice { background: linear-gradient(160deg, #FFFFF6 0%, #f3ebe0 100%); border: 1px solid rgba(80,12,25,0.08); }
    .drink-cat--dark { background-color: #121214; color: #f5f5f4; }
    .drink-cat {
      overflow: hidden;
      isolation: isolate;
    }
    /* Opacity-only reveal inside drink shells — no translate that spills into the next category */
    .drink-cat .menu-item-card.reveal {
      transform: none !important;
    }
    .drink-cat .menu-item-card.reveal:not(.is-in) {
      opacity: 0;
    }
    .drink-cat .menu-item-card.reveal.is-in {
      opacity: 1;
    }
    .drink-hero-panel {
      background: #FFFFF6; color: #1c1917; position: relative; overflow: hidden;
    }
    .drink-hero-panel--dark {
      background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12);
    }

    /* Reveals — DESIGN_1 multi-direction */
    .reveal, .t7-reveal {
      opacity: 0; will-change: opacity, transform;
      transition: opacity 0.85s cubic-bezier(0.22, 1, 0.36, 1), transform 0.85s cubic-bezier(0.22, 1, 0.36, 1);
    }
    .reveal-up { transform: translateY(32px); }
    .reveal-left { transform: translateX(-36px); }
    .reveal-right { transform: translateX(36px); }
    .reveal-fade { transform: translateY(12px); }
    .reveal:not(.reveal-up):not(.reveal-left):not(.reveal-right):not(.reveal-fade),
    .t7-reveal { transform: translateY(32px); }
    .reveal.is-in, .t7-reveal.is-in { opacity: 1; transform: none; will-change: auto; }
    .reveal-delay-1 { transition-delay: 0.06s; }
    .reveal-delay-2 { transition-delay: 0.12s; }
    .reveal-delay-3 { transition-delay: 0.18s; }
    @media (prefers-reduced-motion: reduce) {
      .reveal, .t7-reveal { opacity: 1; transform: none; transition: none; }
    }

    /* Landing tile accents + card skins */
    .tile-burgundy { background: linear-gradient(160deg, #500C19 0%, #36050e 100%); border-color: rgba(197,168,128,0.35) !important; }
    .tile-drinks { border-color: rgba(197,168,128,0.55) !important; }
    .tile-grill { border-color: rgba(106,19,36,0.6) !important; }
    .tile-entree { background: linear-gradient(160deg, #794A2A 0%, #5c351c 100%); border-color: rgba(255,255,246,0.2) !important; }
    .tile-world { border-color: rgba(80,12,25,0.25) !important; }
    .t7-land-card { border-color: rgba(36,36,43,1); }
    .t7-land-card--light { background: #F4EEE5; border-color: rgba(80,12,25,0.12) !important; }
    .t7-land-card--dark { background: #141417; border-color: rgba(36,36,43,1); }
    .t7-land-card--burgundy { background: linear-gradient(160deg, #500C19 0%, #36050e 100%); border-color: rgba(197,168,128,0.35) !important; }
    .t7-land-card--entree { background: linear-gradient(160deg, #794A2A 0%, #5c351c 100%); border-color: rgba(255,255,246,0.18) !important; }
    .t7-land-card--drinks { background: linear-gradient(160deg, #1a1a1f 0%, #121214 100%); border-color: rgba(197,168,128,0.45) !important; }

    .menu-item-card.is-dimmed { opacity: 0.28; transition: opacity 0.35s ease; }
    .menu-item-card.is-highlight {
      outline: 2px solid rgba(197,168,128,0.85);
      outline-offset: 3px;
      box-shadow: 0 0 0 6px rgba(80,12,25,0.28);
      transition: outline 0.3s ease, box-shadow 0.3s ease, opacity 0.35s ease;
    }

    body.t7-drawer-open { overflow: hidden; }
    #t7-menu-drawer.is-open { display: block; }
    #t7-menu-backdrop:not(.hidden) { display: block; }

    .t7-layout { display: grid; grid-template-columns: 1fr; }
    @media (min-width: 1024px) {
      .t7-layout { grid-template-columns: 14rem minmax(0, 1fr); }
    }
    .t7-sidebar-link.active {
      color: #E8D8C3 !important; border-left-color: #C5A880 !important; background: rgba(80,12,25,0.35);
    }

    .material-symbols-outlined {
      font-family: 'Material Symbols Outlined'; font-weight: normal; font-style: normal;
      font-size: 1.25rem; line-height: 1; letter-spacing: normal; text-transform: none;
      display: inline-block; white-space: nowrap; word-wrap: normal; direction: ltr;
      -webkit-font-smoothing: antialiased;
    }

    /* Reservation form — T6 markup, T7 burgundy / champagne skin */
    .t7-reservation { --t6-primary: #C5A880; }
    .t7-reservation .t6-res-form .t6-res-field,
    .t7-reservation .t6-res-form .t6-res-input {
      background: #16161a; border-color: rgba(197,168,128,0.25); color: #F4EEE5;
    }
    .t7-reservation .t6-res-form .t6-res-btn-primary,
    .t7-reservation .t6-res-form .res-next-btn,
    .t7-reservation .t6-res-form button[type="submit"] {
      background: #500C19; color: #F4EEE5; border: 1px solid rgba(197,168,128,0.45);
    }
    .t7-reservation .t6-res-form .t6-res-btn-ghost {
      border: 1px solid rgba(197,168,128,0.35); color: #E8D8C3; background: transparent;
    }
    .t7-reservation .t6-res-form .t6-time-slot.selected,
    .t7-reservation .t6-res-form .t6-occasion-btn.selected,
    .t7-reservation .t6-res-form .res-step-indicator.t6-step-active,
    .t7-reservation .t6-res-form .res-step-indicator.t6-step-done {
      background: #500C19 !important; color: #F4EEE5 !important; border-color: #C5A880 !important;
    }
    .t7-reservation .t6-res-form #reservation-calendar .t6-cal-selected {
      box-shadow: 0 0 0 2px #C5A880; font-weight: 600;
    }
    .t7-reservation .t6-res-alert-success {
      background: rgba(16,185,129,0.15); border: 1px solid rgba(16,185,129,0.35); color: #A7F3D0;
    }
    .t7-reservation .t6-res-alert-error {
      background: rgba(80,12,25,0.35); border: 1px solid rgba(197,168,128,0.35); color: #F4EEE5;
    }

    #t7-back-top {
      position: fixed; right: 1rem; bottom: 5.5rem; z-index: 45;
      width: 2.75rem; height: 2.75rem; border-radius: 0.5rem;
      background: #6a1324; color: #fff; display: flex; align-items: center; justify-content: center;
      opacity: 0; pointer-events: none; transform: translateY(8px);
      transition: opacity 0.3s, transform 0.3s; box-shadow: 0 8px 24px rgba(0,0,0,0.25);
    }
    #t7-back-top.visible { opacity: 1; pointer-events: auto; transform: none; }
  </style>
</head>
<body class="<?php echo t7_esc($bodyClass); ?> antialiased min-h-screen flex flex-col selection:bg-burgundy-wine selection:text-white" data-t7-view="<?php echo t7_esc($menuViewLevel); ?>">

<header class="sticky top-0 z-50 bg-onyx-surface/95 backdrop-blur-md border-b border-onyx-border transition-all" id="t7-header">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 sm:h-[4.5rem] flex items-center justify-between gap-2 sm:gap-4">
    <div class="flex items-center gap-2 sm:gap-3 min-w-0 flex-1">
      <?php if ($t7BackUrl): ?>
      <a href="<?php echo t7_esc($t7BackUrl); ?>" class="flex-shrink-0 flex items-center justify-center w-9 h-9 rounded border border-onyx-border hover:border-champagne-gold text-champagne-gold hover:text-white transition-colors" aria-label="Back to menu">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
      </a>
      <?php endif; ?>
      <?php if ($t7LogoUrl): ?>
      <a href="<?php echo t7_esc($fullMenuUrl ?: '#'); ?>" class="flex-shrink-0 p-1 bg-white/95 border border-onyx-border rounded hover:border-champagne-gold transition-colors">
        <img src="<?php echo t7_esc($t7LogoUrl); ?>" alt="<?php echo t7_esc($t7RestaurantName); ?>" class="h-7 w-auto object-contain">
      </a>
      <?php endif; ?>
      <div class="truncate">
        <a href="<?php echo t7_esc($fullMenuUrl ?: '#'); ?>" class="font-serif-luxury text-base sm:text-lg tracking-[0.14em] uppercase font-medium text-champagne-light hover:text-white transition-colors block leading-tight truncate">
          <?php echo t7_esc($t7RestaurantName); ?>
        </a>
        <?php if ($menuViewLevel === 'section' && ! empty($activeSection['name'])): ?>
        <span class="text-[10px] tracking-[0.20em] uppercase text-white/50 block truncate"><?php echo t7_esc($activeSection['name']); ?></span>
        <?php else: ?>
        <span class="text-[10px] tracking-[0.20em] uppercase text-white/50 block truncate">Digital Menu</span>
        <?php endif; ?>
      </div>
    </div>

    <div class="hidden lg:flex items-center gap-2 px-3 py-1 rounded-full border border-white/10 bg-black/40">
      <span class="w-1.5 h-1.5 rounded-full bg-champagne-gold animate-pulse"></span>
      <span class="text-[10px] uppercase tracking-[0.24em] font-semibold text-white/80">Restaurant Digital Menu</span>
    </div>

    <div class="flex items-center gap-2 sm:gap-4 flex-shrink-0">
      <?php
        $t7ShowDrawerToggle = false;
        if (($menuViewLevel ?? '') === 'home') {
            $t7ShowDrawerToggle = ! empty($sectionsForNav);
        } elseif (($menuViewLevel ?? '') === 'section') {
            $t7ShowDrawerToggle = ! empty($activeSection['categories']);
        }
      ?>
      <?php if ($t7ShowDrawerToggle): ?>
      <button type="button"
              id="t7-menu-toggle"
              class="inline-flex items-center justify-center w-11 h-11 min-h-[44px] min-w-[44px] rounded border border-champagne-gold/40 text-champagne-light bg-burgundy-deep/80 hover:bg-burgundy-wine hover:text-white transition-all shadow-sm"
              aria-label="Open page menu"
              aria-controls="t7-menu-drawer"
              aria-expanded="false">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
          <path d="M4 7h16M4 12h16M4 17h16"/>
        </svg>
      </button>
      <?php endif; ?>
    </div>
  </div>
</header>

<?php include __DIR__ . '/partials/menu-drawer.php'; ?>

<?php if ($menuViewLevel === 'section'): ?>
  <?php include __DIR__ . '/views/section.php'; ?>
<?php else: ?>
  <?php include __DIR__ . '/views/home.php'; ?>
<?php endif; ?>

<footer class="bg-[#0b0a0d] border-t border-onyx-border mt-auto py-10 text-white/60 text-xs">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6 pb-8 border-b border-white/10">
      <div>
        <span class="font-serif-luxury text-lg text-white uppercase tracking-wider"><?php echo t7_esc($t7RestaurantName); ?></span>
        <?php if (! empty($restaurant['address'])): ?>
        <p class="text-[11px] text-white/50 mt-1"><?php echo t7_esc($restaurant['address']); ?></p>
        <?php endif; ?>
      </div>
      <div class="flex flex-wrap items-center gap-5 text-[11px] uppercase tracking-wider">
        <?php if (! empty($restaurant['phone'])): ?>
        <a href="tel:<?php echo t7_esc(preg_replace('/\s+/', '', $restaurant['phone'])); ?>" class="text-champagne-gold hover:underline font-mono"><?php echo t7_esc($restaurant['phone']); ?></a>
        <?php endif; ?>
        <?php if (! empty($restaurant['email'])): ?>
        <a href="mailto:<?php echo t7_esc($restaurant['email']); ?>" class="text-white/75 hover:text-champagne-gold transition-colors"><?php echo t7_esc($restaurant['email']); ?></a>
        <?php endif; ?>
        <?php if (! empty($restaurant['website'])): ?>
        <a href="<?php echo t7_esc($restaurant['website']); ?>" target="_blank" rel="noopener" class="text-white/75 hover:text-champagne-gold transition-colors"><?php echo t7_esc(parse_url($restaurant['website'], PHP_URL_HOST) ?: $restaurant['website']); ?></a>
        <?php endif; ?>
      </div>
    </div>
    <div class="pt-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-[10px] text-white/40 tracking-wider">
      <p>&copy; <?php echo date('Y'); ?> <?php echo t7_esc($t7RestaurantName); ?>. All rights reserved.</p>
      <p>Please inform your server of any allergies.</p>
    </div>
  </div>
</footer>

<a id="t7-back-top" href="#" aria-label="Back to top">
  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M18 15l-6-6-6 6"/></svg>
</a>

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

<script>
(function() {
  var btn = document.getElementById('t7-back-top');
  if (btn) {
    window.addEventListener('scroll', function() {
      var scrolled = window.pageYOffset || document.documentElement.scrollTop;
      var docH = document.documentElement.scrollHeight - window.innerHeight;
      if (docH > 0 && scrolled >= docH * 0.5) btn.classList.add('visible');
      else btn.classList.remove('visible');
    }, { passive: true });
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }
  var reveals = document.querySelectorAll('.reveal, .t7-reveal');
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
  var sideLinks = document.querySelectorAll('.t7-sidebar-link');
  if (sideLinks.length) {
    var sideObserver = new IntersectionObserver(function(entries) {
      entries.forEach(function(entry) {
        var id = entry.target.getAttribute('id');
        var link = document.querySelector('.t7-sidebar-link[href="#' + id + '"]');
        if (link && entry.isIntersecting) {
          sideLinks.forEach(function(l) { l.classList.remove('active'); });
          link.classList.add('active');
        }
      });
    }, { rootMargin: '-20% 0px -60% 0px', threshold: 0 });
    document.querySelectorAll('.t7-cat-section').forEach(function(sec) { sideObserver.observe(sec); });
  }
})();
</script>
<?php include __DIR__ . '/partials/scripts.php'; ?>
</body>
</html>
