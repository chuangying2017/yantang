<?php

use App\Models\Access\User\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'phone' => '12345678910',
            'status' => 1,
            'confirmed' => 1
        ]);
    }
}
