<?php


/**
 * 优惠购
 */
$api->group(['namespace' => 'Campaign'], function ($api) {

    $api->group(['prefix' => 'campaigns'], function ($api) {
        $api->group(['middleware' => 'auth'], function ($api) {

            $api->resource('orders', 'OrderController');
            $api->resource('order-tickets', 'OrderTicketController');
            $api->resource('campaigns', 'CampaignController');

        });
    });
    $api->group(['prefix' => 'store'], function ($api) {

        $api->group(['middleware' => 'auth'], function ($api) {

            $api->resource('exchange', 'StoreExchangeController');
            $api->get('info', 'StoreController@info');
            $api->resource('statements', 'StoreStatementController');
            $api->resource('tickets', 'StoreTicketController');

        });

    });

});
