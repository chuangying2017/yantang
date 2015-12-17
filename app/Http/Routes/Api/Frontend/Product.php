<?php

$api->resource('categories', 'CategoryController', ['only' => ['index', 'show']]);
$api->resource('brands', 'BrandController', ['only' => ['index']]);
$api->resource('products', 'ProductController', ['only' => ['index', 'show']]);


