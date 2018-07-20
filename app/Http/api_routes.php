<?php

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {


    $api->group(['namespace' => 'App\Api\V1\Controllers', 'middleware' => []], function ($api) {

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
        
        /**
         * Open Api
         */
        require_once(__DIR__ . '/../Api/V1/Routes/open_api.php');

        /**
         * 收款工具
         */
        require_once(__DIR__ . '/../Api/V1/Routes/collect_api.php');

        /**
         *
         * 自增个人更改
         * */
        require_once(__DIR__.'/../Api/V1/Routes/others_api.php');

        /**
         * 礼品卡
         */
        require_once(__DIR__ . '/../Api/V1/Routes/tool_api.php');

        /**
         * comment member function
         * */
        require_once (__DIR__.'/../Api/V1/Routes/comments_api.php');

        /*
         * 积分前台
         * */
        require_once (__DIR__.'/../Api/V1/Routes/integral_api.php');
    });

});
