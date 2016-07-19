<?php

use Illuminate\Database\Seeder;

class DistrictSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\District::truncate();

        $data = [
            [
                'name' => '荔湾区',
                'id' => '440103'
            ],
            [
                'name' => '越秀区',
                'id' => '440104'
            ],
            [
                'name' => '海珠区',
                'id' => '440105'
            ],
            [
                'name' => '天河区',
                'id' => '440106'
            ],
            [
                'name' => '白云区',
                'id' => '440111'
            ],
            [
                'name' => '黄埔区',
                'id' => '440112'
            ],
            [
                'name' => '番禺区',
                'id' => '440113'
            ],
            [
                'name' => '花都区',
                'id' => '440114'
            ],
            [
                'name' => '南沙区',
                'id' => '440115'
            ],
            [
                'name' => '萝岗区',
                'id' => '440116'
            ],
            [
                'name' => '从化区',
                'id' => '440117'
            ],
            [
                'name' => '增城区',
                'id' => '440118'
            ],
        ];

        \App\Models\District::insert($data);
    }
}
