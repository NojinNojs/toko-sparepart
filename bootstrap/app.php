<?php

use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\SecurityHeadersMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Alias middleware
        $middleware->alias([
            'role' => RoleMiddleware::class,
        ]);

        // Global middleware: security headers pada setiap response
        $middleware->append(SecurityHeadersMiddleware::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
