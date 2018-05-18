<?php

namespace App\Services;

use App\Repositories\setting\SetLogic;
use App\Repositories\setting\SetMode;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProviders extends ServiceProvider
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
        $this->app->bind(SetMode::class,SetLogic::class);
    }
}
