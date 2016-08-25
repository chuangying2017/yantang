<?php

use App\Models\Product\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Brand::truncate();
        $data = [
            ['name' => '芭妮兰'],
            ['name' => 'benefit'],
            ['name' => 'maskhouse'],
            ['name' => '兰蔻'],
            ['name' => 'SNP'],
            ['name' => '兰芝'],
            ['name' => 'SK-II'],
            ['name' => '拱辰享'],
            ['name' => '纪梵希'],
            ['name' => '贝德玛'],
            ['name' => '赫拉'],
            ['name' => '阿玛尼'],
            ['name' => '香奈儿'],
        ];

        Brand::insert($data);
    }
}
