# Admin Permissions Audit Remediation — Final Report

## 1. Files changed

- `app/Services/Admin/AdminAccountService.php`
- `app/Http/Middleware/EnsureAdminPermission.php`
- `app/Http/Middleware/EnsureSuperAdmin.php`
- `app/Support/ManagerImpersonation.php`
- `tests/Feature/AdminPermissionsTest.php`
- `docs/ADMIN_PERMISSIONS_AUDIT_REMEDIATION_REPORT.md` (this file)

## 2. Exact changes

### FIX-1 + FIX-2 — AdminAccountService
- `create` / `update` / `delete` call `assertActorMayManageAdmins()` (refresh + `isActive()` + `isSuperAdmin()`, fail closed).
- `update` / `delete` wrap work in `DB::transaction()`.
- Inside the transaction: `Admin::query()->orderBy('id')->lockForUpdate()->get()`, then re-resolve actor/target from the locked set, re-assert AuthZ, re-check primary/self/zero-active-Super **after** locks, then mutate.
- Zero-active-Super check uses the locked collection (excludes target; requires another `isSuperAdmin() && isActive()`).

### FIX-3 — Middleware
- `EnsureAdminPermission` and `EnsureSuperAdmin` `refresh()` the authenticated Admin before evaluating active/Super/`hasPermission`. Missing row → logout/session clear (permission) or 403 (super).

### FIX-4 — Impersonation
- `ManagerImpersonation::restoreAdmin` refreshes Admin and requires `isActive()` before `login()`. Inactive → invalidate session → redirect `login` with error. Impersonation start/who-can-impersonate unchanged.

### Tests
- Updated zero-Super case for service AuthZ (Regular can no longer be actor).
- Added: Regular cannot invoke service; sequential demote of one of two Supers; sequential mutual-demotion invariant (≥1 Super remains).

## 3. HIGH-1 race-condition fix

Previously: unlocked `exists()` then `save()`/`delete()` (TOCTOU).

Now: exclusive row locks on all `admins` rows (`SELECT … FOR UPDATE` via `lockForUpdate()`), ordered by `id`, inside one transaction; active-Super invariant evaluated on that snapshot; mutation commits only after checks. Concurrent Admin-management mutations serialize on those locks, so two demotions cannot both observe “another Super remains.”

## 4. AdminAccountService authorization

Privileged mutations require an **active Super** actor from the Admin model (not session/`user_role`/request). Regular actors receive ValidationException (`admin` → Super Admin access required).

## 5. Middleware refresh

Permission and Super middleware no longer depend on `admin.active` having refreshed first; each refreshes before AuthZ. Unknown permission keys still 403. Super still bypasses valid module keys via `hasPermission()`.

## 6. Impersonation

Inactive impersonator restore hardened as above. No redesign of Manager impersonation.

## 7–11. Test results

| Suite | Result |
| ----- | ------ |
| Unit `AdminPermissionMapTest` | **Passed** (2 tests, 13 assertions) |
| Feature `AdminPermissionsTest` | **Skipped** — 33/33 skipped, 0 assertions (MySQL/schema unavailable in this environment) |
| True parallel multi-connection concurrency | **Not executed** — sequential mutual-demotion invariant test added; isolated parallel DB clients not available here |

Do **not** treat feature AuthZ as empirically green until MySQL is available and the suite is re-run.

Re-run when DB is up:

```bash
php artisan migrate --force --path=database/migrations/2026_09_21_220000_add_admin_permissions_and_active.php
php vendor/phpunit/phpunit/phpunit --filter AdminPermissionsTest
```

## 12. Remaining findings

- Feature/concurrency tests still need a live MySQL run for release assurance.
- No new Critical/High AuthZ issues introduced by this remediation (static review).
- LOW audit items not in scope (ActivityLog for privilege changes; `/admin/login` rate-limit parity) remain optional.

## 13. Deploy recommendation

**Conditionally ready to deploy** after:

1. Migration applied on target DB  
2. `AdminPermissionsTest` run **green** on that environment  
3. Super Admin manually grants Regular permissions post-migrate (deny-by-default unchanged)

Code fixes for HIGH-1 / MED service AuthZ / MED middleware refresh / LOW impersonation restore are implemented with real locking (not unlocked recount).
