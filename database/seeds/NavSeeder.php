<?php

use Illuminate\Database\Seeder;

class NavSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Nav::truncate();

        $data = [
            ['name' => '护理', 'type' => 'category', 'id' => 1, 'url' => ''],
            ['name' => '美妆', 'type' => 'category', 'id' => 2, 'url' => ''],
            ['name' => '护肤', 'type' => 'category', 'id' => 3, 'url' => ''],
            ['name' => '彩妆', 'type' => 'category', 'id' => 4, 'url' => ''],
            ['name' => '香氛', 'type' => 'category', 'id' => 5, 'url' => ''],
        ];

        \App\Models\Nav::insert($data);
    }
}
