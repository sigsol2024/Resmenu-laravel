<?php

namespace App\Services;

use App\Models\TableReservation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TableInventoryService
{
    /** @return array<string, mixed> */
    public function availabilityForDate(int $restaurantId, string $date): array
    {
        $total = $this->totalTablesForDate($restaurantId, $date);

        try {
            $row = DB::table('table_reservations')
                ->where('restaurant_id', $restaurantId)
                ->where('reservation_date', $date)
                ->selectRaw("
                    SUM(CASE WHEN status = 'confirmed' AND COALESCE(is_walkin, 0) = 0 THEN 1 ELSE 0 END) AS confirmed,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) AS pending,
                    SUM(CASE WHEN COALESCE(is_walkin, 0) = 1 THEN 1 ELSE 0 END) AS walkins,
                    SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) AS cancelled
                ")
                ->first();
        } catch (\Throwable) {
            $row = DB::table('table_reservations')
                ->where('restaurant_id', $restaurantId)
                ->where('reservation_date', $date)
                ->selectRaw("
                    SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) AS confirmed,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) AS pending,
                    0 AS walkins,
                    SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) AS cancelled
                ")
                ->first();
        }

        $confirmed = (int) ($row->confirmed ?? 0);
        $pending = (int) ($row->pending ?? 0);
        $walkins = (int) ($row->walkins ?? 0);
        $cancelled = (int) ($row->cancelled ?? 0);
        $booked = $confirmed + $pending + $walkins;
        $available = max(0, $total - $booked);

        return [
            'date' => $date,
            'total' => $total,
            'total_tables' => $total,
            'confirmed' => $confirmed,
            'pending' => $pending,
            'walkins' => $walkins,
            'cancelled' => $cancelled,
            'booked' => $booked,
            'available' => $available,
        ];
    }

    /** @return array<string, array<string, mixed>> */
    public function dateRange(int $restaurantId, string $startDate, string $endDate): array
    {
        $result = [];
        $cursor = strtotime($startDate);
        $end = strtotime($endDate);
        while ($cursor !== false && $end !== false && $cursor <= $end) {
            $d = date('Y-m-d', $cursor);
            $result[$d] = $this->availabilityForDate($restaurantId, $d);
            $cursor = strtotime('+1 day', $cursor);
        }

        return $result;
    }

    /** @return array{availability: array<string, mixed>, reservations: list<object>} */
    public function dayDetail(int $restaurantId, string $date): array
    {
        $availability = $this->availabilityForDate($restaurantId, $date);
        $reservations = DB::table('table_reservations')
            ->where('restaurant_id', $restaurantId)
            ->where('reservation_date', $date)
            ->orderBy('reservation_time')
            ->get();

        return ['availability' => $availability, 'reservations' => $reservations->all()];
    }

    public function setDailyTotal(int $restaurantId, string $date, int $totalTables): void
    {
        DB::table('table_inventory_daily')->updateOrInsert(
            ['restaurant_id' => $restaurantId, 'inventory_date' => $date],
            ['total_tables' => $totalTables, 'updated_at' => now(), 'created_at' => now()],
        );
    }

    public function bulkSet(int $restaurantId, string $startDate, string $endDate, int $totalTables): int
    {
        $count = 0;
        $cursor = strtotime($startDate);
        $end = strtotime($endDate);
        while ($cursor <= $end) {
            $this->setDailyTotal($restaurantId, date('Y-m-d', $cursor), $totalTables);
            $count++;
            $cursor = strtotime('+1 day', $cursor);
        }

        return $count;
    }

    /** @return list<array<string, mixed>> */
    public function monthSummary(int $restaurantId, int $year, int $month): array
    {
        $start = sprintf('%04d-%02d-01', $year, $month);
        $end = date('Y-m-t', strtotime($start));
        $range = $this->dateRange($restaurantId, $start, $end);

        return array_values(array_map(fn ($date, $data) => array_merge(['date' => $date], $data), array_keys($range), $range));
    }

    public function addWalkIn(int $restaurantId, string $date, string $guestName, string $time = '18:00:00'): bool
    {
        if ($this->availabilityForDate($restaurantId, $date)['available'] <= 0) {
            return false;
        }

        if (strlen($time) === 5) {
            $time .= ':00';
        }

        $number = 'W'.strtoupper(Str::random(6));
        DB::table('table_reservations')->insert([
            'restaurant_id' => $restaurantId,
            'reservation_number' => $number,
            'reservation_date' => $date,
            'reservation_time' => $time,
            'party_size' => 1,
            'guest_name' => $guestName ?: 'Walk-in',
            'guest_email' => '',
            'guest_phone' => '',
            'status' => 'confirmed',
            'is_walkin' => 1,
            'deposit_amount' => 0,
            'deposit_paid' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return true;
    }

    /**
     * Public calendar cells with status: past|full|limited|available
     *
     * @return array<string, array{available:int, total:int, status:string}>
     */
    public function publicCalendarAvailability(int $restaurantId, string $startDate, string $endDate): array
    {
        $range = $this->dateRange($restaurantId, $startDate, $endDate);
        $today = date('Y-m-d');
        $dates = [];

        foreach ($range as $dateStr => $data) {
            $available = (int) $data['available'];
            $total = (int) $data['total'];
            if ($dateStr < $today) {
                $status = 'past';
            } elseif ($available <= 0) {
                $status = 'full';
            } elseif ($available < 3) {
                $status = 'limited';
            } else {
                $status = 'available';
            }
            $dates[$dateStr] = ['available' => $available, 'total' => $total, 'status' => $status];
        }

        return $dates;
    }

    private function totalTablesForDate(int $restaurantId, string $date): int
    {
        $row = DB::table('table_inventory_daily')
            ->where('restaurant_id', $restaurantId)
            ->where('inventory_date', $date)
            ->value('total_tables');

        return (int) ($row ?? 10);
    }
}
