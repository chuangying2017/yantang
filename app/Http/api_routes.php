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
         *
         * Admin
         *
         */
        require_once(__DIR__ . '/../Api/V1/Routes/admin_api.php');

        /**
         * 订奶系统
         */
        $api->group(['namespace' => 'Subscribe'], function ($api) {
            //服务部端
            $api->group(['namespace' => 'Station', 'prefix' => 'stations'], function ($api) {
                $api->resource('products', 'ProductsController', ['only' => ['index']]); //查看可定购商品
                $api->resource('staffs', 'StaffsController');
                $api->get('info', 'StationController@index'); //查看服务部信息
                $api->get('products', 'StationController@products'); //查看可定购商品
                $api->get('bind_station', 'StationController@bindStation'); //绑定服务部
                $api->get('claim_goods', 'StationController@claimGoods'); //取货单
                $api->resource('preorders', 'StationPreorderController'); //服务部下的订奶订单列表
                $api->get('statements', 'StatementsController@index'); //查看某年对账列表
                $api->post('account_check', 'StatementsController@accountCheck'); //对账
            });
            //订奶客户端
            $api->group(['namespace' => 'Preorder'], function ($api) {
                $api->get('subscribe/preorders', 'PreorderController@stations');
                $api->resource('subscribe/preorders', 'PreorderController');
                $api->resource('subscribe/preorder_product', 'PreorderProductController');
                $api->resource('subscribe/topup', 'TopUpController');
            });
            //订奶配送端
            $api->group(['namespace' => 'Staff'], function ($api) {
                $api->get('staffs/preorders/data', 'StaffPreorderController@data');
                $api->resource('staffs/preorders', 'StaffPreorderController');
            });
        });

        /**
         * 总部管理服务部
         */
        $api->group(['namespace' => 'Admin\\Station', 'prefix' => 'admin'], function ($api) {
            $api->resource('stations', 'AdminStationController'); //查看服务部列表
            $api->post('create_billing', 'AdminStatementsController@createBilling');
        });


        /**
         *
         * Station
         *
         */
        $api->group(['namespace' => 'Subscribe\Station', 'prefix' => 'stations'], function ($api) {
            $api->get('products', 'ProductController@index');
        });


    });

});
