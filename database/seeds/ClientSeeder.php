<?php

use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Models\Client::updateOrCreate(['user_id' => 6], [
            "nickname" => "用户昵称",
            "email"    => "user@user.com",
            "phone"    => "13246665701",
            "sex"      => "",
            "birthday" => "1991-09-02",
            "avatar"   => "/static/img/cover-img.jpg"
        ]);
    }
}
