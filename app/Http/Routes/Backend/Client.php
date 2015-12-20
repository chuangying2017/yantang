<?php

$router->group([
    'namespace' => 'Client'
], function () use ($router) {
    resource('clients', 'ClientController');
});
