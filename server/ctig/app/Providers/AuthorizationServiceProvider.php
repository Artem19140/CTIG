<?php

namespace App\Providers;

use App\Models\Attempt;
use App\Models\ForeignNational;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthorizationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }
    public function boot(): void
    {
        Gate::define('attempt-access', function (ForeignNational $foreignNational, Attempt $attempt){
            return $foreignNational->id === $attempt->foreign_national_id;
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
    }
}
