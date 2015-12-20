<?php

$router->group([
    'namespace' => 'Order'
], function () use ($router) {
    resource('orders', 'OrderController');
});
