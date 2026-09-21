# Manager email verification gate

Unverified managers can use the dashboard, billing, orders, reservations, QR analytics, account/password settings, profile email change, and verification resend.

## Requires verified email

- Catalog CRUD (sections / categories / menu items create-update-delete)
- Customization **POST** (template, colors, feature toggles)
- Restaurant branding mutations on Settings → Restaurant tab (name, logo, hero, social, etc.)

## Does not require verified email

- Viewing catalog list pages and customization **GET**
- Account tab (username/email) and password changes
- Billing and payment settings
- Orders / reservations / bank transfers
- QR code + analytics

Email change clears `email_verified_at` and sends a new magic link (`ManagerEmailChangeService`).
