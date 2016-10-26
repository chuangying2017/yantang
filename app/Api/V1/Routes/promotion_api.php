<?php

/**
 * 优惠券
 */

$api->group(['namespace' => 'Promotion', 'prefix' => 'promotions'], function ($api) {

    $api->group(['middleware' => 'api.auth'], function ($api) {

        $api->resource('coupons', 'CouponController');
        $api->resource('campaigns', 'CampaignController');

        $api->resource('tickets', 'TicketController');

        $api->resource('red-envelopes', 'RedEnvelopeController', ['only' => ['show', 'store']]);
        
        $api->resource('activities', 'ActivityController', ['only' => ['show', 'index']]);
        
    });

});
