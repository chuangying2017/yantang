<?php

$api->group(['namespace' => 'Backend', 'prefix' => 'admin/agents'], function ($api) {
    $api->group(['middleware' => 'access.routeNeedsPermission:view-agent-backend'], function ($api) {
        $api->get('/', 'AgentController@index');
        $api->get('detail', 'AgentController@detail');

        $api->get('detail/{agent_id}', 'AgentController@subDetail');
    });


    $api->resource('apply', 'AgentApplyController');

});
