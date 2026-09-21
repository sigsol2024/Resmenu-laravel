# CRM architecture (Resmenu)

## Pipeline

```text
Registration / Newsletter / Homepage popup / Contact form
        ↓
ContactLeadService
        ↓
ConsentService (local DB only)
        ↓
CRMService → CRMProviderInterface → HubSpotProvider (Mailchimp stub)
```

Controllers never contain `if ($hubspotEnabled)`.

## Email split

- **MailService**: transactional (magic link, password reset, billing, suspension)
- **HubSpot**: marketing campaigns (managed in HubSpot UI)

## Access layers (do not merge)

1. `EnsureManagerEmailVerified` — catalog writes + customization POST + restaurant branding (see `manager-email-verification-gate.md`)
2. `EnsureActiveSubscription` — trial/billing (unchanged)
3. `EnsureManagerRestaurant` + `RestaurantLifecycleService` — 30-day inactivity suspend (unchanged)

## Lead idempotency

`crm_leads` dedupes by **email + source + 15-minute window** (not global unique email). Different sources stay separate.

## Consent / subscribe

`should_subscribe` is true only when a new true consent event is created in that request. CRM retry never sets it.

## Suppression

Reuse `EmailSuppressionService` + `email_delivery_suppressions` + bounce webhook. No second suppression system.
