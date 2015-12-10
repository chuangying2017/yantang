<?php

$router->group([
    'namespace' => 'Product'
], function () use ($router) {
    resource('products', 'ProductController');
    resource('groups', 'GroupController');
    resource('categories', 'CategoryController');
    resource('attributes', 'AttributeController');
});
