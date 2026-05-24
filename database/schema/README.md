# Database schema (source of truth)

> **Security:** `sigsolmenu_resmenu.sql` contains **production-like data** — Paystack keys (incl. live public key), encrypted gateway secrets, bcrypt password hashes, bank account numbers, and customer/manager PII. **Do not publish** to public git remotes without sanitization. See [docs/STANDALONE_VALIDATION.md](../../docs/STANDALONE_VALIDATION.md) (monorepo).

This directory is the **canonical schema baseline** for Resmenu Laravel. Deployments do not use `php artisan migrate:fresh` to create application tables.

## Baseline import

**File:** `sigsolmenu_resmenu.sql`

1. Create an empty MySQL database (e.g. `sigsolmenu_resmenu_laravel` for staging or `sigsolmenu_resmenu` for production).
2. Import via phpMyAdmin, MySQL Workbench, or CLI:

```bash
mysql -u USER -p DATABASE_NAME < database/schema/sigsolmenu_resmenu.sql
```

3. Point `.env` `DB_DATABASE` at that database.
4. Optionally run `php artisan migrate` for Laravel-only tables (`cache`, `jobs`, etc.) — **incremental only**.

## Incremental SQL

Files under `incremental/` document changes applied after the baseline dump was generated. If you import a fresh baseline from this repo, you usually **do not** need them unless your dump predates those changes. Apply only when upgrading an older snapshot.

## Protections

- **Never** run `migrate:fresh` on production or any database with live data.
- **Never** drop/rename legacy tables from Laravel migrations without a dedicated migration project.
- Auth uses `managers` and `admins`, not Laravel’s default `users` table.

## Staging refresh

Clone production data into a staging database (same schema):

```bash
mysqldump -u USER -p sigsolmenu_resmenu | mysql -u USER -p sigsolmenu_resmenu_laravel
```

See [DEPLOY.md](../../DEPLOY.md) and the repo `docs/STANDALONE_DEPLOYMENT.md`.
