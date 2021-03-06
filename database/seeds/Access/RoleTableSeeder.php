<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon as Carbon;

class RoleTableSeeder extends Seeder {

    public function run()
    {

        if (env('DB_DRIVER') == 'mysql')
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        if (env('DB_DRIVER') == 'mysql')
            DB::table(config('access.roles_table'))->truncate();
        elseif (env('DB_DRIVER') == 'sqlite')
            DB::statement("DELETE FROM " . config('access.roles_table'));
        else //For PostgreSQL or anything else
            DB::statement("TRUNCATE TABLE " . config('access.roles_table') . " CASCADE");


        foreach(\App\Repositories\Backend\AccessProtocol::roles() as $role_key =>  $role) {
            $role_model = config('access.role');
            $admin = new $role_model;
            $admin->name = $role;
            $admin->all = true;
            $admin->sort = $role_key;
            $admin->created_at = Carbon::now();
            $admin->updated_at = Carbon::now();
            $admin->save();
        }
//
//        //Create admin role, id of 1
//        $role_model = config('access.role');
//        $admin = new $role_model;
//        $admin->name = 'Supervisor';
//        $admin->all = true;
//        $admin->sort = 1;
//        $admin->created_at = Carbon::now();
//        $admin->updated_at = Carbon::now();
//        $admin->save();
//
//        //id = 2
//        $role_model = config('access.role');
//        $user = new $role_model;
//        $user->name = 'Client';
//        $user->sort = 2;
//        $user->created_at = Carbon::now();
//        $user->updated_at = Carbon::now();
//        $user->save();
//
//        //id = 3
//        $role_model = config('access.role');
//        $user = new $role_model;
//        $user->name = 'Station';
//        $user->sort = 3;
//        $user->created_at = Carbon::now();
//        $user->updated_at = Carbon::now();
//        $user->save();
//
//        //id = 4
//        $role_model = config('access.role');
//        $user = new $role_model;
//        $user->name = 'Store';
//        $user->sort = 4;
//        $user->created_at = Carbon::now();
//        $user->updated_at = Carbon::now();
//        $user->save();
//
//        //id = 5
//        $role_model = config('access.role');
//        $user = new $role_model;
//        $user->name = 'Staff';
//        $user->sort = 5;
//        $user->created_at = Carbon::now();
//        $user->updated_at = Carbon::now();
//        $user->save();
//
//        //id = 6
//        $role_model = config('access.role');
//        $user = new $role_model;
//        $user->name = 'StationAdmin';
//        $user->sort = 6;
//        $user->created_at = Carbon::now();
//        $user->updated_at = Carbon::now();
//        $user->save();
//
//        //id = 7
//        $role_model = config('access.role');
//        $user = new $role_model;
//        $user->name = 'StoreAdmin';
//        $user->sort = 7;
//        $user->created_at = Carbon::now();
//        $user->updated_at = Carbon::now();
//        $user->save();


        if (env('DB_DRIVER') == 'mysql')
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
