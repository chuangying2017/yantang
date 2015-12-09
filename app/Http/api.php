<?php

$api->version('v1', function ($api) {

    /**
     * Frontend Routes
     * Namespaces indicate folder structure
     */
    $api->group(['namespace' => 'App\Http\Controllers', 'middleware' => 'cors'], function ($api) {

        $api->group(['namespace' => 'Frontend'], function () use ($api) {
            $api->group(['namespace' => 'Api'], function () use ($api) {
                require(__DIR__ . "/Routes/Frontend/Api.php");
            });
        });

        /**
         * Backend Routes
         * Namespaces indicate folder structure
         */
        $api->group(['namespace' => 'Backend'], function () use ($api) {
            $api->group(['prefix' => 'admin'], function () use ($api) {
                $api->group(['namespace' => 'Api'], function () use ($api) {
                    require(__DIR__ . "/Routes/Backend/Api.php");
                });
            });
        });
    });
});
