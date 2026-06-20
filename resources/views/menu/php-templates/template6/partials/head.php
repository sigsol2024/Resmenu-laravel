<?php
/** @var array<string, mixed> $restaurant */
/** @var string $pageTitle */
$t6PageTitle = t6_esc($pageTitle ?? ($restaurant['name'] ?? 'Menu'));
?>
<!DOCTYPE html>
<html class="dark" lang="en">
<head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title><?php echo $t6PageTitle; ?></title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Bodoni+Moda:ital,wght@0,400..900;1,400..900&amp;family=Manrope:wght@200..800&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
<style>
.material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 300, 'GRAD' 0, 'opsz' 24; }
.lusso-gradient { background: linear-gradient(to bottom, rgba(23, 19, 14, 0) 0%, rgba(23, 19, 14, 0.9) 80%, rgba(23, 19, 14, 1) 100%); }
.glass-card { background: rgba(51, 33, 29, 0.6); backdrop-filter: blur(12px); border: 1px solid rgba(156, 143, 128, 0.1); }
.lusso-glass { background: rgba(43, 28, 24, 0.6); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); }
.t6-category-box {
  background: linear-gradient(135deg, rgba(46, 41, 36, 0.95) 0%, rgba(35, 31, 26, 0.98) 100%);
}
.t6-category-box:hover {
  border-color: rgba(240, 190, 120, 0.25);
}
.t6-dock-btn {
  background: rgba(35, 31, 26, 0.92);
  backdrop-filter: blur(12px);
  border: 1px solid rgba(156, 143, 128, 0.15);
  border-left: none;
  transition: all 0.3s;
}
.t6-dock-btn:hover {
  background: rgba(46, 41, 36, 0.98);
  transform: translateX(4px);
}
.t6-left-dock .t6-dock-btn:first-child { border-top-right-radius: 9999px; }
.t6-left-dock .t6-dock-btn:last-child { border-bottom-right-radius: 9999px; }
.t6-left-dock .t6-dock-btn:only-child { border-radius: 0 9999px 9999px 0; }
.t6-category-img-overlay {
  background: linear-gradient(to top, rgba(23, 19, 14, 0.98) 0%, rgba(23, 19, 14, 0.9) 30%, rgba(23, 19, 14, 0.55) 55%, rgba(23, 19, 14, 0.15) 75%, transparent 100%);
  pointer-events: none;
}
.category-card:hover .t6-category-img-overlay {
  background: linear-gradient(to top, rgba(23, 19, 14, 0.99) 0%, rgba(23, 19, 14, 0.92) 35%, rgba(23, 19, 14, 0.6) 60%, rgba(23, 19, 14, 0.2) 80%, transparent 100%);
}
@keyframes t6SlideInRight {
  from { transform: translateX(100%); }
  to { transform: translateX(0); }
}
#t6-contact-drawer.is-open,
#t6-menu-drawer.is-open {
  animation: t6SlideInRight 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
