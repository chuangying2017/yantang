<?php

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {


    $api->group(['namespace' => 'App\Api\V1\Controllers', 'middleware' => 'cors'], function ($api) {

        /**
         * 用户相关
         */
        require_once(__DIR__ . '/../Api/V1/Routes/user_api.php');

        /**
         * 商城
         */
        require_once(__DIR__ . '/../Api/V1/Routes/mall_api.php');

        /**
         * 优惠购
         */
        require_once(__DIR__ . '/../Api/V1/Routes/campaign_api.php');

        /**
         *
         * Admin
         *
         */
        require_once(__DIR__ . '/../Api/V1/Routes/admin_api.php');


        /**
         * Gateway
         */


        $api->group(['namespace' => 'Gateway', 'prefix' => 'gateway'], function ($api) {

            $api->group(['prefix' => 'pingxx'], function ($api) {
                $api->post('paid', 'PingxxNotifyController@paid');
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




    });

});
