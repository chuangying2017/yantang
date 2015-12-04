<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
//use Laracasts\TestDummy\Factory as TestDummy;

class UserTableSeeder extends Seeder {

    public function run()
    {
        \App\Models\User::truncate();
        factory(App\Models\User::class, 50)->create();
    }
}
