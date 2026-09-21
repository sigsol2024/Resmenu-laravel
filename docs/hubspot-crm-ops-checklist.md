# HubSpot CRM ops checklist (Resmenu)

Do this in the HubSpot account. Resmenu only stores Portal ID, encrypted Private App token, and subscription type ID.

## 1. Private App
1. Development → Legacy apps → Private apps → Create
2. Scopes: CRM contacts read/write; communication preferences / subscription status read-write
3. Copy access token into Admin → CRM → Private App token

## 2. Portal ID
1. Settings → Account Setup → Account defaults (or Tracking code install page)
2. Copy Hub ID / Portal ID into Admin → CRM

## 3. Marketing subscription type
1. Marketing → Email → Subscription types (or Communication preferences)
2. Create the newsletter type used for Resmenu opt-ins
3. Copy numeric subscription type ID into Admin → CRM

## 4. Live Chat (required for widget)
1. Service → Chatflows → Create website live chat
2. Target `https://resmenu.net` (and paths as needed)
3. Assign inbox / agents / hours
4. Turn chatflow **ON**
5. In Resmenu Admin → CRM: enable Website tracking + Live chat

Without an enabled, targeted chatflow, the tracking script alone will not show chat.

## 5. Website tracking
1. Enable Website tracking in Resmenu CRM settings
2. Confirm `https://js.hs-scripts.com/{portalId}.js` loads once on resmenu.net (no duplicate tracking/chat scripts)
3. Optional: use HubSpot cookie banner or first-party consent with `doNotTrack`

## 6. Marketing campaigns
Create and send newsletters / sequences **inside HubSpot**. Resmenu only syncs contacts + consent.

## 7. Bounce webhook (existing Resmenu)
Keep posting hard bounces to `/api/webhooks/email-suppression` with `X-Webhook-Secret` = `REG_OTP_BOUNCE_WEBHOOK_SECRET`. Magic-link verification reuses this suppression table via `EmailSuppressionService` / `MailService`.
