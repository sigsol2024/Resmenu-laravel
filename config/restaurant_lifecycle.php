<?php

/**
 * Restaurant auto-suspend / purge policy (documented after activity-signal audit).
 *
 * Available signals inspected:
 * - managers.last_login_at (added; updated on successful manager login)
 * - restaurants.last_activity_at (canonical lifecycle clock)
 * - orders.created_at, table_reservations.created_at, menu/section/category updates
 * - payments / subscriptions timestamps
 *
 * Chosen rule (NOT login-only):
 * - Touch restaurants.last_activity_at on manager login, menu writes, orders, and reservations.
 * - New restaurants get last_activity_at = now() at create (migration backfills existing rows).
 * - Auto-suspend when suspended_at IS NULL AND last_activity_at <= now() - 30 days.
 * - Never treat NULL last_activity_at as expired (NULL rows are skipped until backfilled).
 * - Permanent purge when suspended_at IS NOT NULL AND suspended_at <= now() - 7 days.
 */
return [
    'inactivity_days' => 30,
    'purge_days_after_suspend' => 7,
    'reasons' => [
        'admin' => 'admin',
        'inactivity' => 'inactivity',
    ],
];
