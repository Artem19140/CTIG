<?php

use App\Exceptions\BaseException;
use App\Exceptions\BusinessException;
use App\Http\Middleware\EnsureCenterActive;
use App\Http\Middleware\EnsurePasswordChange;
use App\Http\Middleware\EnsureUserActive;
use App\Http\Middleware\EnsureUserHasAnyRole;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Laravel\Sanctum\Http\Middleware\CheckAbilities;
use Laravel\Sanctum\Http\Middleware\CheckForAnyAbility;
use App\Http\Middleware\HandleInertiaRequests;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        api: __DIR__.'/../routes/api.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'abilities' => CheckAbilities::class,
            'ability' => CheckForAnyAbility::class,
        ]);
    
        $middleware->web(append: [
            HandleInertiaRequests::class,
        ]);

        $middleware->alias([
            'password.change' => EnsurePasswordChange::class,
            'user.active' => EnsureUserActive::class,
            'center.active' => EnsureCenterActive::class,
            'user.has.any.role' => EnsureUserHasAnyRole::class
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions
            ->render(function (BaseException $e, Request $request) {
                if($request->expectsJson()){
                    return response()->json([
                        'message' => $e->getMessage()
                    ], $e->getCode());
                }

                if($request->inertia()){
                    return Inertia::flash('error', $e->getMessage())->back();
                }
               
                return back()->with('error', $e->getMessage());
            });
    
    })->create();
