<?php

namespace App\Providers;

use App\Models\Product\Product;
use Illuminate\Support\ServiceProvider;
use XS;
use XSDocument;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            \App\Repositories\Image\ImageRepositoryContract::class,
            \App\Repositories\Image\QiniuImageRepository::class
        );
    }
}
