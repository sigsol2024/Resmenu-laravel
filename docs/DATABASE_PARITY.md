# Database parity: production vs Laravel

## Schema

| Check | Result |
|-------|--------|
| Table count (legacy `sigsolmenu_resmenu.sql` vs Laravel baseline) | **34 / 34 match** |
| Extra Laravel table | `subscription_email_log` (incremental migration `2026_05_29_000001`) — new feature, empty until cron runs |
| Baseline migrations | Frozen under `database/migrations/baseline/` — do not edit |

Reference copies: `Resmenu/database/sigsolmenu_resmenu.sql`, `Resmenu-laravel/database/schema/sigsolmenu_laravel.sql`.

## Production restaurants (10)

| Slug | Menu in seed-sql? | Notes |
|------|-------------------|--------|
| `the-lusso-restaurant` | Yes (`seed_lusso_drinks.sql`) | |
| `the-mania-house` | Yes (`seed_maniahouse_menu.sql`) | Bootstrap slug fixed to match production |
| `opal-cafe-menu` | Yes (`seed_opal_menu.sql`) | Was wrongly `opal-lagos` in bootstrap |
| `salt-and-social` | Yes | |
| `swiss-the-vistana` | Yes | Manifest slug fixed |
| `vendome-cafe-s-menu` | Yes | |
| `lava` | **No** — stub in `06_production_restaurants_missing.sql` | Full menu only in production dump |
| `theview-hotel` | **No** — stub | Large menu (~229 items) |
| `nostalgia-menu` | **No** — stub | Template 18 |
| `ellipse-hotels` | **No** — stub | Reservations on, 0 menu items in dump |

## Recommended data strategies

### A — Full production parity (live site cutover)

Use when the Laravel DB must match the current live site exactly (all menus, orders, QR scans, subscriptions).

```bash
php artisan migrate
php database/scripts/build_data_only_dump.php
php artisan resmenu:import:production --force
php artisan migrate   # applies subscription_email_log if not yet run
```

`build_data_only_dump.php` reads `Resmenu/database/sigsolmenu_resmenu.sql` and writes  
`database/seed-sql/production/sigsolmenu_data_only.sql` (INSERTs only, FK checks off).

### B — Partial dev/staging seeds

```bash
php artisan migrate
php artisan db:seed
```

Runs `SeedSqlSeeder` manifest: platform data + 6 restaurant menus + 4 missing restaurant stubs.  
Does **not** include LAVA / Theview / Nostalgia / Ellipse menu content.

### C — Single restaurant

```bash
php artisan resmenu:seed:restaurant seed_maniahouse_menu.sql
```

## Slug fixes applied (2026-05-29)

- `mania-house` → `the-mania-house`
- `opal-lagos` → `opal-cafe-menu`
- Manifest `swiss-vistana` → `swiss-the-vistana`
- Staging manager links to `the-mania-house`

## Audit script

```bash
php database/scripts/audit_production_data.php
php database/scripts/compare_schema_tables.php
```

## Data tables in production dump (all should import via path A)

`admins`, `managers`, `restaurants`, `sections`, `categories`, `category_secondary_sections`, `menu_items`, `customization_settings`, `subscriptions`, `subscription_plans`, `template_*`, `qr_*`, `orders`, `order_items`, `table_reservations`, `table_inventory_daily`, `payments`, `payment_settings`, `restaurant_payment_settings`, `restaurant_qr_codes`, `restaurant_reservation_settings`, `site_settings`, `pending_*`, `password_reset_tokens`, `qr_code_scans`, etc.

## After import

1. Verify manager logins (passwords from production `password_hash` bcrypt).
2. Run `php artisan storage:link` and copy `uploads/` assets from legacy server.
3. Confirm `.env` `UPLOAD_URL` / `resmenu.upload_url` points to public uploads.
