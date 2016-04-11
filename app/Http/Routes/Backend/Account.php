<?php

$router->group([
    'namespace' => 'Account'
], function () use ($router) {
    resource('accounts', 'AccountController');
});
