<?php

namespace App\Providers;

use App\Services\Integral\Category\Category;
use App\Services\Integral\Category\IntegralCategory;
use Illuminate\Support\ServiceProvider;

class IntegralServiceProvider extends ServiceProvider
{

    protected $defer = true;

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
        $this->app->bind(IntegralCategory::class,Category::class);
    }
}
