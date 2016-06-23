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
        try {
            DB::connection('mysql_testing')->table('users')->insert([
                'id' => 1,
                'phone' => '12345678910',
                'status' => 1,
                'confirmed' => 1
            ]);
        } catch (\Exception $e) {
            echo 'skip user seeder';
        }
    }
}
