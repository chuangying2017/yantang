<?php

use Illuminate\Database\Seeder;

class Agent extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Models\Agent::truncate();

        $data = [
            ['name' => '广东省', 'level' => 1, 'children' => [
                ['name' => '深圳市', 'level' => 2, 'children' => [
                    ['name' => '南山区', 'level' => 3, 'children' => [
                        ['name' => '学府路店', 'level' => 4, 'children' => [
                            ['name' => 'bryant', 'level' => 'sales'],
                            ['name' => 'troy', 'level' => 'sales']
                        ]],
                        ['name' => '科技园店', 'level' => 4],
                    ]],
                    ['name' => '罗湖区', 'level' => 3],
                    ['name' => '福田区', 'level' => 3],
                    ['name' => '盐田区', 'level' => 3],
                    ['name' => '龙岗区', 'level' => 3],
                    ['name' => '宝安区', 'level' => 3],
                    ['name' => '光明新区', 'level' => 3],
                ]],
                ['name' => '广州市', 'level' => 2],
                ['name' => '珠海市', 'level' => 2],
                ['name' => '东莞市', 'level' => 2],
                ['name' => '惠州市', 'level' => 2],
                ['name' => '中山市', 'level' => 2],
                ['name' => '汕头市', 'level' => 2],
                ['name' => '韶关市', 'level' => 2],
                ['name' => '梅州市', 'level' => 2],
                ['name' => '江门市', 'level' => 2],
                ['name' => '清远市', 'level' => 2],
                ['name' => '湛江市', 'level' => 2],
                ['name' => '潮州市', 'level' => 2],
            ]]
        ];

        App\Models\Agent::buildTree($data);
    }
}
