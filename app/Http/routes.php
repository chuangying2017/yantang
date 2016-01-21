<?php

$api = app('Dingo\Api\Routing\Router');
require(__DIR__ . "/api.php");

/**
 * Frontend Routes
 * Namespaces indicate folder structure
 */
$router->group(['namespace' => 'Frontend'], function () use ($router) {
    require(__DIR__ . "/Routes/Frontend/Payment.php");
    require(__DIR__ . "/Routes/Frontend/Frontend.php");
    require(__DIR__ . "/Routes/Frontend/Access.php");
});


/**
 * Backend Routes
 * Namespaces indicate folder structure
 */
$router->group(['namespace' => 'Backend'], function () use ($router) {

    $router->group(['middleware' => 'auth'], function ($router) {
        $router->group(['prefix' => 'admin'], function () use ($router) {
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
//                require(__DIR__ . "/Routes/Backend/Marketing.php");
                require(__DIR__ . "/Routes/Backend/Order.php");
                require(__DIR__ . "/Routes/Backend/Express.php");
                require(__DIR__ . "/Routes/Backend/Account.php");
                require(__DIR__ . "/Routes/Backend/Image.php");
                require(__DIR__ . "/Routes/Backend/Article.php");
                require(__DIR__ . "/Routes/Backend/Setting.php");
            });

        });
    });
});


/**
 * 测试路由,当 env => local debug => true 有效
 */
require(__DIR__ . "/Routes/test.php");

