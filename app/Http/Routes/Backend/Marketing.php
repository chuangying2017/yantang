<?php

$router->group([
    'namespace' => 'Marketing'
], function () use ($router) {
    resource('coupons', 'MarketingController');
});
