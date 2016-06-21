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
         * 服务部
         */
        $api->group(['namespace' => 'Subscribe'], function ($api) {
            $api->group(['namespace' => 'Station', 'prefix' => 'stations'], function ($api) {
                $api->resource('products', 'ProductsController', ['only' => ['index']]); //查看可定购商品
                $api->resource('staffs', 'StaffsController');
                $api->get('info', 'StationController@index'); //查看服务部信息
                $api->get('products', 'StationController@products'); //查看可定购商品
                $api->get('bind_station', 'StationController@bindStation'); //绑定服务部
            });
            $api->group(['namespace' => 'Preorder'], function ($api) {
                $api->get('subscribe/preorders', 'PreorderController@stations');
            });
        });

        /**
         * 总部管理服务部
         */
        $api->group(['namespace' => 'Admin\\Station', 'prefix' => 'admin'], function ($api) {
            $api->resource('stations', 'AdminStationController'); //查看服务部列表
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
