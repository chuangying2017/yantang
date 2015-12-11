<?php


$api->resource('categories', 'CategoryController', ['only' => ['index', 'show']]);

$api->resource('products', 'ProductController', ['only' => 'index', 'show']);

$api->resource('fav', 'FavController', ['only' => ['index', 'store', 'destroy']]);


$api->resource('carts', 'CartController', ['only' => ['index', 'store', 'update', 'destroy']]);


$api->group(['namespace' => 'Marketing', 'prefix' => 'marketing'], function ($api) {
    $api->resource('coupons', 'CouponController');
    $api->post('coupons/exchange', [
        'as'   => 'api.marketing.coupons.exchange',
        'uses' => 'CouponController@exchange'
    ]);
});

$api->post('orders/confirm', [
    'as'   => 'order.preConfirm',
    'uses' => 'OrderController@preConfirm'
]);

$api->resource('orders', 'OrderController');
$api->resource('address', 'AddressController');


