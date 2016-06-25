<?php

use App\Models\Access\User\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        if (env('DB_DRIVER') == 'mysql')
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        if (env('DB_DRIVER') == 'mysql')
            DB::table('users')->truncate();
        elseif (env('DB_DRIVER') == 'sqlite')
            DB::statement("DELETE FROM " . 'users');
        else //For PostgreSQL or anything else
            DB::statement("TRUNCATE TABLE " . 'users' . " CASCADE");

        //Add the master administrator, user id of 1
        $users = [
            [
                'username' => 'Admin Istrator',
                'email' => 'admin@admin.com',
                'phone' => '12345678910',
                'password' => bcrypt('123456'),
                'confirmation_code' => md5(uniqid(mt_rand(), true)),
                'confirmed' => true,
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'username' => 'Default User',
                'email' => 'user@user.com',
                'phone' => '12345678911',
                'password' => bcrypt('123456'),
                'confirmation_code' => md5(uniqid(mt_rand(), true)),
                'confirmed' => true,
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ];

        DB::table('users')->insert($users);

        if (env('DB_DRIVER') == 'mysql')
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
