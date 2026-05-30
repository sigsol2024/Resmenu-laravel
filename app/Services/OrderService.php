<?php

namespace App\Services;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public static function statusLabel(string $status): string
    {
        return match ($status) {
            'pending' => 'Pending payment',
            'confirmed' => 'Paid',
            'on_hold' => 'Processing',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            default => ucfirst($status),
        };
    }

    /** @return array<string, int> */
    public function countByStatus(int $restaurantId): array
    {
        $rows = Order::query()
            ->where('restaurant_id', $restaurantId)
            ->select('status', DB::raw('COUNT(*) as cnt'))
            ->groupBy('status')
            ->pluck('cnt', 'status');

        $out = [];
        foreach (Order::STATUSES as $status) {
            $out[$status] = (int) ($rows[$status] ?? 0);
        }

        return $out;
    }

    public function revenueTotal(int $restaurantId): float
    {
        return (float) Order::query()
            ->where('restaurant_id', $restaurantId)
            ->whereIn('status', ['pending', 'confirmed', 'on_hold', 'completed'])
            ->sum('total');
    }

    /** @return Collection<int, Order> */
    public function recent(int $restaurantId, int $limit = 5): Collection
    {
        return Order::query()
            ->where('restaurant_id', $restaurantId)
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    /** @return array<string, int> */
    public function countByStatusBetween(int $restaurantId, Carbon $from, Carbon $to): array
    {
        $rows = Order::query()
            ->where('restaurant_id', $restaurantId)
            ->whereBetween('created_at', [$from, $to])
            ->select('status', DB::raw('COUNT(*) as cnt'))
            ->groupBy('status')
            ->pluck('cnt', 'status');

        $out = [];
        foreach (Order::STATUSES as $status) {
            $out[$status] = (int) ($rows[$status] ?? 0);
        }

        return $out;
    }

    public function revenueBetween(int $restaurantId, Carbon $from, Carbon $to): float
    {
        return (float) Order::query()
            ->where('restaurant_id', $restaurantId)
            ->whereIn('status', ['pending', 'confirmed', 'on_hold', 'completed'])
            ->whereBetween('created_at', [$from, $to])
            ->sum('total');
    }

    /** @return list<array{date: string, revenue: float}> */
    public function revenueByDate(int $restaurantId, string $range = 'all'): array
    {
        [$from, $to] = $this->resolveRange($range);

        $query = Order::query()
            ->where('restaurant_id', $restaurantId)
            ->whereIn('status', ['pending', 'confirmed', 'on_hold', 'completed'])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COALESCE(SUM(total), 0) as revenue'))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date');

        if ($from) {
            $query->where('created_at', '>=', $from);
        }
        if ($to) {
            $query->where('created_at', '<=', $to);
        }

        return $query->get()->map(fn ($row) => [
            'date' => (string) $row->date,
            'revenue' => (float) $row->revenue,
        ])->all();
    }

    /** @return array{0: ?Carbon, 1: ?Carbon} */
    private function resolveRange(string $range): array
    {
        $now = now();
        $todayEnd = $now->copy()->endOfDay();

        return match ($range) {
            'today' => [$now->copy()->startOfDay(), $todayEnd],
            '2days' => [$now->copy()->subDays(2)->startOfDay(), $todayEnd],
            '3days' => [$now->copy()->subDays(3)->startOfDay(), $todayEnd],
            '7days' => [$now->copy()->subDays(7)->startOfDay(), $todayEnd],
            '1month' => [$now->copy()->subMonth()->startOfDay(), $todayEnd],
            default => [null, null],
        };
    }
}
