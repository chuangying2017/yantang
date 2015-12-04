<?php


$router->group(['prefix' => 'api/admin'], function () {

    resource('categories', 'AdminCategoryController');

});

