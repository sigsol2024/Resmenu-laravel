# Signed URLs, HTTPS, and trusted proxies

Magic-link verification uses Laravel `temporarySignedRoute`. Signatures incorporate the absolute URL, so production must generate **https** links that match what browsers hit.

## Required production settings

1. `APP_URL=https://your-app-host` (no trailing slash mismatch with the live host)
2. `TRUST_PROXY_HEADERS=true` only when the app sits behind a trusted reverse proxy/load balancer that sets `X-Forwarded-Proto` / `X-Forwarded-For`
3. With those set, `AppServiceProvider` calls `URL::forceScheme('https')` in production so signed links are HTTPS

## Do not

- Blindly trust forwarded headers on a host exposed directly to the internet
- Serve the app on HTTP while `APP_URL` is HTTPS (or the reverse) — signatures and cookies will break

## Related

- Verification flow: GET signed link → confirmation page → POST confirm (`manager.verification.verify` / `manager.verification.confirm`)
- Resend is `auth:manager` only and rate-limited; suppressed addresses are a hard fail
