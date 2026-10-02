<?php

namespace App\Console\Commands;

use App\Models\Restaurant;
use App\Services\DisplayOrderService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * One-off repair for menus saved before positions were kept dense. Keeps the
 * current visible order (display_order, then id) and renumbers each group 1..n.
 */
class ResequenceDisplayOrders extends Command
{
    protected $signature = 'menu:resequence-display-orders
        {restaurant? : Restaurant id (omit with --all)}
        {--all : Every restaurant}
        {--dry-run : Report how many rows would change without saving}';

    protected $description = 'Renumber section, category and menu item positions to 1, 2, 3 within each group';

    public function handle(DisplayOrderService $displayOrders): int
    {
        $restaurantId = $this->argument('restaurant');
        if (! $this->option('all') && ($restaurantId === null || ! ctype_digit((string) $restaurantId))) {
            $this->error('Pass a restaurant id or --all.');

            return self::FAILURE;
        }

        $ids = $this->option('all')
            ? Restaurant::query()->orderBy('id')->pluck('id')->map(fn ($id) => (int) $id)->all()
            : [(int) $restaurantId];

        $dryRun = (bool) $this->option('dry-run');
        $total = 0;

        foreach ($ids as $id) {
            DB::beginTransaction();
            try {
                $changed = $displayOrders->resequenceRestaurant($id);
                $dryRun ? DB::rollBack() : DB::commit();
            } catch (\Throwable $e) {
                DB::rollBack();
                throw $e;
            }

            if ($changed > 0) {
                $this->line("Restaurant {$id}: {$changed} row(s) ".($dryRun ? 'would change' : 'renumbered'));
            }
            $total += $changed;
        }

        if (! $dryRun) {
            Log::info('Display orders resequenced', ['restaurants' => count($ids), 'rows' => $total]);
        }
        $this->info(($dryRun ? 'Dry run: ' : '')."{$total} row(s) across ".count($ids).' restaurant(s).');

        return self::SUCCESS;
    }
}
