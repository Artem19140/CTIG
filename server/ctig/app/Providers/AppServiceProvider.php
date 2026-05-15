<?php

namespace App\Providers;

use App\Models\ForeignNational;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Database\Eloquent\Model;
use Inertia\Inertia;
use Inertia\ExceptionResponse;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        
    }
    public function boot(): void
    {
        Relation::enforceMorphMap([
            'foreign_national' => ForeignNational::class,
            'employee' => Employee::class,
        ]);

        Model::preventLazyLoading(
            !app()->environment('production')
        );

        Model::preventSilentlyDiscardingAttributes();
        
        Model::preventAccessingMissingAttributes();

        Inertia::handleExceptionsUsing(function (ExceptionResponse $response) {
            if (in_array($response->statusCode(), [403, 404, 500, 503])) {
                return $response->render('ErrorPage', [
                    'status' => $response->statusCode(),
                ])->withSharedData();
            }
        });

        RateLimiter::for('api', function (Request $request) {
        return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
    });
    }
}
