<?php


/**
 * 订奶系统
 */
$api->group(['namespace' => 'Subscribe'], function ($api) {

    //配送员端
    $api->group(['prefix' => 'stations/staffs'], function ($api) {

        $api->get('info', 'StaffController@info');
        $api->get('/{staff_id}/bind', 'StaffController@getBind')->name('api.staff.check.bind.get');
        $api->post('/{staff_id}/bind', 'StaffController@postBind')->name('api.staff.bind');
//                $api->resource('statements', 'StaffStatementController');
    });

    //服务部端
    $api->group(['middleware' => 'api.auth'], function ($api) {

        $api->group(['prefix' => 'stations'], function ($api) {

            $api->get('info', 'StationController@info');
            $api->get('/{station_id}/bind', 'StationController@getBind')->name('api.station.check.bind.get');
            $api->post('/{station_id}/bind', 'StationController@postBind')->name('api.station.bind');
//            $api->resource('statements', 'StationStatementController');
            $api->resource('staffs', 'StationStaffController');
        });

        //管理配送员
    });

    //用户订奶
    $api->group(['prefix' => 'subscribe'], function ($api) {

        $api->get('preorders', 'PreorderController@index');
        $api->get('stations', 'StationController@index');
        $api->get('products', 'ProductController@index');

    });
});

