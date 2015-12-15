<?php

$api->version('v1', function ($api) {
    $api->group(['namespace' => 'App\Http\Controllers', 'middleware' => 'cors'], function ($api) {

        /**
         * Frontend Routes
         * Namespaces indicate folder structure
         */
        $api->group(['namespace' => 'Frontend\Api'], function () use ($api) {
            require(__DIR__ . "/Routes/Frontend/Api.php");
        });

        /**
         * Backend Routes
         * Namespaces indicate folder structure
         */
        $api->group(['namespace' => 'Backend\Api', 'prefix' => 'admin'], function () use ($api) {
            require(__DIR__ . "/Routes/Backend/Api.php");
        });

    });
});
