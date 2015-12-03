<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class CategoryTableSeeder extends Seeder
{
    public function run()
    {
        App\Models\Category::truncate();
        factory(App\Models\Category::class, 5)->create()->each(function($cat){
            factory(App\Models\Category::class, 5)->create(['pid'=>$cat->id]);
        });
    }
}
