<?php

$api->group(['namespace' => 'Backend', 'prefix' => 'admin/agents'], function ($api) {
    $api->group(['middleware' => 'access.routeNeedsPermission:view-agent-backend'], function ($api) {
        $api->get('/', 'AgentController@index');
        $api->get('detail', 'AgentController@detail');

        $api->get('detail/{agent_id}', 'AgentController@subDetail');
    });


});


$api->group(['namespace' => 'Backend', 'prefix' => 'apply/agents', 'middleware' => 'api.auth'], function ($api) {

    $api->get('lists/{agent_id?}', [
        'as'   => 'apply.agents.tree',
        'uses' => 'AgentApplyController@agents'
    ]);
    $api->resource('/', 'AgentApplyController');
});
