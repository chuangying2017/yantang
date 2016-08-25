<?php


/**
 * 订奶系统
 */
$api->group(['namespace' => 'Subscribe', 'middleware' => 'api.auth'], function ($api) {

    //配送员端
    $api->group(['prefix' => 'staffs'], function ($api) {

        $api->group(['middleware' => ['api.auth', 'access.routeNeedsRole:' . \App\Repositories\Backend\AccessProtocol::ROLE_OF_STAFF]], function ($api) {
            $api->get('info', 'StaffController@info');
            $api->get('preorders/daily', 'StaffPreorderController@daily');
            $api->put('preorders/{order_id}/pause', 'StaffPreorderController@pause');
            $api->put('preorders/{order_id}/restart', 'StaffPreorderController@restart');
            $api->resource('preorders', 'StaffPreorderController', ['only' => ['index', 'show']]);
            $api->post('/{staff_id}/unbind', 'StaffController@postUnBind')->name('api.staff.unbind');
        });

        $api->get('/{staff_id}/bind', 'StaffController@getBind')->name('api.staff.check.bind.get');
        $api->post('/{staff_id}/bind', 'StaffController@postBind')->name('api.staff.bind');
    });

    //服务部端

    $api->group(['prefix' => 'stations'], function ($api) {


        $api->group(['middleware' => ['api.auth', 'access.routeNeedsRole:' . \App\Repositories\Backend\AccessProtocol::ROLE_OF_STATION]], function ($api) {

            $api->get('info', 'StationController@info');

            $api->get('preorders/daily', 'StationPreorderController@daily');

            $api->resource('statements', 'StationStatementController', ['only' => ['index', 'show', 'update']]);
            //管理配送员
            $api->get('staffs/{staff_id}/preorders', 'StationStaffController@orders');
            $api->put('staffs/{staff_id}/preorders', 'StationStaffController@reassign');

            $api->resource('staffs', 'StationStaffController');


            $api->post('/{station_id}/unbind', 'StationController@postUnBind')->name('api.station.unbind');

            $api->get('preorders/deliver', 'StationPreorderController@deliver');
            $api->put('preorders/{order_id}/reject', 'StationPreorderController@reject');
            $api->put('preorders/{order_id}/confirm', 'StationPreorderController@confirm');
            $api->resource('preorders/{order_id}/assign', 'StationAssignController', ['only' => ['store', 'destroy']]);
            $api->resource('preorders', 'StationPreorderController');
        });


        $api->get('/{station_id}/bind', 'StationController@getBind')->name('api.station.check.bind.get');
        $api->post('/{station_id}/bind', 'StationController@postBind')->name('api.station.bind');
    });

    //用户订奶
    $api->group(['prefix' => 'subscribe'], function ($api) {

        $api->put('orders/{temp_order}/confirm', 'OrderController@confirm');
        $api->resource('orders', 'OrderController');
        $api->get('preorders/{order_id}/deliver', 'PreorderController@deliver');
        $api->post('preorders/comments', 'PreorderCommentController@store');
        $api->resource('preorders', 'PreorderController');
        $api->resource('orders.checkout', 'CheckoutController');
        $api->resource('address', 'AddressController');
        $api->get('stations', 'StationController@index');
        $api->get('products', 'ProductController@index');
        $api->get('districts', 'DistrictController@index');

    });
});

