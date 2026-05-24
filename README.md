# Resmenu (Laravel)

Standalone production application for digital restaurant menus, ordering, reservations, and subscriptions.

## Quick start

1. Import `database/schema/sigsolmenu_resmenu.sql` into MySQL.
2. `cp .env.example .env` and configure database + `APP_URL`.
3. `php artisan key:generate`
4. `php artisan storage:link`
5. `php artisan serve` (local) or point vhost to `public/`.

Full guide: [docs/STANDALONE_DEPLOYMENT.md](../docs/STANDALONE_DEPLOYMENT.md) (monorepo) or [DEPLOY.md](DEPLOY.md).

## Requirements

- PHP 8.2+
- MySQL 8 / MariaDB 10.6+
- Composer (if regenerating `vendor/`)

## Schema

Baseline: **`database/schema/sigsolmenu_resmenu.sql`**.  
Do not use `migrate:fresh` on production.

## Structure

| Path | Role |
|------|------|
| `public/` | Web root |
| `public/assets/` | Shared CSS/JS |
| `public/templates/` | Template static files |
| `storage/app/public/uploads/` | Images |
| `resources/views/menu/php-templates/` | Menu templates 1–18 |

Legacy `Resmenu/` is **not** required to run this application.
