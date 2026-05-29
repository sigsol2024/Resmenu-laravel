# Sync Laravel database from live production dump

This runbook describes how to refresh a **Laravel Resmenu** database from a phpMyAdmin export of the live legacy site (`sigsolmenu_resmenu.sql`). The sync is **slug-based**: Laravel `restaurants.id` values do not need to match production.

## What gets synced (v1 default)

| Area | Tables | Strategy |
|------|--------|----------|
| Platform | `subscription_plans`, `templates`, `template_*`, `qr_templates`, `site_settings`, `payment_settings`, `admins` | REPLACE / UPSERT (admins never delete-all) |
| Per restaurant | `restaurants`, `managers`, `subscriptions`, `customization_settings`, payment/QR/reservation settings | UPDATE + scoped delete/insert |
| Menu | `sections`, `categories`, `category_secondary_sections`, `menu_items` | Strategy A: delete tree for `@rid`, re-insert by slug |
| History | `orders`, `order_items`, `table_reservations`, pending payments | Delete for `@rid`, re-insert; `order_items.menu_item_id` remapped by slug |
| Payments | `payments` | Insert only if not already present (`transaction_reference` or amount+created_at) |

**Skipped by default** (optional flags): `qr_code_scans`, `table_inventory_daily`, `login_attempts`.

**Never touched:** `subscription_email_log`, Laravel `cache` / `jobs`.

## Generate SQL files

From the Laravel project root:

```bash
php database/scripts/build_sync_laravel_from_live_dump.php \
  ../Resmenu/database/sigsolmenu_resmenu.sql \
  database/seed-sql/production/
```

Or via Artisan:

```bash
php artisan resmenu:build:sync-sql
php artisan resmenu:build:sync-sql --dry-run
php artisan resmenu:build:sync-sql --force-passwords
php artisan resmenu:build:sync-sql --include-scans --include-inventory
```

Output (11 files, ~1.2 MB total for May 2026 dump):

- `sync_part_00_platform.sql` — run first
- `sync_part_01_ellipse-hotels.sql` … `sync_part_10_vendome-cafe-s-menu.sql` — one per active restaurant (alphabetical by slug)
- Last part ends with `SET FOREIGN_KEY_CHECKS=1;`

Each restaurant file includes a comment block:

```sql
-- EXPECTED: sections=3 categories=39 menu_items=315 orders=0 reservations=0 payments=1
```

Use these counts after import to verify parity.

## Mandatory: staging before production

1. Create an empty database, e.g. `sigsolmenu_laravel_staging`.
2. Point a staging `.env` at it (`DB_DATABASE=sigsolmenu_laravel_staging`).
3. Run `php artisan migrate`.
4. Import all `sync_part_*.sql` files **in order** (see header in `sync_part_00_platform.sql`).
5. **Copy uploads** from the legacy server (see checklist below).
6. Run verification SQL and smoke-test the app (manager login, public menu, admin).
7. Only then repeat on production (after a full backup).

## Import methods

### CLI (recommended)

```bash
mysql -u USER -p sigsolmenu_laravel_staging < database/seed-sql/production/sync_part_00_platform.sql
mysql -u USER -p sigsolmenu_laravel_staging < database/seed-sql/production/sync_part_01_ellipse-hotels.sql
# … continue through sync_part_10
```

### phpMyAdmin

Import one file at a time in order. If uploads fail, raise `upload_max_filesize` and `max_execution_time`. Largest single file is typically under 200 KB for the current dump.

## Uploads checklist (critical)

Database sync alone does **not** fix broken images. Copy from the legacy server into Laravel’s public upload path:

- `uploads/logos/`
- `uploads/heroes/`
- `uploads/categories/`
- `uploads/menu-items/`
- Template / QR preview assets referenced in `templates` and `qr_templates`

Example (adjust hosts and paths):

```bash
rsync -avz user@legacy-host:/path/to/public/uploads/ /path/to/laravel/public/uploads/
```

Confirm `resmenu.upload_url` / `.env` `APP_URL` match where files are served.

## Verification SQL

After import, compare counts to `-- EXPECTED` comments in each part file:

```sql
SELECT r.slug,
  (SELECT COUNT(*) FROM sections s WHERE s.restaurant_id = r.id) AS sections,
  (SELECT COUNT(*) FROM categories c WHERE c.restaurant_id = r.id) AS categories,
  (SELECT COUNT(*) FROM menu_items m WHERE m.restaurant_id = r.id) AS menu_items,
  (SELECT COUNT(*) FROM orders o WHERE o.restaurant_id = r.id) AS orders,
  (SELECT COUNT(*) FROM table_reservations tr WHERE tr.restaurant_id = r.id) AS reservations
FROM restaurants r
WHERE r.is_active = 1
ORDER BY r.slug;
```

Spot-check:

- Admin login (`sigsol2024`, `brain`) on staging before production
- `template_id` and `subscriptions.status` for `opal-cafe-menu` and `the-mania-house`
- Public menu URLs for 2–3 slugs with images loading

## Safety features

- **Per-restaurant transactions** — failure rolls back one restaurant only
- **Slug guard** — `DELETE`/`INSERT` use `@rid`; if slug missing, insert stub then re-resolve
- **Admin UPSERT** — no wipe of all admins (avoids lockout)
- **Payments** — no blind delete; insert-if-missing only
- **`--force-passwords`** — only when you intend to overwrite hashes from the dump

## Regenerating after a new export

1. Export fresh `sigsolmenu_resmenu.sql` from production phpMyAdmin.
2. Place it at `Resmenu/database/sigsolmenu_resmenu.sql` (or pass path to the generator).
3. Re-run `php artisan resmenu:build:sync-sql`.
4. Re-import on staging and re-verify before production.

## Related commands

| Command | Purpose |
|---------|---------|
| `php artisan migrate` | Schema only — does not load production data |
| `php artisan resmenu:import:production` | Raw INSERT import for **empty** DB after migrate (fixed IDs) |
| `php artisan resmenu:seed:restaurant` | Single-restaurant idempotent menu seed |

For ongoing menu edits on one restaurant, prefer `resmenu:seed:restaurant` with hand-maintained seed files. For **full parity** with live production, use this sync generator.
