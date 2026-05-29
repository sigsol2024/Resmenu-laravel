# Resmenu Laravel parity audit (updated)

## Summary

| Area | Estimate |
|------|----------|
| Core API & business logic | ~95% |
| Manager / admin UX | ~88% |
| Public reservation & menu | ~90% |
| Email & registration polish | ~85% |

## Completed in latest pass

- **Public reservation**: Full legacy 4-step wizard (`reservation-booking.blade.php` + `reservation-wizard.js`), calendar, occasions, deposit redirect, feature gating.
- **Styled QR**: Endroid via `QrGeneratorService`, `QrImageController`, manager/admin routes; removed qrserver for main/section downloads.
- **Admin restaurant hub**: `RestaurantHubController` + tabbed manage view (menu CRUD, customization, header/footer).
- **Table inventory**: Manager UI + `table-inventory.js` wired to `/api/table-inventory`.
- **Transactional emails**: `RestaurantTransactionalMailService` for orders/reservations (create + status).
- **Registration MX**: User-facing deliverability messages on OTP send.
- **QR CSV export**: `manager.qr.analytics.export`.
- **Dashboard usage bars**: Plan limits for categories, menu items, QR styles.

## Remaining (lower priority)

- Admin hub: inline **edit** forms for existing categories/items (create/delete only today).
- Marketing CMS pages (legacy admin content editor).
- Admin QR template live preview page (legacy `qr-template-preview.php`).
- Full parity of every email HTML template pixel-match to legacy `email-templates-restaurant.php`.

## Database

See [DATABASE_PARITY.md](DATABASE_PARITY.md) for production dump import vs seed-sql paths and slug fixes.

## Deploy notes

```bash
php artisan migrate
php artisan route:clear
php artisan config:clear
```

Cron: `php artisan subscriptions:send-reminders` daily.
