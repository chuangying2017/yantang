<?php


/**
 * 订奶系统
 */
$api->group(['namespace' => 'Subscribe', 'middleware' => 'api.auth'], function ($api) {

    //配送员端
    $api->group(['prefix' => 'stations/staffs'], function ($api) {

        $api->group(['middleware' => ['api.auth', 'access.routeNeedsRole:' . \App\Repositories\Backend\AccessProtocol::ROLE_OF_STAFF]], function ($api) {
            $api->get('info', 'StaffController@info');
            $api->post('/{staff_id}/unbind', 'StaffController@postUnBind')->name('api.staff.unbind');
        });

        $api->get('/{staff_id}/bind', 'StaffController@getBind')->name('api.staff.check.bind.get');
        $api->post('/{staff_id}/bind', 'StaffController@postBind')->name('api.staff.bind');
    });

    //服务部端

    $api->group(['prefix' => 'stations'], function ($api) {


        $api->group(['middleware' => ['api.auth', 'access.routeNeedsRole:' . \App\Repositories\Backend\AccessProtocol::ROLE_OF_STATION]], function ($api) {

            $api->get('info', 'StationController@info');
            $api->resource('statements', 'StatementController');
            //管理配送员
            $api->resource('staffs', 'StationStaffController');

            $api->post('/{station_id}/unbind', 'StationController@postUnBind')->name('api.station.unbind');
        });

        $api->get('/{station_id}/bind', 'StationController@getBind')->name('api.station.check.bind.get');
        $api->post('/{station_id}/bind', 'StationController@postBind')->name('api.station.bind');

    });

    //用户订奶
    $api->group(['prefix' => 'subscribe'], function ($api) {

        $api->get('preorders', 'PreorderController@index');
        $api->get('stations', 'StationController@index');
        $api->get('products', 'ProductController@index');

    });
});

