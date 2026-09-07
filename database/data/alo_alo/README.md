# Alo Alo menu seed package

Used by migration `2026_09_07_120000_seed_alo_alo_restaurant_menu.php`.

## Acceptance taxonomy (authoritative)

- **11 sections**
- **27 categories** (do not merge to force an older “24” target)
- **162 menu items**
- **78** staged / referenced images under `images/`

## Contents

- `menu.php` — full hierarchy (prices/descriptions sourced from DESIGN_1 where present)
- `image_sources.php` — map of `image_key` → local public path or URL
- `images/` — staged binaries (run `node stage_images.mjs` to refresh)
- `stage_images.mjs` — download/copy sources into `images/`
- `last_upload_manifest.json` — written by migration for safe `down()` (gitignored)

## Data quality flags (human confirmation required)

Do **not** auto-correct these without an authoritative source beyond DESIGN_1:

| Item | Current value | Why flagged |
| ---- | ------------- | ----------- |
| Plantain (Fried or Grilled) | 45000 | Outlier vs other Extra & Sides (4000–10000). DESIGN_1 matches 45000; DESIGN_3 shows ~4500 for a similar plantain line. |
| Glenfiddich 18yrs | 33000 | Below Glenfiddich 12yrs (150000) and 15yrs (228000). DESIGN_1 also has 33000 — may be a digit typo, unconfirmed. |

Logo / hero: no restaurant-owned logo or hero binary exists in this package or dump (`NULL` in schema). Do not fabricate branding.

## Run

```bash
# From Resmenu-laravel/
node database/data/alo_alo/stage_images.mjs
php artisan migrate
```

Resolves restaurant by slug `alo-alo-restaurant` or email `reservations@vcphotels.com` (never by hardcoded id). Sets `template_id = 7`. Imports images via `UploadService` (filename-only DB fields). Inserts only missing hierarchy rows; tracks created IDs in the manifest for rollback.