body.t6-drawer-open { overflow: hidden; }
.category-card:hover .card-overlay { background: rgba(33, 21, 18, 0.4); }
.category-card:hover img { transform: scale(1.05); }
.card-container:hover .hover-reveal { opacity: 1; transform: translateY(0); }
.hover-reveal { opacity: 0; transform: translateY(10px); transition: all 0.4s ease; }
.glass-pill { background: rgba(35, 31, 26, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(240, 190, 120, 0.15); }
.premium-shadow { box-shadow: 0 20px 40px -15px rgba(23, 19, 14, 0.8); }
.t6-reservation-img-overlay {
  background: linear-gradient(to right, rgba(35, 31, 26, 1) 0%, rgba(35, 31, 26, 0.55) 40%, rgba(35, 31, 26, 0.15) 70%, transparent 100%);
}
@media (max-width: 1023px) {
  .t6-reservation-img-overlay {
    background: linear-gradient(to bottom, rgba(35, 31, 26, 0.92) 0%, rgba(35, 31, 26, 0.5) 45%, rgba(35, 31, 26, 0.75) 100%);
  }
}
.serif { font-family: 'Bodoni Moda', serif; }
.hide-scrollbar::-webkit-scrollbar { display: none; }
.hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
::-webkit-scrollbar { width: 4px; }
::-webkit-scrollbar-track { background: #17130e; }
::-webkit-scrollbar-thumb { background: #b88b4a; }
#t6-search-bar { max-height: 0; overflow: hidden; transition: max-height 0.3s ease; }
#t6-search-bar.is-open { max-height: 88px; }
html { scroll-behavior: smooth; scroll-padding-top: 6rem; }
:root {
  --t6-header-h: 88px;
  --t6-header-gap: 3.5rem;
}
@media (min-width: 768px) {
  :root { --t6-header-gap: 2.5rem; }
}
.t6-main-offset {
  padding-top: calc(var(--t6-header-h) + var(--t6-header-gap));
}
.t6-main-after-hero {
  padding-top: 2.75rem;
}
@media (min-width: 768px) {
  .t6-main-after-hero { padding-top: 3.25rem; }
}
body.t6-has-cart { padding-bottom: 5rem; }
@media (min-width: 1024px) {
  body.t6-has-cart { padding-bottom: 2rem; }
}
.t6-res-form .t6-res-field,
.t6-res-form .t6-res-input {
  background: #1f1b16;
  border-color: #4f4539;
  border-radius: 0.125rem;
}
.t6-res-form .t6-res-field { border-bottom-width: 1px; }
.t6-res-form .t6-res-btn-primary {
  background: var(--t6-primary, #f0be78);
  color: #452b00;
  border-radius: 0.125rem;
  transition: opacity 0.2s;
}
.t6-res-form .t6-res-btn-primary:hover { opacity: 0.9; }
.t6-res-form .t6-res-btn-ghost {
  background: transparent;
  color: #d3c4b4;
  border: 1px solid #4f4539;
  border-radius: 0.125rem;
}
.t6-res-form .t6-res-btn-ghost:hover { border-color: var(--t6-primary, #f0be78); color: var(--t6-primary, #f0be78); }
.t6-res-form .t6-time-slot.selected,
.t6-res-form .t6-occasion-btn.selected {
  background: var(--t6-primary, #f0be78) !important;
  border-color: var(--t6-primary, #f0be78) !important;
  color: #452b00 !important;
}
.t6-res-form #reservation-calendar .t6-cal-past { color: #4f4539; }
.t6-res-form #reservation-calendar .t6-cal-full { color: #9c8f80; background: #2e2924; cursor: not-allowed; }
.t6-res-form #reservation-calendar .t6-cal-limited { background: rgba(245, 158, 11, 0.15); color: #f0be78; }
.t6-res-form #reservation-calendar .t6-cal-open { background: rgba(240, 190, 120, 0.12); color: #eae1d9; }
.t6-res-form #reservation-calendar .t6-cal-open:hover,
.t6-res-form #reservation-calendar .t6-cal-limited:hover { background: rgba(240, 190, 120, 0.22); }
.t6-res-form #reservation-calendar .t6-cal-selected { box-shadow: 0 0 0 2px var(--t6-primary, #f0be78); font-weight: 600; }
.t6-res-form .t6-res-input:focus,
.t6-res-form .t6-res-field:focus-within {
  border-color: var(--t6-primary, #f0be78) !important;
  outline: none;
  box-shadow: none;
}
.t6-res-form .res-step-indicator {
  background: transparent !important;
  border: 1px solid #4f4539 !important;
  color: #d3c4b4 !important;
}
.t6-res-form .res-step-indicator.t6-step-active,
.t6-res-form .res-step-indicator.t6-step-done {
  background: var(--t6-primary, #f0be78) !important;
  border-color: var(--t6-primary, #f0be78) !important;
  color: #452b00 !important;
}
.t6-res-form .t6-time-slot:not(:disabled):hover {
  border-color: var(--t6-primary, #f0be78) !important;
}
.t6-res-form .t6-res-qty-btn:hover {
  border-color: var(--t6-primary, #f0be78) !important;
  color: var(--t6-primary, #f0be78) !important;
}
.t6-res-form .t6-res-alert-error {
  border: 1px solid rgba(240, 190, 120, 0.35);
  background: rgba(240, 190, 120, 0.08);
  color: #eae1d9;
}
.t6-res-form .t6-res-alert-success {
  border: 1px solid rgba(240, 190, 120, 0.35);
  background: rgba(240, 190, 120, 0.12);
  color: #eae1d9;
}
.t6-res-form input:focus,
.t6-res-form button:focus-visible {
  outline: 2px solid var(--t6-primary, #f0be78);
  outline-offset: 2px;
}
.t6-res-form .t6-res-btn-primary,
.t6-res-form .res-next-btn,
.t6-res-form button[type="submit"] {
  background: var(--t6-primary, #f0be78) !important;
  color: #452b00 !important;
  border: none !important;
}
.t6-res-form #time-slots-container .text-red-400,
.t6-res-form #time-slots-container .text-red-500,
.t6-res-form #time-slots-container .text-gray-500 { color: #d3c4b4 !important; }
.t6-res-form input:invalid,
.t6-res-form textarea:invalid {
  border-color: #4f4539 !important;
  box-shadow: none !important;
}
.t6-res-form input:user-invalid,
.t6-res-form textarea:user-invalid {
  border-color: rgba(240, 190, 120, 0.55) !important;
}
.t6-res-form .res-step-indicator[style*="background"] {
  background: transparent !important;
}
.t6-res-form .res-step-indicator.t6-step-active[style],
.t6-res-form .res-step-indicator.t6-step-done[style] {
  background: var(--t6-primary, #f0be78) !important;
  color: #452b00 !important;
  border-color: var(--t6-primary, #f0be78) !important;
}
.t6-res-form .time-slot[style],
.t6-res-form .t6-time-slot[style] {
  background: inherit !important;
  color: inherit !important;
  border-color: inherit !important;
}
.t6-res-form .time-slot.selected,
.t6-res-form .t6-time-slot.selected {
  background: var(--t6-primary, #f0be78) !important;
  border-color: var(--t6-primary, #f0be78) !important;
  color: #452b00 !important;
}
</style>
<script id="tailwind-config">
tailwind.config = {
  darkMode: "class",
  theme: {
    extend: {
      colors: {
        "background": "#17130e", "surface": "#17130e", "surface-dim": "#17130e",
        "surface-bright": "#3d3833", "surface-container-lowest": "#110e09",
        "surface-container-low": "#1f1b16", "surface-container": "#231f1a",
        "surface-container-high": "#2e2924", "surface-container-highest": "#39342e",
        "surface-variant": "#39342e", "on-background": "#eae1d9", "on-surface": "#eae1d9",
        "on-surface-variant": "#d3c4b4", "outline": "#9c8f80", "outline-variant": "#4f4539",
        "primary": "#f0be78", "on-primary": "#452b00", "primary-container": "#b88b4a",
        "on-primary-container": "#3f2700", "secondary": "#d4c3b7", "on-secondary": "#392e26",
        "secondary-container": "#50453b", "on-secondary-container": "#c2b2a6",
        "tertiary": "#a3caf6", "on-tertiary": "#003256", "tertiary-container": "#7096c0",
        "inverse-surface": "#eae1d9", "inverse-on-surface": "#34302a", "surface-tint": "#f0be78"
      },
      borderRadius: { DEFAULT: "0.125rem", lg: "0.25rem", xl: "0.5rem", full: "0.75rem" },
      spacing: { gutter: "24px", lg: "48px", "container-max": "1280px", "margin-mobile": "16px", md: "24px", sm: "12px", xl: "80px", base: "8px", xs: "4px" },
      fontFamily: {
        "body-md": ["Manrope"], "body-lg": ["Manrope"], "body-sm": ["Manrope"],
        "display-lg": ["Bodoni Moda"], "display-lg-mobile": ["Bodoni Moda"],
        "headline-xl": ["Bodoni Moda"], "headline-lg": ["Bodoni Moda"], "headline-md": ["Bodoni Moda"],
        "label-lg": ["Manrope"], "label-md": ["Manrope"]
      },
      fontSize: {
        "body-md": ["16px", { lineHeight: "1.6", fontWeight: "400" }],
        "body-lg": ["18px", { lineHeight: "1.6", fontWeight: "400" }],
        "body-sm": ["14px", { lineHeight: "1.5", fontWeight: "400" }],
        "display-lg": ["72px", { lineHeight: "1.1", letterSpacing: "-0.02em", fontWeight: "700" }],
        "display-lg-mobile": ["48px", { lineHeight: "1.1", letterSpacing: "-0.02em", fontWeight: "700" }],
        "headline-xl": ["48px", { lineHeight: "1.2", fontWeight: "600" }],
        "headline-lg": ["32px", { lineHeight: "1.2", fontWeight: "600" }],
        "headline-md": ["24px", { lineHeight: "1.3", fontWeight: "500" }],
        "label-lg": ["14px", { lineHeight: "1", letterSpacing: "0.1em", fontWeight: "600" }],
        "label-md": ["12px", { lineHeight: "1", letterSpacing: "0.05em", fontWeight: "600" }]
      },
      maxWidth: { "container-max": "1280px" }
    }
  }
};
</script>
</head>
<body class="bg-background text-on-background font-body-md text-body-md antialiased selection:bg-primary-container selection:text-on-primary-container<?php echo ! empty($supportsOrdering) ? ' t6-has-cart' : ''; ?>">
