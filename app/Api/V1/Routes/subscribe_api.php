<?php


/**
 * 订奶系统
 */
$api->group(['namespace' => 'Subscribe'], function ($api) {

    //服务部端
    $api->group(['prefix' => 'stations'], function ($api) {

        $api->group(['middleware' => 'api.auth'], function ($api) {
            $api->get('info', 'StationController@info');
            $api->get('/{station_id}/bind', 'StationController@getBind')->name('api.station.check.bind.get');
            $api->post('/{station_id}/bind', 'StationController@postBind')->name('api.station.bind');
            $api->resource('statements', 'StationStatementController');
        });

    });

    //用户订奶
    $api->group(['prefix' => 'subscribe'], function ($api) {

        $api->get('stations', 'StationController@index');

        $api->get('products', 'ProductController@index');

    });
});

