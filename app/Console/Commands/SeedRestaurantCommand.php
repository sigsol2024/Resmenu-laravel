<?php

namespace App\Console\Commands;

use Database\Seeders\SeedSqlSeeder;
use Illuminate\Console\Command;

class SeedRestaurantCommand extends Command
{
    protected $signature = 'resmenu:seed:restaurant
                            {file : Seed file name or path under database/seed-sql/restaurants/}';

    protected $description = 'Run a single restaurant seed-sql file';

    public function handle(SeedSqlSeeder $seeder): int
    {
        $file = $this->argument('file');
        if (! str_contains($file, '/')) {
            $file = 'restaurants/'.$file;
        }
        if (! str_ends_with($file, '.sql')) {
            $file .= '.sql';
        }

        try {
            $seeder->runFile($file, useTransaction: false);
        } catch (\Throwable $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        $this->info('Restaurant seed completed.');

        return self::SUCCESS;
    }
}
