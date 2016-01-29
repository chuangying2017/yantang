<?php

//$api->group(['prefix' => 'agents'], function ($api) {
$api->group(['middleware' => 'access.routeNeedsPermission:view-agent-backend'], function ($api) {

    $api->resource('agents/apply', 'AgentApplyController', ['only' => ['index', 'show', 'update']]);
    $api->resource('agents', 'AgentController', ['only' => ['index', 'show']]);

});

//});


