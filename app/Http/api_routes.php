<?php

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {

    $api->group(['namespace' => 'App\V1\Controllers', 'middleware' => 'cors'], function ($api) {



    });

});
