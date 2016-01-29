<?php


$api->group(['prefix' => 'agents/apply', 'middleware' => 'api.auth'], function ($api) {

    $api->get('lists/{agent_id?}', [
        'as'   => 'agents.apply.tree',
        'uses' => 'AgentApplyController@agents'
    ]);

    $api->resource('/', 'AgentApplyController');
});
