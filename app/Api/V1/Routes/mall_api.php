<?php

/**
 * 商城
 */
$api->group(['namespace' => 'Mall', 'prefix' => 'mall'], function ($api) {
    $api->group(['middleware' => 'api.auth'], function ($api) {
        $api->resource('orders/cart', 'CartOrderController', ['only' => ['store', 'update', 'destroy']]);
        $api->resource('cart', 'CartController');
        $api->resource('orders', 'OrderController');
        $api->resource('orders.checkout', 'CheckoutController', ['only' => ['index', 'store']]);
    });

    $api->resource('products', 'ProductController', ['only' => ['index', 'show']]);
    $api->resource('cats', 'CategoryController', ['only' => ['index', 'show']]);
    $api->resource('brands', 'BrandController', ['only' => ['index', 'show']]);
    $api->resource('groups', 'GroupController', ['only' => ['index', 'show']]);
});

