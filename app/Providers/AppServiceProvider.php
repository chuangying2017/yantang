<?php

namespace App\Providers;

use App\Models\Product\Product;
use App\Repositories\Comment\StarLevel\CommentStarLevelRepository;
use App\Repositories\Comment\StarLevel\CommentStarLevelRepositoryContract;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use XS;
use XSDocument;

class AppServiceProvider extends ServiceProvider {

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('cn_val', function ($attribute, $value, $parameters, $validator) {
            return is_numeric($value) && $value > 0 ;
        });
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

        $this->app->bind(
            \App\Repositories\Comment\CommentRepositoryContract::class,
            \App\Repositories\Comment\EloquentCommentRepository::class
        );

        $this->app->bind(
            CommentStarLevelRepositoryContract::class,
            CommentStarLevelRepository::class
        );
    }
}
