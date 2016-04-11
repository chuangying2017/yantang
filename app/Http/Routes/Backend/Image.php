<?php

$router->group([
    'namespace' => 'Image'
], function () use ($router) {
    resource('images', 'ImageController');
    get('images/{id}/delete', 'ImageController@destroy');
    get('images/action/upload', 'ImageController@upload');
});
