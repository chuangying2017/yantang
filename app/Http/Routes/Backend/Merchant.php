<?php

$router->group([
    'namespace' => 'Merchant'
], function () use ($router) {
    resource('merchants', 'MerchantController');
});
