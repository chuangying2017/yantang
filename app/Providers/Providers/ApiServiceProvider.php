<?php

namespace App\Providers;

use API;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpKernel\Exception\HttpException;
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


        API::error(function (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new HttpException(404, $e->getMessage());
        });

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
