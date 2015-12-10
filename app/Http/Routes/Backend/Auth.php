<?php
$router->group(['namespace' => 'Auth'], function () use ($router) {

    $router->group(['middleware' => 'guest'], function () use ($router) {
        get('auth/login/{provider}', 'AuthController@loginThirdParty')->name('auth.provider');
        get('account/confirm/{token}', 'AuthController@confirmAccount')->name('account.confirm');
        get('account/confirm/resend/{user_id}', 'AuthController@resendConfirmationEmail')->name('account.confirm.resend');

        $router->controller('auth', 'AuthController');
        $router->controller('password', 'PasswordController');
    });
});
