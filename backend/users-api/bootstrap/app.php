<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\HandleCors;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use App\Http\Middleware\IsAdmin;
use Illuminate\Routing\Middleware\SubstituteBindings;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
     // 1) Middleware globaux (toutes requêtes)
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(HandleCors::class);
    })
    // 2) Middleware du groupe "api" (routes/api.php)
    ->withMiddleware(function ($middleware) {
        $middleware->api(prepend: [
            EnsureFrontendRequestsAreStateful::class, // Sanctum SPA
            'throttle:sanctum',                       // rate-limit Sanctum
            SubstituteBindings::class                 // route-model binding
        ]);
    })
        // 3) Déclarer des alias pour vos middleware de route
    ->withMiddleware(function ($middleware) {
        $middleware->alias([
            'admin' => \App\Http\Middleware\IsAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
