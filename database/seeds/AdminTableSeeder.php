<?php

use Illuminate\Database\Seeder;
use App\Models\Admin;

use Laracasts\TestDummy\Factory as TestDummy;

class AdminTableSeeder extends Seeder {

    public function run()
    {
        Admin::truncate();
        $data = ['username' => 'admin', 'password' => bcrypt('admin2014')];
        Admin::create($data);
    }
}
