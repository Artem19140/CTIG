<?php

namespace App\Providers;

use App\Models\Attempt;
use App\Models\Exam;
use App\Models\ForeignNational;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Database\Eloquent\Model;

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
        Gate::define('attempt-access', function (ForeignNational $foreignNational, Attempt $attempt){
            return $foreignNational->id === $attempt->foreign_national_id;
        });

        Gate::define('exam-manage-access', function (User $user, Exam $exam){
            return $exam->examiners()->where('examiner_id', $user->id)->first();;
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
