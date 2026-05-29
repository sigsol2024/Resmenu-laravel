# UI Parity Checklist (Legacy → Laravel)

Source of truth: `Resmenu/` legacy PHP. Laravel must match **DOM structure, class names, columns, modals, tabs, JS hooks** exactly.

Assets: `public/legacy/assets/` (copied from `Resmenu/assets/`). Shell CSS: `public/legacy/css/admin-shell.css`, `manager-shell.css`.

## Verification per page
1. DOM diff legacy HTML vs Laravel HTML
2. Visual check at 375px / 768px / 1280px
3. Action dropdowns, modals, sidebar mobile/collapse
4. User approval before next page

## Admin

| Legacy | Laravel route | Laravel view | Status |
|--------|---------------|--------------|--------|
| `admin/dashboard.php` | `admin.dashboard` | `admin/dashboard.blade.php` | pending |
| `admin/restaurants.php` | `admin.restaurants.index` | `admin/restaurants/index.blade.php` | pending |
| `admin/restaurant-view.php` | `admin.restaurants.hub` | `admin/restaurants/hub.blade.php` | pending |
| `admin/subscription-plans.php` | `admin.subscription-plans.index` | `admin/subscription-plans/index.blade.php` | pending |
| `admin/subscriptions.php` | `admin.subscriptions.index` | `admin/subscriptions/index.blade.php` | pending |
| `admin/payments.php` | `admin.payments.index` | `admin/payments/index.blade.php` | pending |
| `admin/payment-settings.php` | `admin.payment-settings.index` | `admin/payment-settings/index.blade.php` | pending |
| `admin/templates.php` | `admin.templates.index` | `admin/templates/index.blade.php` | pending |
| `admin/qr-templates.php` | `admin.qr-templates.index` | `admin/qr-templates/index.blade.php` | pending |
| `admin/settings.php` | `admin.settings.index` | `admin/settings/index.blade.php` | pending |
| `admin/login.php` | `admin.login` | `admin/login.blade.php` | pending |

## Manager

| Legacy | Laravel route | Laravel view | Status |
|--------|---------------|--------------|--------|
| `manager/dashboard.php` | `manager.dashboard` | `manager/dashboard.blade.php` | pending |
| `manager/sections.php` | manager sections | `manager/sections/index.blade.php` | pending |
| `manager/categories.php` | manager categories | `manager/categories/index.blade.php` | pending |
| `manager/menu-items.php` | manager menu-items | `manager/menu-items/index.blade.php` | pending |
| `manager/customization.php` | manager customization | `manager/customization/index.blade.php` | pending |
| `manager/orders.php` | manager orders | `manager/orders/index.blade.php` | pending |
| `manager/restaurant-orders.php` | manager orders list | `manager/orders/list.blade.php` | pending |
| `manager/reservations.php` | manager reservations | `manager/reservations/index.blade.php` | pending |
| `manager/restaurant-reservations.php` | reservations list | `manager/reservations/list.blade.php` | pending |
| `manager/table-inventory.php` | table inventory | `manager/table-inventory/index.blade.php` | pending |
| `manager/qr-code.php` | manager QR | `manager/qr/code.blade.php` | pending |
| `manager/qr-analytics.php` | QR analytics | `manager/qr/analytics.blade.php` | pending |
| `manager/billing.php` | billing | `manager/billing/index.blade.php` | pending |
| `manager/checkout.php` | checkout | `manager/billing/checkout.blade.php` | pending |
| `manager/transaction-history.php` | transactions | `manager/billing/transactions.blade.php` | pending |
| `manager/payment-settings.php` | payment settings | `manager/billing/payment-settings.blade.php` | pending |
| `manager/settings.php` | settings | `manager/settings.blade.php` | pending |
| `manager/profile.php` | profile | `manager/profile.blade.php` | pending |

## Public / Auth

| Legacy | Laravel route | Laravel view | Status |
|--------|---------------|--------------|--------|
| `index.php` (login) | `login` | `auth/login.blade.php` | pending |
| `register.php` | `register` | `auth/register.blade.php` | pending |
| `forgot-password.php` | `password.request` | `auth/forgot-password.blade.php` | pending |
| `reset-password.php` | `password.reset` | `auth/reset-password.blade.php` | pending |
| `checkout.php` | `public.checkout` | `public/checkout.blade.php` | pending |
| `order-confirmation.php` | `public.order.confirmation` | `public/order-confirmation.blade.php` | pending |
| `reservation.php` | `public.reservation` | `public/reservation.blade.php` | pending |
| `reservation-confirmation.php` | `public.reservation.confirmation` | `public/reservation-confirmation.blade.php` | pending |
| `bank-transfer-pending.php` | `public.bank-transfer-pending` | `public/bank-transfer-pending.blade.php` | pending |
| `payment-failed.php` | `public.payment-failed` | `public/payment-failed.blade.php` | pending |
| `faq.php` | `public.faq` | `public/marketing/faq.blade.php` | pending |
| `contact.php` | `public.contact` | `public/marketing/contact.blade.php` | pending |
| `terms.php` | `public.terms` | `public/marketing/terms.blade.php` | pending |
| `restaurants-list.php` | `public.restaurants-list` | `public/marketing/restaurants-list.blade.php` | pending |
| `templates.php` | `public.templates` | `public/marketing/templates.blade.php` | pending |
| `restaurant.php` | `public.menu` | PHP templates 1–18 | pending |

## Menu templates (1–18)

Rendered via `MenuTemplateRenderService` from `resources/views/menu/php-templates/template{N}/index.php`.
Static assets: `public/templates/template{N}/`.
