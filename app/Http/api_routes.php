<?php

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {


    $api->group(['namespace' => 'App\Api\V1\Controllers', 'middleware' => 'cors'], function ($api) {

        /**
         * 用户相关
         */
        require_once(__DIR__ . '/../Api/V1/Routes/user_api.php');


        /**
         * 优惠
         */
        require_once(__DIR__ . '/../Api/V1/Routes/promotion_api.php');


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
         * 订奶
         */
        require_once(__DIR__ . '/../Api/V1/Routes/subscribe_api.php');


        /**
         * Gateway
         */
        require_once(__DIR__ . '/../Api/V1/Routes/gateway_api.php');

        
        /**
         * Tool Support
         */
        require_once(__DIR__ . '/../Api/V1/Routes/test_api.php');

    });

});
