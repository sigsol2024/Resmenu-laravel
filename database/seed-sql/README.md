# seed-sql

Deterministic, idempotent SQL seeds for platform reference data and restaurant menus.

## Rules

- Slugs must follow `App\Support\SlugNormalizer` (lowercase, ASCII, hyphen-separated).
- Resolve `restaurant_id` via email or slug — never hardcode numeric IDs.
- Use `NOT EXISTS` guards aligned to unique keys:
  - `sections (restaurant_id, slug)`
  - `categories (restaurant_id, slug)`
  - `menu_items (restaurant_id, category_id, slug)`
- Large menu files: **no** single transaction wrapping the entire file (MariaDB implicit commits).

## Production sync (live dump → Laravel)

For **full parity** with the live legacy database (all 10 active restaurants, menus, subscriptions, orders), generate and import slug-based SQL:

```bash
php artisan resmenu:build:sync-sql
# Import database/seed-sql/production/sync_part_*.sql in order (staging first!)
```

See [docs/SYNC_FROM_LIVE_DUMP.md](../docs/SYNC_FROM_LIVE_DUMP.md) for staging workflow, uploads checklist, and verification queries.

Generated `sync_part_*.sql` files may be large; regenerate on the server from `sigsolmenu_resmenu.sql` rather than committing every export.

## Usage

```bash
php artisan migrate
php artisan db:seed
php artisan resmenu:seed:restaurant seed_maniahouse_menu.sql
```

**Path A (fresh DB):** `00_restaurants_bootstrap.sql` creates minimal restaurant rows before menu seeds. `SeedSqlSeeder` fails fast with a clear error if a restaurant dependency is missing.

**Staging auth:** `05_staging_auth_bootstrap.sql` adds `staging-admin` / `staging-manager` (password `password`).
