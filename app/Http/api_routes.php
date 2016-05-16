<?php

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {

    $api->group(['namespace' => 'App\Http\Controllers\Api', 'middleware' => 'cors'], function ($api) {
        /**
         * 后台
         */
        $api->group(['namespace' => 'Backend'], function ($api) {
            /**
             * 服务部
             */
            $api->group(['namespace' => 'Station', 'prefix' => 'stations'], function ($api) {

                $api->resource('products', 'ProductsController', ['only' => ['index']]); //查看可定购商品
            });
        });
    });

});
