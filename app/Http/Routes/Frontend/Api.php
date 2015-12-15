<?php

/**
 * Frontend Access Controllers
 */
$api->group(['namespace' => 'Auth'], function () use ($api) {
    /**
     * These routes require the user to be logged in
     */
    $api->group(['middleware' => 'jwt.refresh'], function ($api) {
        $api->get('auth/logout', 'AuthController@getLogout');
        $api->get('auth/password/change', 'PasswordController@getChangePassword');
        $api->post('auth/password/change', 'PasswordController@postChangePassword')->name('password.change');
    });

    /**
     * These reoutes require the user NOT be logged in
     */
    $api->group(['middleware' => 'guest'], function () use ($api) {
        $api->get('auth/login/{provider}', 'AuthController@loginThirdParty')->name('auth.provider');
        $api->get('account/confirm/{token}', 'AuthController@confirmAccount')->name('account.confirm');
        $api->get('account/confirm/resend/{user_id}', 'AuthController@resendConfirmationEmail')->name('account.confirm.resend');

        $api->controller('auth', 'AuthController');
        $api->controller('password', 'PasswordController');
    });
});


$api->get('nav', [
    'as' => 'home.nav',
    'uses' => 'IndexController@getNav'
]);

$api->get('banners', [
    'as' => 'home.banners',
    'uses' => 'IndexController@getBanners'
]);

$api->get('sections', [
    'as' => 'home.sections',
    'uses' => 'IndexController@getSections'
]);

$api->get('user/info', [
    'as' => 'client.user.info',
    'uses' => 'IndexController@getUserInfo'
]);


$api->resource('categories', 'CategoryController', ['only' => ['index', 'show']]);
$api->resource('brands', 'BrandController', ['only' => ['index']]);
$api->resource('products', 'ProductController', ['only' => ['index', 'show']]);


/**
 * 需要登录才能查看
 */
$api->group(['middleware' => 'jwt.refresh'], function ($api) {

    $api->group(['namespace' => 'Marketing', 'prefix' => 'marketing'], function ($api) {
        $api->resource('coupons', 'CouponController');
        $api->post('coupons/exchange', [
            'as' => 'api.marketing.coupons.exchange',
            'uses' => 'CouponController@exchange'
        ]);
    });

    $api->resource('user/favs', 'FavController', ['only' => ['index', 'store', 'destroy']]);
    $api->resource('cart', 'CartController', ['only' => ['index', 'store', 'update', 'destroy']]);

    $api->post('orders/confirm', [
        'as' => 'order.preConfirm',
        'uses' => 'OrderController@preConfirm'
    ]);

    $api->post('orders/request', [
        'as' => 'order.fetchConfirm',
        'uses' => 'OrderController@fetchConfirm'
    ]);


    $api->resource('orders', 'OrderController');
    $api->resource('address', 'AddressController');
});




