<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        //\Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        //\Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        //\Illuminate\Session\Middleware\StartSession::class,
       // \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        //\App\Http\Middleware\TrustProxies::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            //\App\Http\Middleware\EncryptCookies::class,
           // \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
           // \Illuminate\Session\Middleware\StartSession::class,
           // \Illuminate\View\Middleware\ShareErrorsFromSession::class,
           // \App\Http\Middleware\VerifyCsrfToken::class,
          //  \Illuminate\Routing\Middleware\SubstituteBindings::class,
            //\App\Http\Middleware\LoadUserData::class,
            \App\Http\Middleware\CargarRolesYPermisos::class,

        ],

        /*'api' => [
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],*/
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        //'auth' => \App\Http\Middleware\Authenticate::class,
        //'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        //'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        //'can' => \Illuminate\Auht\Middleware\Authorize::class,
        //'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        //'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
       // 'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
       // 'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
    ];
}
