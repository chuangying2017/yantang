<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

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

        $this->call(BrandSeeder::class);
        $this->call(CategoryTableSeeder::class);
        $this->call(AttributeSeeder::class);
        $this->call(ImageSeeder::class);
        $this->call(NavSeeder::class);
        $this->call(ClientSeeder::class);

        if (env('DB_DRIVER') == 'mysql')
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Model::reguard();
    }
}

