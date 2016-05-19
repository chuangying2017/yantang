<?php

use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Product\Group::truncate();

        \App\Models\Product\Group::create([
            'id' => \App\Repositories\Category\CategoryProtocol::ID_OF_SUBSCRIBE_GROUP,
            'name' => '长期订购'
        ]);
    }
}
