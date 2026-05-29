# Resmenu Laravel — Smoke Test Checklist

Run after deploy: `php artisan migrate`, `php artisan db:seed`, verify `APP_URL` and document root `public/`.

## Admin (`/admin`)

- [ ] Login at `/admin/login`
- [ ] Dashboard: stats, chart, recent restaurants
- [ ] Restaurants: list, create, edit, show, delete, search `?q=`
- [ ] Subscription plans: CRUD, toggle
- [ ] Subscriptions: filter, update status/plan, extend days
- [ ] Payments: filter, change status, manual payment
- [ ] Payment settings: save Paystack/Flutterwave keys
- [ ] Templates: list, edit, preview, toggle
- [ ] QR templates: create, edit, delete
- [ ] Settings: site branding, contact fields, test email
- [ ] CSS loads: `/assets/css/admin.css` returns 200

## Manager (`/manager`)

- [ ] Login, dashboard
- [ ] Sections, categories, menu items CRUD
- [ ] Orders and reservations lists
- [ ] Table inventory page + API month load
- [ ] QR code and billing checkout
- [ ] Customization and settings

## Public

- [ ] `/restaurant/{slug}` menu (templates 1–18 spot-check)
- [ ] Checkout and order confirmation
- [ ] Reservation flow
- [ ] `/contact`, `/bank-transfer-pending?token=`, `/payment-failed`
- [ ] `/faq` and `/terms` redirect to resmenu.net

## API / cron

- [ ] `POST /api/webhooks/paystack` (test mode)
- [ ] `php artisan schedule:run` (subscriptions sync + reminders)

## Schema

- [ ] `php artisan resmenu:schema:verify --strict`
- [ ] Double `php artisan db:seed` idempotent
