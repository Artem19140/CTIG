<?php

namespace App\Providers;

use App\Models\Attempt;
use App\Models\Student;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Access\Response;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('attempt-access', function (Student $student, Attempt $attempt){
            return $student->id === $attempt->student_id;
        });

        Model::preventLazyLoading(
            !app()->environment('production')
        );
        Model::preventSilentlyDiscardingAttributes();
        Model::preventAccessingMissingAttributes();
        RateLimiter::for('api', function (Request $request) {
        return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
    });
    }
}
