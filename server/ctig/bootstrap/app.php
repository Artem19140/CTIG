<?php

use App\Http\Middleware\CenterContext;
use App\Http\Middleware\EnsureCenterActive;
use App\Http\Middleware\EnsurePasswordChange;
use App\Http\Middleware\EnsureEmployeeActive;
use App\Http\Middleware\EnsureEmployeeHasAnyRole;
use App\Http\Middleware\EnsureValidAttemptStatus;
use App\Http\Middleware\LogContext;
use App\Http\Middleware\RequestTimeMeasure;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\HandleInertiaRequests;
use App\Support\AppMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        api: __DIR__.'/../routes/api.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            HandleInertiaRequests::class,
        ]);

        $middleware->alias([
            AppMiddleware::HAS_CHANGE_PASSWORD => EnsurePasswordChange::class,
            AppMiddleware::EMPLOYEE_ACTIVE => EnsureEmployeeActive::class,
            AppMiddleware::CENTER_ACTIVE => EnsureCenterActive::class,
            AppMiddleware::EMPLOYEE_HAS_ANY_ROLE => EnsureEmployeeHasAnyRole::class,
            AppMiddleware::LOG_CONTEXT => LogContext::class,
            AppMiddleware::REQUEST_TIME_MEASURE => RequestTimeMeasure::class,
            AppMiddleware::CENTER_CONTEXT => CenterContext::class,
            AppMiddleware::ENSURE_ATTEMPT_VALID_STATUS => EnsureValidAttemptStatus::class
        ]);

        $middleware->redirectUsersTo('/me');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
    
    })->create();
