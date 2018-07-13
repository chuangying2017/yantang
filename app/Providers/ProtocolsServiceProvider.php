<?php

namespace App\Providers;


use App\Repositories\Other\Protocol;
use App\Repositories\Other\ProtocolGenerator;
use App\Services\Integral\Category\Category;
use App\Services\Integral\Category\IntegralCategoryMangers;
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
        $this->app->bind(IntegralCategoryMangers::class,Category::class);
    }
}
