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
            'id' => 1,
            'name' => '白云区'
        ];

        \App\Models\District::insert($data);
    }
}
