<?php

/**
 * 需要登录才能查看
 */
$api->group(['middleware' => 'api.auth'], function ($api) {

    $api->group(['namespace' => 'Marketing', 'prefix' => 'marketing'], function ($api) {
        $api->resource('coupons', 'CouponController', ['only' => ['index', 'store']]);

        $api->post('coupons/exchange', [
            'as'   => 'api.marketing.coupons.exchange',
            'uses' => 'CouponController@exchange'
        ]);
    });

    $api->resource('address', 'AddressController');
});


