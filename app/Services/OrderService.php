<?php

namespace App\Services;

use App\Models\Order;
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
}
