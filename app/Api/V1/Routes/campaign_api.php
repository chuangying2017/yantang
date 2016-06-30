<?php


/**
 * 优惠购
 */
$api->group(['namespace' => 'Campaign', 'middleware' => 'api.auth'], function ($api) {

    $api->group(['prefix' => 'campaigns'], function ($api) {
        $api->group(['middleware' => 'api.auth'], function ($api) {

            $api->resource('orders', 'OrderController');
            $api->resource('orders.checkout', 'CheckoutController', ['only' => ['index', 'store', 'show']]);
            $api->resource('tickets', 'OrderTicketController');
            $api->resource('campaigns', 'CampaignController');
            $api->resource('stores', 'StoreController@index');
        });
    });

    $api->group(['prefix' => 'store'], function ($api) {

        $api->group(['middleware' => ['api.auth', 'access.routeNeedsRole:' . \App\Repositories\Backend\AccessProtocol::ROLE_OF_STATION]], function ($api) {
            $api->resource('exchange', 'StoreExchangeController');
            $api->get('info', 'StoreController@info');
            $api->resource('statements', 'StoreStatementController', ['only' => ['index', 'store', 'update']]);
            $api->resource('tickets', 'StoreTicketController');
            $api->post('/{store_id}/unbind', 'StoreController@postUnBind')->name('api.store.unbind');
        });

        $api->get('/{store_id}/bind', 'StoreController@getBind')->name('api.store.check.bind.get');
        $api->post('/{store_id}/bind', 'StoreController@postBind')->name('api.store.bind');

    });

});
