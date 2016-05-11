<?php

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {
    $api->group(['namespace' => 'App\V1\Controllers\Api', 'middleware' => 'cors'], function ($api) {

        /**
         * Frontend Routes 前端路由
         */
        $api->group(['namespace' => 'Frontend'], function () use ($api) {
            /**
             * 商城
             */
            //主页
            require(__DIR__ . "/Routes/Api/Frontend/Home.php");
            //商品
            require(__DIR__ . "/Routes/Api/Frontend/Product.php");

            /**
             * 用户
             */
            //用户登录注册
            require(__DIR__ . "/Routes/Api/Frontend/Access.php");
            require(__DIR__ . "/Routes/Api/Frontend/Sms.php");
            //用户信息
            require(__DIR__ . "/Routes/Api/Frontend/User.php");
            //优惠券信息
            require(__DIR__ . "/Routes/Api/Frontend/Marketing.php");
            //用户订单
            require(__DIR__ . "/Routes/Api/Frontend/Order.php");

            //九级分销
            require(__DIR__ . "/Routes/Api/Frontend/Agent.php");
        });


        /**
         * Backend Routes 管理后台路由
         */
        $api->group(['namespace' => 'Backend', 'prefix' => 'admin'], function () use ($api) {

            //商城主页,设置
            require(__DIR__ . "/Routes/Api/Backend/Home.php");

            //商品
            require(__DIR__ . "/Routes/Api/Backend/Product.php");
            require(__DIR__ . "/Routes/Api/Backend/Qiniu.php");
            //优惠券
            require(__DIR__ . "/Routes/Api/Backend/Marketing.php");
            //商家
            require(__DIR__ . "/Routes/Api/Backend/Merchant.php");
            //订单
            require(__DIR__ . "/Routes/Api/Backend/Order.php");

            //九级分销
            require(__DIR__ . "/Routes/Api/Backend/Agent.php");
        });

    });
});
