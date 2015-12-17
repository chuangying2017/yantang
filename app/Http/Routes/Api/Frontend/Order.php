<?php
/**
 * 需要登录才能查看
 */
$api->group(['middleware' => 'api.auth'], function ($api) {

    $api->resource('cart', 'CartController', ['only' => ['index', 'store', 'update', 'destroy']]);

    $api->post('orders/confirm', [
        'as'   => 'order.preConfirm',
        'uses' => 'OrderController@preConfirm'
    ]);

    $api->post('orders/request', [
        'as'   => 'order.fetchConfirm',
        'uses' => 'OrderController@fetchConfirm'
    ]);

    $api->resource('orders', 'OrderController');
    $api->resource('orders.checkout', 'CheckOutController', ['only' => ['index', 'store', 'update', 'destroy']]);
});


