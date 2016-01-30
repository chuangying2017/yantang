<?php

//$api->group(['prefix' => 'agents'], function ($api) {
$api->group(['middleware' => 'access.routeNeedsPermission:view-agent-backend'], function ($api) {

    $api->resource('agents/apply', 'AgentApplyController', ['only' => ['index', 'show', 'update']]);

    $api->get('agents/earn', ['as' => 'agents.earn.data', 'uses' => 'AgentController@earnData']);
    $api->get('agents/orders', ['as' => 'agents.orders.data', 'uses' => 'AgentController@orders']);

    $api->resource('agents/rates', 'AgentRateController', ['only' => ['index', 'show', 'store', 'update']]);

    $api->resource('agents', 'AgentController', ['only' => ['index', 'show']]);

});

//});


