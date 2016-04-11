<?php

use Illuminate\Database\Seeder;

class AgentRateSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\AgentRate::truncate();

        $data = [
            ['name' => '东方丽人', 'level' => \App\Services\Agent\AgentProtocol::AGENT_LEVEL_OF_SYSTEM, 'rate' => 200, 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()],
            ['name' => '省级代理', 'level' => \App\Services\Agent\AgentProtocol::AGENT_LEVEL_OF_PROVINCE, 'rate' => 50, 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()],
            ['name' => '市级代理', 'level' => \App\Services\Agent\AgentProtocol::AGENT_LEVEL_OF_CITY, 'rate' => 100, 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()],
            ['name' => '区县代理', 'level' => \App\Services\Agent\AgentProtocol::AGENT_LEVEL_OF_REGION, 'rate' => 150, 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()],
            ['name' => '门店代理', 'level' => \App\Services\Agent\AgentProtocol::AGENT_LEVEL_OF_STORE, 'rate' => 2000, 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()],
        ];

        \App\Models\AgentRate::insert($data);
    }
}
