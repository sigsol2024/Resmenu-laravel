<?php

namespace App\Services;

class ReservationSlotService
{
    public function __construct(
        private TableInventoryService $inventory,
        private SubscriptionService $subscriptions,
    ) {}

    /**
     * @return array{slots:list<array{time:string,available:bool,count:int}>, tables_left:int, day_available:bool}
     */
    public function slotsForDate(int $restaurantId, string $date): array
    {
        $availability = $this->inventory->availabilityForDate($restaurantId, $date);
        $tablesLeft = (int) ($availability['available'] ?? 0);

        $open = '17:00';
        $close = '23:00';
        $interval = 30;

        $slots = [];
        $start = strtotime($date.' '.$open);
        $end = strtotime($date.' '.$close);
        $now = time();

        for ($t = $start; $t < $end; $t += $interval * 60) {
            $timeStr = date('H:i', $t);
            $slotDateTime = strtotime($date.' '.$timeStr);
            $isPast = $slotDateTime < $now;
            $available = ! $isPast && $tablesLeft > 0;

            $slots[] = [
                'time' => $timeStr,
                'available' => $available,
                'count' => 0,
            ];
        }

        return [
            'slots' => $slots,
            'tables_left' => $tablesLeft,
            'day_available' => $tablesLeft > 0,
        ];
    }

    /** @return list<array{date:string,available:int,day_available:bool}> */
    public function monthAvailability(int $restaurantId, int $year, int $month): array
    {
        $summary = $this->inventory->monthSummary($restaurantId, $year, $month);

        return array_map(fn ($day) => [
            'date' => $day['date'],
            'available' => (int) ($day['available'] ?? 0),
            'day_available' => ((int) ($day['available'] ?? 0)) > 0,
        ], $summary);
    }

    /**
     * @return array<string, array{available:int, total:int, status:string}>
     */
    public function publicCalendar(int $restaurantId, string $startDate, string $endDate): array
    {
        return app(TableInventoryService::class)->publicCalendarAvailability($restaurantId, $startDate, $endDate);
    }

    public function restaurantCanBook(int $restaurantId): ?string
    {
        if (! $this->subscriptions->isSubscriptionActive($restaurantId)) {
            return 'Subscription required';
        }
        if (! $this->subscriptions->hasFeatureAccess($restaurantId, 'table_reservations')) {
            return 'Table reservations are not available for this restaurant plan.';
        }

        return null;
    }
}
