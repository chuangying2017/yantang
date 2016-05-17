<?php
/*
 * Test routes
 */

use App\Models\Product\AttributeValue;
use App\Models\OrderProduct;
use App\Services\Product\ProductConst;

if (App::environment() == 'local' || env('APP_DEBUG')) {

    Route::get('test', function () {
        return 1;
    });

    Route::get('test/agent', function () {
        $agent_order = \App\Models\AgentOrder::find(33);
        event(new \App\Services\Agent\Event\NewAgentOrder($agent_order));

        return 1;
    });

    Route::get('test/token', function () {
        return csrf_token();
    });

    Route::get('/test/login/{id}', function ($id) {
        Auth::logout();
        Auth::loginUsingId($id);

        return $id . ' login ' . (Auth::check() ? ' success' : ' fail');
    });

    Route::get('/test/logout', function () {
        Auth::user()->logout();
    });



}
