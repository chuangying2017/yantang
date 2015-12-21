<?php namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

/**
 * Class Kernel
 * @package App\Http
 */
class Kernel extends HttpKernel {

    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \Barryvdh\Cors\HandleCors::class,
        \App\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\VerifyCsrfToken::class,
        \App\Http\Middleware\LocaleMiddleware::class,
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        /**
         * Default laravel route middleware
         */
        'auth'                        => \App\Http\Middleware\Authenticate::class,
        'auth.basic'                  => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'guest'                       => \App\Http\Middleware\RedirectIfAuthenticated::class,

        /**
         * Access Middleware
         */
        'access.routeNeedsRole'       => \App\Http\Middleware\RouteNeedsRole::class,
        'access.routeNeedsPermission' => \App\Http\Middleware\RouteNeedsPermission::class,


        'auth.admin'  => \App\Http\Middleware\AdminAuthenticate::class,
        'guest.admin' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'auth.wechat' => \App\Http\Middleware\WechatAuthenticate::class,
        'follow'      => \App\Http\Middleware\RedirectIfNotFollow::class,

    ];
}
