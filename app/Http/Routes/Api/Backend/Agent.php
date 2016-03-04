<?php

//$api->group(['prefix' => 'agents'], function ($api) {
$api->group(['middleware' => ['api.auth', 'access.routeNeedsPermission:access-agent-backend']], function ($api) {

    $api->put('agents/info/{apply_id}', [
        'as'   => 'agents.info.update',
        'uses' => 'AgentApplyController@updateInfo'
    ]);

    $api->resource('agents/apply', 'AgentApplyController', ['only' => ['index', 'show', 'update']]);

    $api->get('agents/earn', ['as' => 'agents.earn.data', 'uses' => 'AgentController@earnData']);

    $api->get('agents/orders', ['as' => 'agents.orders.data', 'uses' => 'AgentController@orders']);
    $api->get('agents/users', ['as' => 'agents.users.data', 'uses' => 'AgentController@users']);

    $api->put('agents/rates', 'AgentRateController@update')->name('agents.rate.update');
    $api->resource('agents/rates', 'AgentRateController', ['only' => ['index', 'show', 'store']]);
    $api->get('agents/{agent_id}/info', ['as' => 'agent.show.info', 'uses' => 'AgentController@info']);
    $api->resource('agents', 'AgentController', ['only' => ['index', 'show']]);

});

//});


