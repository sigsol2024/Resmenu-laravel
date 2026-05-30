# Resmenu Laravel — standalone deployment

This repository is a **self-contained production application**. You do not need a sibling `Resmenu/` legacy tree, external SQL paths, or hybrid feature flags to deploy.

## Document root

Point the web server vhost to **`public/`** only.

## Fresh server checklist

1. Clone or upload this repository to the server.
2. Create a MySQL database and user.
3. Import the schema baseline:
   ```bash
   mysql -u USER -p DATABASE_NAME < database/schema/sigsolmenu_resmenu.sql
   ```
   Or use phpMyAdmin → Import → `database/schema/sigsolmenu_resmenu.sql`.
4. Copy `.env.example` → `.env` and set `DB_*`, `APP_URL`, mail, payment keys.
5. `php artisan key:generate`
6. Optional: `php artisan storage:link` (only if you use Laravel’s `storage/app/public` disk elsewhere).
7. Optional: `php artisan migrate` (Laravel `cache`/`jobs` tables only — **not** `migrate:fresh`).
8. Ensure `public/uploads` contains menu images (copy from backup or production sync).
9. `php artisan config:cache` && `php artisan route:cache` && `php artisan view:cache` (production).
10. Cron: `* * * * * cd /path/to/Resmenu-laravel && php artisan schedule:run`

Full runbook: [docs/STANDALONE_DEPLOYMENT.md](../docs/STANDALONE_DEPLOYMENT.md).

## Database names

| Environment | Typical `DB_DATABASE` |
|-------------|------------------------|
| Staging / local | `sigsolmenu_resmenu_laravel` |
| Production | `sigsolmenu_resmenu` |

Boot guards refuse `APP_ENV=local|staging` with production DB name and refuse production with a non-production DB name.

## Uploads & static assets (in-repo)

| Path | Purpose |
|------|---------|
| `public/uploads` | Restaurant logos, menu item images |
| `public/assets` | CSS, JS, icons (`admin.css`, `cart.js`, etc.) |
| `public/templates` | Per-template static assets |
| `resources/views/menu/php-templates` | Menu templates 1–18 |

`UPLOAD_ROOT` empty → defaults to `public/uploads`.  
`UPLOAD_URL` → `${APP_URL}/uploads`.

## Schema rules

- **Source of truth:** `database/schema/sigsolmenu_resmenu.sql`
- **Never** `php artisan migrate:fresh` on production
- New features: add **incremental** Laravel migrations only; do not recreate core tables in migrations

## Scheduler

| Command | Schedule |
|---------|----------|
| `subscriptions:sync-expired` | Daily 00:15 |
| `subscriptions:send-reminders` | Daily 09:00 |

## Health

- `GET /health` — `{ "status": "ok|degraded", "db": true|false }`
- `GET /up` — Laravel built-in

## Related docs (repo `docs/`)

- [STANDALONE_DEPLOYMENT.md](../docs/STANDALONE_DEPLOYMENT.md)
- [SCHEMA.md](../docs/SCHEMA.md)
- [ARCHITECTURE.md](../docs/ARCHITECTURE.md)
- [CUTOVER.md](../docs/CUTOVER.md)
- [DEPLOYMENT_CHECKLIST.md](../docs/DEPLOYMENT_CHECKLIST.md)
