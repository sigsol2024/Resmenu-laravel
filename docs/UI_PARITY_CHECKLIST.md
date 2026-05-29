# UI Parity Checklist (Legacy → Laravel)

Source of truth: `Resmenu/` legacy PHP. Laravel matches **DOM structure, class names, columns, modals, tabs, JS hooks** from legacy.

## Assets
- Shared: `public/legacy/assets/` (from `Resmenu/assets/`)
- Shell CSS: `public/legacy/css/admin-shell.css`, `manager-shell.css`
- Page CSS: `public/legacy/css/pages/admin-*.css`, `manager-*.css`
- Menu templates: `resources/views/menu/php-templates/template{N}/` + `public/templates/template{N}/`

## Verification
1. DOM diff: `php database/scripts/dom_parity_diff.php --legacy-url=... --laravel-url=...`
2. Screenshots: `node scripts/ui-parity-screenshots.mjs` (Playwright)
3. Visual check at 375px / 768px / 1280px

## Admin

| Legacy | Laravel route | Laravel view | Status |
|--------|---------------|--------------|--------|
| `admin/dashboard.php` | `admin.dashboard` | `admin/dashboard.blade.php` | ported |
| `admin/restaurants.php` | `admin.restaurants.index` | `admin/restaurants/index.blade.php` | ported |
| `admin/restaurant-view.php` | `admin.restaurants.hub` | `admin/restaurants/hub.blade.php` | ported |
| `admin/subscription-plans.php` | `admin.subscription-plans.index` | `admin/subscription-plans/index.blade.php` | ported |
| `admin/subscriptions.php` | `admin.subscriptions.index` | `admin/subscriptions/index.blade.php` | ported |
| `admin/payments.php` | `admin.payments.index` | `admin/payments/index.blade.php` | ported |
| `admin/payment-settings.php` | `admin.payment-settings.index` | `admin/payment-settings/index.blade.php` | ported |
| `admin/templates.php` | `admin.templates.index` | `admin/templates/index.blade.php` | ported |
| `admin/qr-templates.php` | `admin.qr-templates.index` | `admin/qr-templates/index.blade.php` | ported |
| `admin/settings.php` | `admin.settings.index` | `admin/settings/index.blade.php` | ported |
| `admin/login.php` | `admin.login` | `admin/login.blade.php` | ported (redirects to `/login`) |

## Manager

| Legacy | Laravel route | Laravel view | Status |
|--------|---------------|--------------|--------|
| `manager/dashboard.php` | `manager.dashboard` | `manager/dashboard.blade.php` | ported |
| `manager/sections.php` | manager sections | `manager/sections/index.blade.php` | ported |
| `manager/categories.php` | manager categories | `manager/categories/index.blade.php` | ported |
| `manager/menu-items.php` | manager menu-items | `manager/menu-items/index.blade.php` | ported |
| `manager/customization.php` | manager customization | `manager/customization/index.blade.php` | ported |
| `manager/orders.php` | manager orders | `manager/orders/index.blade.php` | ported |
| `manager/restaurant-orders.php` | manager orders list | `manager/orders/list.blade.php` | ported |
| `manager/reservations.php` | manager reservations | `manager/reservations/index.blade.php` | ported |
| `manager/restaurant-reservations.php` | reservations list | `manager/reservations/list.blade.php` | ported |
| `manager/table-inventory.php` | table inventory | `manager/table-inventory/index.blade.php` | ported |
| `manager/qr-code.php` | manager QR | `manager/qr/code.blade.php` | ported |
| `manager/qr-analytics.php` | QR analytics | `manager/qr/analytics.blade.php` | ported |
| `manager/billing.php` | billing | `manager/billing/index.blade.php` | ported |
| `manager/checkout.php` | checkout | `manager/billing/checkout.blade.php` | ported |
| `manager/transaction-history.php` | transactions | `manager/billing/transactions.blade.php` | ported |
| `manager/payment-settings.php` | payment settings | `manager/billing/payment-settings.blade.php` | ported |
| `manager/settings.php` | settings | `manager/settings.blade.php` | ported |
| `manager/profile.php` | profile | `manager/profile.blade.php` | ported |

## Public / Auth

| Legacy | Laravel route | Laravel view | Status |
|--------|---------------|--------------|--------|
| `index.php` (login) | `login` | `auth/login.blade.php` | ported |
| `register.php` | `register` | `auth/register.blade.php` | ported |
| `forgot-password.php` | `password.request` | `auth/forgot-password.blade.php` | ported |
| `reset-password.php` | `password.reset` | `auth/reset-password.blade.php` | ported |
| `checkout.php` | `public.checkout` | `public/checkout.blade.php` | ported |
| `order-confirmation.php` | `public.order.confirmation` | `public/order-confirmation.blade.php` | ported |
| `reservation.php` | `public.reservation` | `public/reservation.blade.php` | ported |
| `reservation-confirmation.php` | `public.reservation.confirmation` | `public/reservation-confirmation.blade.php` | ported |
| `bank-transfer-pending.php` | `public.bank-transfer-pending` | `public/bank-transfer-pending.blade.php` | ported |
| `payment-failed.php` | `public.payment-failed` | `public/payment-failed.blade.php` | ported |
| Marketing pages | `public.*` | `public/marketing/*.blade.php` | ported (`layouts.marketing`) |
| `restaurant.php` | `public.menu` | PHP templates 1–18 | ported |

## Menu templates (1–18)

Rendered via `MenuTemplateRenderService` from `resources/views/menu/php-templates/template{N}/index.php`.
Static assets: `public/templates/template{N}/`. Cart/modal assets: `/legacy/assets/css/`.
