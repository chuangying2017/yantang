<?php

$router->group([
    'namespace' => 'Article'
], function () use ($router) {
    resource('articles', 'ArticleController');
});
