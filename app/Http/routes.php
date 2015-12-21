<?php

$api = app('Dingo\Api\Routing\Router');
require(__DIR__ . "/api.php");

/**
 * Switch between the included languages
 */
$router->group(['namespace' => 'Language'], function () use ($router) {
    require(__DIR__ . "/Routes/Language/Lang.php");
});

/**
 * Frontend Routes
 * Namespaces indicate folder structure
 */
$router->group(['namespace' => 'Frontend'], function () use ($router) {
    require(__DIR__ . "/Routes/Frontend/Frontend.php");
    require(__DIR__ . "/Routes/Frontend/Access.php");
});


/**
 * Backend Routes
 * Namespaces indicate folder structure
 */
$router->group(['namespace' => 'Backend'], function () use ($router) {
    $router->group(['prefix' => 'admin', 'middleware' => 'api.auth'], function () use ($router) {

        /**
         * These routes need view-backend permission (good if you want to allow more than one group in the backend, then limit the backend features by different roles or permissions)
         *
         * Note: Administrator has all permissions so you do not have to specify the administrator role everywhere.
         */
        $router->group(['middleware' => 'access.routeNeedsPermission:view-backend'], function () use ($router) {
            require(__DIR__ . "/Routes/Backend/Access.php");
            require(__DIR__ . "/Routes/Backend/Dashboard.php");
            require(__DIR__ . "/Routes/Backend/Client.php");
            require(__DIR__ . "/Routes/Backend/Product.php");
            require(__DIR__ . "/Routes/Backend/Merchant.php");
//            require(__DIR__ . "/Routes/Backend/Marketing.php");
            require(__DIR__ . "/Routes/Backend/Order.php");
            require(__DIR__ . "/Routes/Backend/Express.php");
            require(__DIR__ . "/Routes/Backend/Account.php");
            require(__DIR__ . "/Routes/Backend/Image.php");
            require(__DIR__ . "/Routes/Backend/Article.php");
        });

    });
});


require(__DIR__ . "/Routes/test.php");

