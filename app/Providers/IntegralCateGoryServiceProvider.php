<?php

namespace App\Providers;

use App\Services\Integral\Category\Category;
use App\Services\Integral\Category\IntegralCategoryMangers;
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
    }
}
