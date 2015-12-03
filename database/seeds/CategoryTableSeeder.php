<?php

use App\Models\Category;
use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class CategoryTableSeeder extends Seeder {

    public function run()
    {
        App\Models\Category::truncate();
        $categories = [
            ['name' => 'TV & Home Theather'],
            ['name' => 'Tablets & E-Readers'],
            ['name' => 'Computers', 'children' => [
                ['name' => 'Laptops', 'children' => [
                    ['name' => 'PC Laptops'],
                    ['name' => 'Macbooks (Air/Pro)']
                ]],
                ['name' => 'Desktops', 'children' => [
                    // These will be created
                    ['name' => 'Towers Only'],
                    ['name' => 'Desktop Packages'],
                    ['name' => 'All-in-One Computers'],
                    ['name' => 'Gaming Desktops']
                ]]
                // This one, as it's not present, will be deleted
                // ['name' => 'Monitors'],
            ]],
            ['name' => 'Cell Phones']
        ];

        Category::buildTree($categories); // => true
//        factory(App\Models\Category::class, 5)->create()->each(function($cat){
//            factory(App\Models\Category::class, 5)->create(['pid'=>$cat->id]);
//        });
    }
}
