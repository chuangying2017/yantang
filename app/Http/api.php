<?php

$api->version('v1', function ($api) {
    $api->group(['namespace' => 'App\Http\Controllers\Api', 'middleware' => 'cors'], function ($api) {

        /**
         * Frontend Routes
         * Namespaces indicate folder structure
         */
        $api->group(['namespace' => 'Frontend'], function () use ($api) {
            require(__DIR__ . "/Routes/Api/Frontend/Home.php");
            require(__DIR__ . "/Routes/Api/Frontend/Sms.php");
            require(__DIR__ . "/Routes/Api/Frontend/Access.php");
            require(__DIR__ . "/Routes/Api/Frontend/Marketing.php");
            require(__DIR__ . "/Routes/Api/Frontend/Order.php");
            require(__DIR__ . "/Routes/Api/Frontend/Product.php");
        });

        /**
         * Backend Routes
         * Namespaces indicate folder structure
         */
        $api->group(['namespace' => 'Backend', 'prefix' => 'admin'], function () use ($api) {
            require(__DIR__ . "/Routes/Api/Backend/Product.php");
            require(__DIR__ . "/Routes/Api/Backend/Qiniu.php");
            require(__DIR__ . "/Routes/Api/Backend/Marketing.php");
            require(__DIR__ . "/Routes/Api/Backend/Home.php");
            require(__DIR__ . "/Routes/Api/Backend/Merchant.php");
            require(__DIR__ . "/Routes/Api/Backend/Order.php");
            require(__DIR__ . "/Routes/Api/Backend/Agent.php");
        });

    });
});
