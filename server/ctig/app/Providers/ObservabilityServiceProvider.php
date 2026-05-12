<?php

namespace App\Providers;

use DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class ObservabilityServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        DB::listen(function ($query) {
            if ($query->time > 200) {
                Log::warning('Slow query', [
                    'sql' => $query->sql,
                    'time' => $query->time,
                ]);
            }
        });
    }
}
