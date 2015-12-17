<?php

$api->get('nav', [
    'as'   => 'home.nav',
    'uses' => 'IndexController@getNav'
]);

$api->get('banners', [
    'as'   => 'home.banners',
    'uses' => 'IndexController@getBanners'
]);

$api->get('sections', [
    'as'   => 'home.sections',
    'uses' => 'IndexController@getSections'
]);

$api->get('user/info', [
    'as'   => 'client.user.info',
    'uses' => 'IndexController@getUserInfo'
]);




/**
 * 需要登录才能查看
 */
$api->group(['middleware' => 'api.auth'], function ($api) {

    $api->resource('user/favs', 'FavController', ['only' => ['index', 'store', 'destroy']]);

    $api->resource('address', 'AddressController');
});


