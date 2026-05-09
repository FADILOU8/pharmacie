<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Append SecurityHeaders to every HTTP response globally.
        $middleware->append(App\Http\Middleware\SecurityHeaders::class);

        $middleware->alias([
            'role' => App\Http\Middleware\RoleMiddleware::class,
        ]);

        // Rate-limit authentication endpoints to mitigate brute-force attacks.
        // The 'throttle:10,1' limiter on auth routes allows 10 attempts per minute
        // per IP address. This is applied directly in routes/web.php.
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
