<?php


/**
 * OpenApi
 */
$api->group(['namespace' => 'Open', 'prefix' => 'open', 'middleware' => ['valid.server']], function ($api) {

    $api->group(['prefix' => 'weixin'], function ($api) {
        $api->get('token', 'WeixinController@token')->name('open.weixin.token');
    });

});

