<?php

use App\Models\Image;
use Illuminate\Database\Seeder;

class ImageSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Image::truncate();
        $data = [];
        for ($i = 1; $i <= 13; $i++) {
            $data[] = ['media_id' => 'pd-cover-' . $i . '.jpg'];
            $data[] = ['media_id' => 'pd-info-' . $i . '.jpg'];
        }

        Image::insert($data);
    }
}
