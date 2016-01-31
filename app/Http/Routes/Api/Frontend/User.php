<?php
/**
 * 需要登录才能查看
 */
$api->group(['middleware' => 'api.auth'], function ($api) {

    $api->resource('user/favs', 'FavController', ['only' => ['index', 'store', 'destroy']]);

    $api->resource('user/info', 'ClientController', ['only' => ['index', 'store']]);

    $api->get('user/promotion', [
        'as'   => 'user.promotion.code',
        'uses' => 'ClientController@promotion'
    ]);

    $api->post('user/phone', 'Auth\AuthController@updatePhone');

    $api->resource('address', 'AddressController');

    $api->get('images/token', [
        'as'   => 'images.token',
        'uses' => 'ImageController@token'
    ]);
});

