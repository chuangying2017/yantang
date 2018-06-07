<?php


/**
 * Gateway
 */
$api->group(['namespace' => 'Gateway', 'prefix' => 'gateway'], function ($api) {

    $api->group(['prefix' => 'pingxx'], function ($api) {
        $api->post('paid', 'PingxxNotifyController@paid');//ping++ interface call - back 
        $api->post('refund', 'PingxxNotifyController@refund');
        $api->post('transfer', 'PingxxNotifyController@transfer');
        $api->post('summary', 'PingxxNotifyController@summary');
    });

    $api->group(['prefix' => 'qiniu'], function ($api) {
        $api->post('callback', 'QiniuNotifyController@store')->name('qiniu.callback');
    });

    $api->group(['prefix' => 'auth'], function ($api) {
        $api->get('weixin', 'WechatAuthController@redirect');
    });

});

