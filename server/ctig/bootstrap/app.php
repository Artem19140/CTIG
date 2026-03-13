<?php

use App\Exceptions\BusinessException;
use App\Http\Middleware\CheckOrganizationIsWork;
use App\Http\Middleware\CheckPasswordChange;
use App\Http\Middleware\CheckUserIsWork;
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
            'password.change' => CheckPasswordChange::class,
            'user.is.work' => CheckUserIsWork::class,
            'organization.is.work' => CheckOrganizationIsWork::class
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions
            // ->render(function (BusinessException $e, Request $request) {
            //     if($request->header('X-Inertia')){
            //         return back()->with('error', $e->getMessage());
            //     }
            //     return null;
            // })

            ->shouldRenderJsonWhen(function (Request $request, Throwable $e) {
                if ($request->is('api/*')) {
                    return true;
                }
                return $request->expectsJson();
            });

            // ->respond(function (Response $response) {
            //     if ($response->getStatusCode() === 419) {
            //         return back()->with([
            //             'message' => 'The page expired, please try again.',
            //         ]);
            //     }

            //     return $response;
            // });
    
    })->create();
