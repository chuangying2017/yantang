<?php

$router->group([
    'namespace' => 'Image'
], function () use ($router) {
    resource('images', 'ImageController');
});
