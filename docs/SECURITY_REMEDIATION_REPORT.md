# Security Remediation Report (FIX-1–FIX-15)

**Date:** 2026-09-21  
**Scope:** Controlled audit remediation only (no CRM redesign, no lifecycle/subscription changes, no production migrate/deploy executed).

---

## 1. Files changed (high level)

### Laravel app
- Super Admin: migrations `2026_09_21_160000_*`, `2026_09_21_170000_fix_super_admin_bootstrap.php`, `PromoteSuperAdminCommand`, `Admin` fillable, `EnsureSuperAdmin`
- Email verify/change: `ManagerEmailVerificationController` (GET confirm → POST), Blade confirm/invalid views, `ManagerEmailChangeService`, `ManagerEmailVerificationService` (quota on accepted dispatch), Profile/Settings controllers
- Leads/CORS/consent: `LeadCaptureController`, `ContactLeadService`, `ConsentService`, `ContactPayload`, `HubSpotProvider`, `ApiJsonResponse`, `config/cors.php`, `config/resmenu.php`, `routes/api.php`, `routes/web.php`, `routes/manager.php`
- Gate: customization POST + Settings restaurant branding behind email verification
- Phone: `PhoneNormalizer`, Register + lead capture
- Encryption: `CrmEncryption`, CRM settings/service use dedicated key with payment-key decrypt fallback
- HubSpot script: `HubSpotTrackingConfig` HTTPS

### Marketing site (`resmenu.net`)
- `newsletter-popup.php`, `contact.php`: CAPTCHA + honeypot
- `hubspot.php`: HTTPS script URL
- `config.php`: `RECAPTCHA_SITE_KEY`

### Docs / env / tests
- `docs/manager-email-verification-gate.md`, `docs/signed-urls-https.md`, CRM/HubSpot doc updates
- `.env.example` keys for CORS, CRM encryption, Super Admin bootstrap, lead limits
- Tests: `SecurityRemediationTest`, `PhoneNormalizerTest`, `ContactLeadConsentRetryTest`, `ManagerEmailChangeServiceTest`, gate tests; syntax fix in `PlanVisibilityServiceTest`

---

## 2. Fixes ↔ findings

| Fix | Finding | Status |
|-----|---------|--------|
| FIX-1 | HIGH-1 Super Admin blanket | Done — demote all; promote via env/command only |
| FIX-2 | HIGH-2 Email change keeps verified | Done — clears verify + resend |
| FIX-3 | HIGH-3 Lead abuse | Done — CAPTCHA, honeypot, rate limits, source allowlist |
| FIX-4 | HIGH-3 CORS `*` | Done — allowlist + Origin reflection; `config/cors.php` no `*` |
| FIX-5 | HIGH-4 Unsubscribe overwrite on retry | Done — `should_subscribe` only on fresh consent |
| FIX-6 | MEDIUM duplicate leads | Done — email+source+15m dedup |
| FIX-7 | MEDIUM HubSpot blank overwrite | Done — merge-safe non-empty props |
| FIX-8 | MEDIUM/LOW fillable privilege | Done — `is_super_admin` / `email_verified_at` not fillable |
| FIX-9 | HIGH-5 Presentation gate | Done — customization POST + restaurant branding |
| FIX-10 | MEDIUM weak phone | Done — E.164-compatible normalizer |
| FIX-11 | MEDIUM GET prefetch | Done — GET page → POST confirm |
| FIX-12 | INFO HTTPS ops | Done — docs |
| FIX-13 | MEDIUM resend vs queue | Done — hit quota on accepted dispatch; suppression hard fail |
| FIX-14 | LOW shared encryption key | Done — `CRM_ENCRYPTION_KEY` + fallback decrypt |
| FIX-15 | LOW protocol-relative script | Done — `https://js.hs-scripts.com/...` |

---

## 3. Remaining findings / ops gaps

- Local MySQL was unavailable during this pass — DB-backed tests skipped (13). Re-run full suite against `sigsolmenu_resmenu_laravel` before deploy.
- Production must set: `CORS_ALLOWED_ORIGINS`, `SUPER_ADMIN_BOOTSTRAP_USERNAME` (or run `admins:promote-super`), `CRM_ENCRYPTION_KEY`, matching `RECAPTCHA_*` on Laravel + `RECAPTCHA_SITE_KEY` on resmenu.net, `APP_URL` + `TRUST_PROXY_HEADERS` for signed HTTPS links.
- Migrations `2026_09_21_160000` / `170000` must be applied on each environment (not executed here).

---

## 4. Security Q&A

- **Can a normal admin open CRM?** No — `EnsureSuperAdmin` → 403; flag not mass-assignable; no self-promote UI.
- **Does email change keep menu write access?** No — verification cleared until new link confirmed.
- **Can `/api/leads` spoof `registration`?** No — public allowlist only.
- **Does CRM retry re-SUBSCRIBE?** No — `should_subscribe=false` on retry.
- **CORS `*` on leads?** No — when allowlist configured; empty allowlist omits Allow-Origin.

---

## 5. Tests + results

```text
php vendor/phpunit/phpunit/phpunit \
  tests/Unit/PhoneNormalizerTest.php \
  tests/Unit/ContactLeadConsentRetryTest.php \
  tests/Unit/ManagerEmailChangeServiceTest.php \
  tests/Unit/ContactLeadServiceTest.php \
  tests/Unit/ManagerEmailVerificationServiceTest.php \
  tests/Feature/SecurityRemediationTest.php \
  tests/Feature/ManagerEmailVerifiedMenuGateTest.php

OK, but some tests were skipped!
Tests: 28, Assertions: 33, Skipped: 13.
```

Skipped = no local MySQL. Non-DB cases (phone normalize, CORS, honeypot, source rejection, same-email noop) passed.

---

## 6. Deploy decision

# DO NOT DEPLOY

Blockers for production cutover are **operational**, not unfinished code paths:

1. Apply CRM/consent/super-admin migrations on staging/prod DB (after backup).
2. Set env: Super Admin bootstrap or `admins:promote-super`, CORS allowlist, CRM encryption key, reCAPTCHA on both hosts, HTTPS `APP_URL` / trusted proxies.
3. Re-run the security test set with DB up; smoke CRM lead + magic-link + menu gate + Super Admin CRM UI.
4. Confirm HubSpot HTTPS script loads once on resmenu.net.

No production migrate/deploy was run in this remediation pass (per plan).

---

## 7. Deploy prerequisites (when clear)

1. Staging migrate + seed smoke  
2. Promote Super Admin  
3. Configure CORS + reCAPTCHA + CRM key  
4. Verify signed magic-link over HTTPS  
5. Then production migrate + deploy with rollback plan  
