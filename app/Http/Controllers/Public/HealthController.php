<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;

class HealthController extends Controller
{
    public function __invoke()
    {
        $dbOk = false;
        try {
            $dbOk = Schema::hasTable('restaurants');
        } catch (\Throwable) {
            $dbOk = false;
        }

        return response()->json([
            'status' => $dbOk ? 'ok' : 'degraded',
            'db' => $dbOk,
        ]);
    }
}
