<?php

use Illuminate\Database\Seeder;

class StationSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Subscribe\Station::truncate();

        \App\Models\Subscribe\Station::create([
            'id' => 1,
            'name' => '白云区服务点',
            'district_id' => 1,
            'geo' => json_encode([
                [23.161885, 113.330456],
                [23.160385, 113.331616],
                [23.160403, 113.333071],
                [23.159571, 113.333680],
                [23.158541, 113.336158],
                [23.157619, 113.336472],
                [23.155486, 113.334585],
                [23.155721, 113.330613],
                [23.158830, 113.326858],
            ]),
            'longitude' => 23.157195,
            'latitude' => 113.330319,
        ]);
        
    }
}
