<?php


/**
 * 优惠购
 */
$api->group(['namespace' => 'Campaign'], function ($api) {

    $api->group(['prefix' => 'campaigns'], function ($api) {
        $api->group(['middleware' => 'api.auth'], function ($api) {

            $api->resource('orders', 'OrderController');
            $api->resource('order-tickets', 'OrderTicketController');
            $api->resource('campaigns', 'CampaignController');

        });
    });
    $api->group(['prefix' => 'store'], function ($api) {

        $api->group(['middleware' => 'api.auth'], function ($api) {

            $api->resource('exchange', 'StoreExchangeController');
            $api->get('info', 'StoreController@info');
            $api->get('/{store_id}/bind', 'StoreController@getBind')->name('api.store.check.bind.get');
            $api->post('/{store_id}/bind', 'StoreController@postBind')->name('api.store.bind');
            $api->resource('statements', 'StoreStatementController');
            $api->resource('tickets', 'StoreTicketController');

        });

    });

});
