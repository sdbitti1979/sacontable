<?php

use Carbon\Laravel\ServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use NunoMaduro\Collision\Provider;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withProviders([
        // Agregar directamente los proveedores en un array
        \App\Providers\AppServiceProvider::class,       
    ])
    ->withMiddleware(function (Middleware $middleware) {
        //$middleware->append(\App\Http\Middleware\CargarRolesYPermisos::class);
        $middleware->append(\App\Http\Middleware\NoCacheHeaders::class);
        
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
