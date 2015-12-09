<?php


$api->resource('categories', 'CategoryController', ['only' => ['index', 'show']]);

$api->resource('products', 'ProductController', ['only' => 'index', 'show']);

$api->resource('fav', 'FavController', ['only' => ['index', 'store', 'destroy']]);


$api->group(['prefix' => 'marketing'], function ($api) {
    $api->resource('coupons', 'Marketing\CouponController');
});
