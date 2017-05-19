<?php

//收款工具
$api->group(['namespace' => 'Collect', 'middleware' => 'api.auth','prefix' => 'collect'], function ($api) {
    $api->group([
            'middleware' => [
                'access.routeNeedsRole:' . \App\Repositories\Backend\AccessProtocol::ROLE_OF_STAFF
            ]
        ], function( $api ){
            $api->get('addresses', 'CollectOrderController@addresses');
            $api->post('{collect_order}/remove', 'CollectOrderController@remove');
            $api->resource('collect_order', 'CollectOrderController',['except'=>'show']);
        }
    );

    $api->resource('collect_order', 'CollectOrderController',['only'=>'show']);
    $api->post('{collect_order}/preConfirm', 'CollectOrderController@preConfirm');
    $api->post('{collect_order}/confirm', 'CollectOrderController@confirm');
});
