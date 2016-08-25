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


        /**
         * 初始数据
         */
//        $this->call(AccessTableSeeder::class);
//        $this->call(GroupSeeder::class);
//        $this->call(UserSeeder::class);


        /**
         * 测试数据
         */
//        $this->call(DistrictSeeder::class);
//        $this->call(StationSeeder::class);
        $this->call(CouponSeeder::class);

        if (env('DB_DRIVER') == 'mysql')
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Model::reguard();
        // $this->call(UsersTableSeeder::class);
    }
}

