<?php

namespace App\Providers;

use App\Services\Integral\Category\Category;
use App\Services\Integral\Category\IntegralCategoryMangers;
use App\Services\Integral\Product\ProductInerface;
use App\Services\Integral\Product\ProductManager;
use Illuminate\Support\ServiceProvider;

class IntegralCateGoryServiceProvider extends ServiceProvider
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
        $this->app->bind(IntegralCategoryMangers::class, Category::class);
        $this->app->bind(ProductInerface::class,ProductManager::class);
       // $this->app->bind();
    }
}
