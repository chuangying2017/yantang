<?php

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {

    $api->group(['namespace' => 'App\Api\V1\Controllers', 'middleware' => 'cors'], function ($api) {

        /**
         * Frontend Access Controllers
         */
        $api->group(['namespace' => 'Auth'], function () use ($api) {
            /**
             * These routes require the user to be logged in
             */
            $api->group(['middleware' => 'auth'], function ($api) {
                $api->get('auth/logout', 'AuthController@getLogout');
                $api->get('auth/password/change', 'PasswordController@getChangePassword');
                $api->post('auth/password/change', 'PasswordController@postChangePassword')->name('password.change');
            });

            /**
             * These reoutes require the user NOT be logged in
             */
            $api->group(['middleware' => 'guest', 'web'], function () use ($api) {
                $api->get('auth/login/{provider}', 'AuthController@loginThirdPartyUrl')->name('auth.provider.url');
                $api->post('auth/login/{provider}', 'AuthController@loginThirdParty')->name('auth.provider');
                $api->controller('auth', 'AuthController');
                $api->controller('password', 'PasswordController');
            });

            /**
             * Sms
             */
            $api->post('auth/sms/send-code', 'SmsController@postSendCode')->name('auth.sms.send');
            $api->get('auth/sms/debug', 'SmsController@getInfo')->name('auth.sms.debug');
        });

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
                $api->resource('preorders', 'StationPreorderController'); //服务部下的订奶订单列表
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
        });


        /**
         *
         * Admin
         *
         */
        $api->group(['namespace' => 'Admin', 'prefix' => 'admin'], function ($api) {
            $api->group(['namespace' => 'Product'], function ($api) {
                $api->resource('products', 'ProductController');
                $api->resource('attributes', 'AttributeController');
                $api->resource('attributes.values', 'AttributeValueController');
                $api->resource('brands', 'BrandController');
                $api->resource('groups', 'BrandController');
                $api->resource('cats', 'CategoryController');
            });
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
