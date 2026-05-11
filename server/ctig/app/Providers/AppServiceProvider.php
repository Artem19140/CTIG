<?php

namespace App\Providers;

use App\Models\Attempt;
use App\Models\Exam;
use App\Models\ForeignNational;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Gate;
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
        
        Gate::define('attempt-access', function (ForeignNational $foreignNational, Attempt $attempt){
            return $foreignNational->id === $attempt->foreign_national_id;
        });

        Relation::enforceMorphMap([
            'foreign_national' => ForeignNational::class,
            'user' => User::class,
        ]);

        Gate::define('exam-examiner-access', function (User $user, Exam $exam){
            if($user->isSuperAdmin()){
                return true;
            }
            return $exam->examiners()->where('examiner_id', $user->id)->first();
        });

        Gate::define('attempt-examiner-access', function (User $user, Attempt $attempt){
            if($user->isSuperAdmin()){
                return true;
            }
            return $attempt->exam()
                ->whereHas('examiners', function(Builder $query) use($user){
                    $query->where('examiner_id', $user->id);
                })->exists();
        });

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
