<?php

/**
 * Frontend Access Controllers
 */
$router->group(['namespace' => 'Auth'], function () use ($router) {
    /**
     * These routes require the user to be logged in
     */
    $router->group(['middleware' => 'auth'], function () {
        Route::get('auth/logout', 'AuthController@getLogout');
        Route::get('auth/password/change', 'PasswordController@getChangePassword');
        Route::post('auth/password/change', 'PasswordController@postChangePassword')->name('password.change');
    });

    /**
     * These reoutes require the user NOT be logged in
     */
    $router->group(['middleware' => 'guest'], function () use ($router) {
        Route::get('auth/login/{provider}', 'AuthController@loginThirdPartyUrl')->name('auth.provider.url');
        Route::get('auth/login/{provider}', 'AuthController@loginThirdParty')->name('auth.provider');
        Route::get('account/confirm/{token}', 'AuthController@confirmAccount')->name('account.confirm');
        Route::get('account/confirm/resend/{user_id}', 'AuthController@resendConfirmationEmail')->name('account.confirm.resend');

        $router->controller('auth', 'AuthController');
        $router->controller('password', 'PasswordController');
    });
});
