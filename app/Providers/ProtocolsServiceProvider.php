<?php

namespace App\Providers;

use App\Providers\Other\Protocol;
use App\Repositories\Other\ProtocolGenerator;
use Illuminate\Support\ServiceProvider;

class ProtocolsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->bind(ProtocolGenerator::class,Protocol::class);
    }
}
