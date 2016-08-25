<?php

use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Client\Client::updateOrCreate(['user_id' => 2], [
            "user_id" => 2,
            "nickname" => "bryant",
            "sex" => "",
            "birthday" => "1991-09-02",
            "avatar" => "/static/img/cover-img.jpg"
        ]);
    }
}
