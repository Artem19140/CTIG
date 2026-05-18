<?php

namespace App\Providers;

use App\Models\Attempt;
use App\Models\Employee;
use App\Models\ForeignNational;
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

        Gate::before(function (Employee $employee, string $ability) {
        if ($employee->isSuperAdmin()) {
            return true;
        }
        return null;
    });

    }
}
