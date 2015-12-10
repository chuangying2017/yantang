<?php
/*
 * Test routes
 */

if (App::environment() == 'local' || env('APP_DEBUG')) {

    Route::get('test', function () {
        return view('frontend.auth.register');
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
