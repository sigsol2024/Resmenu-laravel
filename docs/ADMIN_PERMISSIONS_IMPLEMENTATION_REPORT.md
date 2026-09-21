# Admin Management & Permissions — Implementation Report

## 1. Files created

- `database/migrations/2026_09_21_220000_add_admin_permissions_and_active.php`
- `app/Services/Admin/AdminAccountService.php`
- `app/Http/Middleware/EnsureAdminActive.php`
- `app/Http/Middleware/EnsureAdminPermission.php`
- `app/Http/Controllers/Admin/AdminsController.php`
- `app/Http/Controllers/Admin/ProfileController.php`
- `resources/views/admin/admins/index.blade.php`
- `resources/views/admin/admins/create.blade.php`
- `resources/views/admin/admins/edit.blade.php`
- `resources/views/admin/admins/_form.blade.php`
- `resources/views/admin/admins/_form-scripts.blade.php`
- `resources/views/admin/profile/show.blade.php`
- `tests/Feature/AdminPermissionsTest.php`
- `tests/Unit/AdminPermissionMapTest.php`
- `docs/ADMIN_PERMISSIONS_IMPLEMENTATION_REPORT.md`

## 2. Files modified

- `app/Models/Admin.php`
- `bootstrap/app.php`
- `routes/admin.php`
- `app/Http/Controllers/Admin/SettingsController.php`
- `app/Http/Controllers/Admin/LoginController.php`
- `app/Http/Controllers/Auth/LoginController.php`
- `app/Http/Middleware/EnsureSuperAdmin.php`
- `app/Support/ManagerImpersonation.php`
- `app/View/Composers/AdminLayoutComposer.php`
- `resources/views/partials/admin-sidebar.blade.php`
- `resources/views/admin/settings/index.blade.php`
- `tests/Feature/SecurityRemediationTest.php`

## 3. Migration name

`2026_09_21_220000_add_admin_permissions_and_active`

## 4. Database columns added

- `is_active` (boolean, default `true`)
- `can_subscription_plans`
- `can_subscriptions`
- `can_payments`
- `can_payment_settings`
- `can_templates`
- `can_qr_templates`
- `can_settings`
- `can_crm`

All `can_*` default `false`. `is_super_admin` was not modified. No blanket grants.

## 5. Middleware created

- `admin.active` → `EnsureAdminActive`
- `admin.permission:{key}` → `EnsureAdminPermission`
- `super.admin` retained for `admins.*` (also checks active)

## 6. Service created

`App\Services\Admin\AdminAccountService` — create/update/delete with primary, self, and zero-active-Super guards.

## 7. Routes changed

Permission middleware applied to plans, subscriptions, payments, payment-settings, templates, qr-templates, settings, crm.  
New: `admin.admins.*` (Super only), `admin.profile.show|update`.  
Baseline (auth + active only): dashboard, restaurants, impersonation, profile, logout.

## 8. Sidebar changes

- Nav filtered via `hasPermission()` / Super-only Administrators
- Logo subtitle from `isSuperAdmin()` → “Super Admin” / “Administrator”
- Profile card + Profile link above Logout

## 9. Profile changes

Dedicated `/admin/profile` for username, email, password. Privilege fields ignored. Session regenerates after password change.

## 10. Admin Management changes

CRUD moved from Settings → `/admin/admins`. Role + eight permission checkboxes for Regular Admins; Full access UI for Super.

## 11. Authentication / inactive-account handling

- Login blocked when `is_active = false` (shared Auth login + Admin login)
- `admin.active` on protected Admin routes; deactivated Admin loses access on next request
- Manager auth untouched
- `user_role` session set to `super_admin` or `admin` for bookkeeping only (not AuthZ)

## 12. Tests added

- `tests/Unit/AdminPermissionMapTest.php`
- `tests/Feature/AdminPermissionsTest.php` (full locked matrix)
- Extended mass-assignment asserts in `SecurityRemediationTest`

## 13. Test results

| Suite | Result |
| ----- | ------ |
| `AdminPermissionMapTest` | **OK** (2 tests, 13 assertions) |
| `AdminPermissionsTest` | **Skipped** (30/30) — MySQL not reachable locally (`SQLSTATE[HY000] [2002] connection refused`). Migration could not be applied in this environment for the same reason. |

Re-run after DB is available:

```bash
php artisan migrate --force --path=database/migrations/2026_09_21_220000_add_admin_permissions_and_active.php
php vendor/phpunit/phpunit/phpunit --filter AdminPermissionsTest
```

## 14. Remaining risks

- **Deploy:** After migrate, existing Regular Admins have all `can_* = false` until a Super Admin grants permissions.
- **Shared DB tests:** Primary/zero-Super cases may skip on shared staging DBs when other active Supers exist.
- **Session `user_role`:** Still stored for impersonation bookkeeping; authorization uses the Admin model only.

## Deploy note

Do **not** guess permissions in a data migration. A Super Admin must open **Administrators** and assign checkboxes after deployment.
