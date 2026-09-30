<?php

namespace App\Support;

/**
 * Lets the browser that made a booking open its reservation confirmation page even when
 * the signed link is unavailable (APP_HMAC_SECRET unset) or the gateway return lost the query.
 */
final class ReservationConfirmationAccess
{
    private const SESSION_KEY = 'reservation_confirmation_access';

    private const MAX_IDS = 20;

    public static function grant(int $reservationId): void
    {
        if ($reservationId <= 0 || ! self::hasSession()) {
            return;
        }

        $ids = array_values(array_filter(
            (array) session()->get(self::SESSION_KEY, []),
            fn ($id) => (int) $id !== $reservationId
        ));
        $ids[] = $reservationId;

        session()->put(self::SESSION_KEY, array_slice($ids, -self::MAX_IDS));
    }

    public static function granted(int $reservationId): bool
    {
        if ($reservationId <= 0 || ! self::hasSession()) {
            return false;
        }

        return in_array($reservationId, array_map('intval', (array) session()->get(self::SESSION_KEY, [])), true);
    }

    /** Signed URL when possible; otherwise the plain URL, which only this session can open. */
    public static function url(int $reservationId, string $slug): string
    {
        self::grant($reservationId);

        $signed = ReservationConfirmationToken::confirmationUrl($reservationId, $slug);

        return $signed !== '' ? $signed : route('public.reservation.confirmation', ['reservation' => $reservationId]);
    }

    private static function hasSession(): bool
    {
        $request = request();

        return $request !== null && $request->hasSession();
    }
}
