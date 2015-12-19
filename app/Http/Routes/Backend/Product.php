<?php

$router->group([
    'namespace' => 'Product'
], function () use ($router) {
    resource('products', 'ProductController');
    resource('groups', 'GroupController');
    resource('brands', 'BrandController');
    resource('categories', 'CategoryController');
    resource('attributes', 'AttributeController');
});
