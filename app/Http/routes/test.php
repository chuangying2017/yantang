<?php

if(App::environment() == 'local' || env('APP_DEBUG')) {

    Route::get('test/{id?}', function($id){
        return seesion(['id' => $id]);
    });

    Route::get('test/token', function(){
        return Session::token();
    });

    Route::get('/test/login/{id}', function($id){
        Auth::user()->logout();
        Auth::user()->loginUsingId($id);
        return $id . 'login';
    });

    Route::get('/test/logout', function(){
        Auth::user()->logout();
    });

}
