---
name: Alo Alo Restaurant at Victoria Crown Plaza
colors:
  burgundy-deep: '#500C19'
  burgundy-wine: '#6A1324'
  burgundy-soft: '#8B1E32'
  onyx-surface: '#121214'
  onyx-card: '#141417'
  onyx-border: '#24242B'
  ivory-warm: '#F4EEE5'
  champagne-gold: '#C5A880'
  champagne-light: '#E8D8C3'
typography:
  title: Playfair Display (dish + section titles)
  desc: DM Sans (food descriptions only)
  ui: Plus Jakarta Sans
---

## Scope

Static reference only: [`code.html`](./code.html). Not wired into Resmenu PHP templates yet.

## Restored from backup DESIGN_1

- Full-viewport hero (`min-h-[92vh]` / `min-h-screen`)
- Search bar **inside** the hero (rounded pill + suggestions dropdown)
- Hours chips: Breakfast / All-Day / In-Room Cloche
- **Individual menu cards** with `hover:border-burgundy-wine/60`
- Desktop **2-column** grids (`md:grid-cols-2`); some items span 2
- Optional left image inside the card; full-width text when no image
- Self Service Bar: chip/option display cards (no checkboxes)
- National Menu: stepped vertical cards (no fake order checkboxes)

## Still in this sample

- Sidebar nav (hamburger) instead of horizontal category pills
- Logo: **desktop header only**; on mobile logo lives in the **sidebar** header
- Real VCP menu content from web.vcphotels.com/menu-list
- Real-time search + clickable suggestions that scroll to dishes
- Resmenu-shaped fields only (name, price, description, optional image — no tag chips)

## Motion & patterns

- Scroll reveals: section titles fade in; cards fade up / from left / from right
- Dark sections: Template 16 `binding_dark.png` pattern
- Light / white sections: Template 4 `bg_black.png` subtle pattern
- Drinks: white section bg; cards mix dark / ivory / white / gray / sparse burgundy (not every subcategory)
- Entree (`#entree`): VCP Self Service Bar — warm brown `#794A2A`, cream panels, real burger/sandwich/pizza imagery
- Back-to-top button after scroll
- Accent: sparse burgundy elsewhere; drinks use a deliberate color mix
