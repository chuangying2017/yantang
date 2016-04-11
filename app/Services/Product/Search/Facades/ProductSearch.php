<?php namespace App\Services\Product\Search\Facades;



use Illuminate\Support\Facades\Facade;

class ProductSearch extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'product_search';
    }
}
