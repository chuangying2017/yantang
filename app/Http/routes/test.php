<?php
/*
 * Test routes
 */

if (App::environment() == 'local' || env('APP_DEBUG')) {

    Route::get('test', function () {
//        return \App\Services\Client\ClientService::create('ken', 'kobebryant', 'bryant@weazm.com');
        $wallet = new \App\Services\Client\Wallet\WalletRepository(6);
        return $wallet->unFrozen(50);
    });

    Route::get('test/token', function () {
        return csrf_token();
    });

    Route::get('/test/login/{id}', function ($id) {
        Auth::user()->logout();
        Auth::user()->loginUsingId($id);

        return $id . ' login ' . (Auth::user()->check() ? ' success' : ' fail');
    });

    Route::get('/test/logout', function () {
        Auth::user()->logout();
    });

}
