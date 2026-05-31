# Mass assignment audit

Audit date: 2026-05-30

## Summary

All Eloquent models use `$fillable` (no empty `$guarded`). No `Model::create($request->all())` patterns remain in controllers.

## Model changes

| Model | Change |
|-------|--------|
| `Order` | Removed `status`, `subtotal`, `delivery_fee`, `tax`, `total` from `$fillable`. Only `OrderSubmissionService` sets these via `forceFill()`. |
| `Subscription` | Removed `status`, `trial_ends_at`, `current_period_*`, `cancelled_at` from `$fillable`. Creation uses `Subscription::forceCreate()` in registration/payment flows. |

## Request handling

| Location | Status |
|----------|--------|
| `CheckoutController` reservation bank transfer | Uses `$request->only([...])` instead of `$request->all()` |
| Manager CRUD controllers | Validated field subsets only |
| API order/reservation endpoints | Explicit customer array construction |

## High-risk fields (never from raw request)

- `Order.status`, pricing columns
- `Subscription.status`, trial/period dates
- `Manager.password_hash`, `Admin.password_hash`
- `Restaurant.is_active`, `template_id` (admin/manager validated paths only)

## Verification command

```bash
rg "request->all\(\)" app/
rg "guarded\s*=\s*\[\]" app/Models
```
