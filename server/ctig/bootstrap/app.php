<?php

use App\Http\Middleware\EnsureCenterActive;
use App\Http\Middleware\EnsurePasswordChange;
use App\Http\Middleware\EnsureUserActive;
use App\Http\Middleware\EnsureUserHasAnyRole;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\HandleInertiaRequests;


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
            'password.change' => EnsurePasswordChange::class,
            'user.active' => EnsureUserActive::class,
            'center.active' => EnsureCenterActive::class,
            'user.has.any.role' => EnsureUserHasAnyRole::class
        ]);

        $middleware->redirectUsersTo('/me');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
    
    })->create();
