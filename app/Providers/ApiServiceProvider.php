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
        $this->app['Dingo\Api\Transformer\Factory']->setAdapter(function ($app) {
            $fractal = new \League\Fractal\Manager;
            $fractal->setSerializer(new \App\Api\V1\Transformers\NoDataArraySerializer);
            return new \Dingo\Api\Transformer\Adapter\Fractal($fractal);
        });
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
