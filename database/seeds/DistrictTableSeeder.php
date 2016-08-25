<?php

use Illuminate\Database\Seeder;

class DistrictTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('district')->truncate();
        $data = [
            [
                'name' => '荔湾区',
            ],
            [
                'name' => '越秀区',
            ],
            [
                'name' => '海珠区',
            ],
            [
                'name' => '天河区',
            ],
            [
                'name' => '黄埔区',
            ],
            [
                'name' => '白云区',
            ],
            [
                'name' => '番禺区',
            ],
            [
                'name' => '花都区',
            ],
            [
                'name' => '增城区',
            ],
            [
                'name' => '从化区',
            ],
            [
                'name' => '南沙区',
            ],
        ];
        DB::table('district')->insert($data);
    }
}