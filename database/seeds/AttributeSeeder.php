<?php

use Illuminate\Database\Seeder;

class AttributeSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Attribute::truncate();
        $data = [
            ['name' => '规格']
        ];

        \App\Models\Attribute::insert($data);
    }
}
