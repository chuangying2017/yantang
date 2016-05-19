<?php

use Baum\Extensions\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        if (env('DB_DRIVER') == 'mysql')
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

//        $this->call(BrandSeeder::class);
//        $this->call(CategoryTableSeeder::class);
//        $this->call(AttributeSeeder::class);
//        $this->call(ImageSeeder::class);
//        $this->call(NavSeeder::class);
//        $this->call(ClientSeeder::class);
        $this->call(AccessTableSeeder::class);
        $this->call(GroupSeeder::class);

        if (env('DB_DRIVER') == 'mysql')
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Model::reguard();
        // $this->call(UsersTableSeeder::class);
    }
}

