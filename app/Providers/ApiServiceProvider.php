<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Tymon\JWTAuth\Test\GetUserFromTokenTest;

class ApiServiceProvider extends ServiceProvider {

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['router']->middleware('jwt.auth', \Tymon\JWTAuth\Middleware\GetUserFromToken::class);
        $this->app['router']->middleware('jwt.refresh', \Tymon\JWTAuth\Middleware\RefreshToken::class);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
