<?php


/**
 * 优惠购
 */
$api->group(['namespace' => 'Campaign', 'prefix' => 'campaigns'], function ($api) {

    $api->group(['middleware' => 'auth'], function ($api) {
        $api->resource('orders', 'OrderController');
        $api->resource('order-tickets', 'OrderTicketController');
        $api->resource('campaigns', 'CampaignController');
    });
});
