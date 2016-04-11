<?php
/**
 * 需要登录才能查看
 */
$api->group(['middleware' => 'api.auth'], function ($api) {

    $api->delete('cart', 'CartController@destroy');
    $api->resource('cart', 'CartController', ['only' => ['index', 'store', 'update']]);

    $api->post('orders/confirm', [
        'as'   => 'order.preConfirm',
        'uses' => 'OrderController@preConfirm'
    ]);

    $api->post('orders/request', [
        'as'   => 'order.fetchConfirm',
        'uses' => 'OrderController@fetchConfirm'
    ]);

    $api->post('orders/return/{order_no}', [
        'as'   => 'order.return',
        'uses' => 'OrderController@refund'
    ]);

    $api->post('orders/return/deliver/{order_no}', [
        'as'   => 'order.return.deliver',
        'uses' => 'OrderController@redeliver'
    ]);

    $api->resource('orders', 'OrderController');
    $api->resource('orders.checkout', 'CheckOutController', ['only' => ['index', 'store', 'update', 'destroy']]);
});


