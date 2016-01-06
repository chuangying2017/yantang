<?php


$api->group(['prefix' => 'agents'], function ($api) {
    $api->get('/', 'AgentController@index');
    $api->get('detail', 'AgentController@detail');

    $api->get('detail/{agent_id}', 'AgentController@subDetail');


});
