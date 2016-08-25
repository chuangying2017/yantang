<?php

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
//use Laracasts\TestDummy\Factory as TestDummy;
use App\Models\Product\Product;

class ProductTableSeeder extends Seeder
{
    public function run()
    {

//        Product::truncate();
        factory(\App\Models\Product\Product::class, 50)->create();

    }
}
