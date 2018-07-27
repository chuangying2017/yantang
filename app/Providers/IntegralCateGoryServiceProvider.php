<?php

namespace App\Providers;

use App\Repositories\Integral\Address\UserAddress;
use App\Repositories\Integral\OrderHandle\OrderIntegralInterface;
use App\Repositories\Integral\OrderHandle\OrderIntegralRepository;
use App\Repositories\Integral\Supervisor\Supervisor;
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
        $this->app->bind(OrderIntegralInterface::class,OrderIntegralRepository::class);
        $this->app->bind(Supervisor::class,UserAddress::class);
    }
}
