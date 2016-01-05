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
                        ]]
                    ]]
                ]]
            ]]
        ];

        App\Models\Agent::buildTree($data);
    }
}
