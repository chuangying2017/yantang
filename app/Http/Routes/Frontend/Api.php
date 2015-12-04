<?php

$router->group(['prefix' => 'api'], function () {

    resource('categories', 'CategoryController', ['only' => ['index', 'show']]);

});
