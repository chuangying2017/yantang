database/seeds/Agent.php<?php

use App\Models\Product\Category;
use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class CategoryTableSeeder extends Seeder {

    public function run()
    {
        \App\Models\Product\Category::truncate();

        $categories = [
            ['name' => '护理', 'children' => [
                ['name' => '洗发'],
                ['name' => '护发'],
                ['name' => '脱毛'],
                ['name' => '发膜'],
                ['name' => '牙膏'],
                ['name' => '淋浴'],
                ['name' => '润肤乳'],
            ]],
            ['name' => '彩妆', 'children' => [
                ['name' => '卸妆'],
                ['name' => '防晒'],
                ['name' => 'BB霜'],
                ['name' => '粉饼'],
                ['name' => '睫毛膏'],
                ['name' => '唇彩'],
                ['name' => '腮红'],
                ['name' => '套装']
            ]],
            ['name' => '香氛', 'children' => [
                ['name' => '女士香水'],
                ['name' => '男士香水'],
                ['name' => '中性香水'],
                ['name' => 'Q版香水']
            ]],
            ['name' => '美妆', 'children' => [
                ['name' => '雅诗兰蔻'],
                ['name' => '迪奥'],
                ['name' => '海蓝之恋']
            ]]
        ];

        Category::buildTree($categories); // => true
//        factory(App\Models\Category::class, 5)->create()->each(function($cat){
//            factory(App\Models\Category::class, 5)->create(['pid'=>$cat->id]);
//        });
    }
}
