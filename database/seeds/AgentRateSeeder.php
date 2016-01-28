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
            ['level' => \App\Services\Agent\AgentProtocol::AGENT_LEVEL_OF_SYSTEM, 'rate' => 200, 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()],
            ['level' => \App\Services\Agent\AgentProtocol::AGENT_LEVEL_OF_PROVINCE, 'rate' => 50, 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()],
            ['level' => \App\Services\Agent\AgentProtocol::AGENT_LEVEL_OF_CITY, 'rate' => 100, 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()],
            ['level' => \App\Services\Agent\AgentProtocol::AGENT_LEVEL_OF_REGION, 'rate' => 150, 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()],
            ['level' => \App\Services\Agent\AgentProtocol::AGENT_LEVEL_OF_STORE, 'rate' => 2000, 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()],
        ];

        \App\Models\AgentRate::insert($data);
    }
}
