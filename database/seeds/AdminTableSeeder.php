<?php

use Illuminate\Database\Seeder;
use App\Models\Admin;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class AdminTableSeeder extends Seeder
{
    public function run()
    {
        // TestDummy::times(20)->create('App\Post');
	    Admin::truncate();
	    $data = ['username' => 'admin', 'password' => bcrypt('admin2014')] ;
	    Admin::create($data);


    }
}
