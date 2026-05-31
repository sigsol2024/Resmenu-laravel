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
| `subscriptions:apply-scheduled` | Daily 00:30 |
| `subscriptions:send-reminders` | Daily 09:00 |
| `payments:cleanup-stale` | Daily 02:00 |

## Queue worker (production)

Set in `.env`:

```
QUEUE_CONNECTION=database
```

Run a persistent worker (supervisor/systemd recommended):

```bash
php artisan queue:work --sleep=3 --tries=3
```

Transactional mail (orders, reservations, OTP, password reset) is queued when `QUEUE_CONNECTION` is not `sync`.

Monitor `failed_jobs` daily; alert if count > 0.

## Refunds (M14)

Platform and restaurant payment refunds are **manual only**. There is no automated refund API integration.

- Mark a payment `refunded` in **Admin → Payments** after processing the refund in Paystack/Flutterwave (or bank transfer reversal).
- Subscription access is not automatically reversed when status is set to `refunded`; adjust the subscription separately if needed.
- Keep gateway refund receipts in your accounting records; optional `note` is required when marking success/refunded manually.

## Required production `.env`

```
APP_DEBUG=false
APP_HMAC_SECRET=...
PAYMENT_ENCRYPTION_KEY=...   # never use the placeholder in staging/production
SESSION_DRIVER=database
SESSION_ENCRYPT=true
SESSION_SECURE_COOKIE=true
TRUST_PROXY_HEADERS=true       # behind nginx/Cloudflare
QUEUE_CONNECTION=database
CACHE_STORE=database           # use redis for multi-node webhook idempotency
CORS_ALLOWED_ORIGINS=https://resmenu.net
```

Local dev must set a dev-only `PAYMENT_ENCRYPTION_KEY` (any 32+ char secret).

## Rate limits

| Endpoint | Limit |
|----------|-------|
| `POST /login` | 10/min IP + 5/15min per username |
| `POST /forgot-password` | 5/min IP + 3/hour per email |
| `POST /register/otp` | 5/min route + service limits |
| `GET /api/restaurants`, menu | 120/min |
| `POST /api/orders`, reservations | 60/min |
| `POST /api/webhooks/*` | 120/min + 60/min |
| `POST /api/bank-transfer/confirm` | 10/min |
| Public checkout/reservation | 60/min |

## Backups & disaster recovery

**Database:** daily automated backup (mysqldump or host panel). Retention: **7 daily, 4 weekly, 3 monthly**. Test restore quarterly.

**Uploads:** `public/uploads/` (`logos/`, `heroes/`, `categories/`, `menu-items/`, `sections/`, `site/`, `template-previews/`, `qr-templates/`) — nightly tarball or object storage sync with same retention as DB.

## Monitoring runbook

| Signal | Source | Action |
|--------|--------|--------|
| Failed queue jobs | `failed_jobs` | Daily review; alert if > 0 |
| Payment verify failures | Laravel logs (`Log::warning` in payment services) | Investigate reference + restaurant |
| Webhook signature rejects | `WebhookController` logs | Check gateway secrets |
| Mail failures | `MailService` / queue retries | Fix SMTP/Zepto; check suppressions |
| Scheduler | cron `schedule:run` | Monitor last success |

## Composer on production

```bash
composer install --no-dev --optimize-autoloader
```

Do not deploy with dev dependencies enabled.

## Health

- `GET /health` — `{ "status": "ok|degraded", "db": true|false }`
- `GET /up` — Laravel built-in

## Related docs (repo `docs/`)

- [STANDALONE_DEPLOYMENT.md](../docs/STANDALONE_DEPLOYMENT.md)
- [SCHEMA.md](../docs/SCHEMA.md)
- [ARCHITECTURE.md](../docs/ARCHITECTURE.md)
- [CUTOVER.md](../docs/CUTOVER.md)
- [DEPLOYMENT_CHECKLIST.md](../docs/DEPLOYMENT_CHECKLIST.md)
