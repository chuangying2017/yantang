<?php

$api->group(['prefix' => 'agents'], function ($api) {
    $api->group(['middleware' => 'access.routeNeedsPermission:view-agent-backend'], function ($api) {

        $api->resource('apply', 'AgentApplyController', ['only' => ['index', 'show', 'update']]);
        $api->get('detail', 'AgentController@detail');
        $api->get('detail/{agent_id}', 'AgentController@subDetail');
        $api->get('/', 'AgentController@index');

    });


});


