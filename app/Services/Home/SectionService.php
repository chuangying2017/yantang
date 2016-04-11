<?php namespace App\Services\Home;

use App\Models\Product;
use App\Services\Product\ProductRepository;

class SectionService {

    public static function lists()
    {
        $data = [
            [
                "title"    => "热门推荐",
                "style"    => "",
                "url"      => "",
                "products" => [
                    ['id' => 1, 'cover_image' => 'http://7xp47i.com1.z0.glb.clouddn.com/grid1-1.jpg', 'title' => '', 'price' => 1000],
                    ['id' => 2, 'cover_image' => 'http://7xp47i.com1.z0.glb.clouddn.com/grid1-2.jpg', 'title' => '', 'price' => 1000],
                    ['id' => 3, 'cover_image' => 'http://7xp47i.com1.z0.glb.clouddn.com/grid1-3.jpg', 'title' => '', 'price' => 1000],
                    ['id' => 4, 'cover_image' => 'http://7xp47i.com1.z0.glb.clouddn.com/grid1-4.jpg', 'title' => '', 'price' => 1000],
                    ['id' => 5, 'cover_image' => 'http://7xp47i.com1.z0.glb.clouddn.com/grid1-5.jpg', 'title' => '', 'price' => 1000],
                    ['id' => 6, 'cover_image' => 'http://7xp47i.com1.z0.glb.clouddn.com/grid1-6.jpg', 'title' => '', 'price' => 1000],
                ]
            ],
            [
                "title"    => "限时特惠",
                "style"    => "",
                "url"      => "",
                "products" => [
                    ['id' => 14, 'cover_image' => 'http://7xp47i.com1.z0.glb.clouddn.com/grid2-1.jpg', 'title' => '', 'price' => 1000],
                    ['id' => 13, 'cover_image' => 'http://7xp47i.com1.z0.glb.clouddn.com/grid2-2.jpg', 'title' => '', 'price' => 1000],
                    ['id' => 12, 'cover_image' => 'http://7xp47i.com1.z0.glb.clouddn.com/grid2-3.jpg', 'title' => '', 'price' => 1000],
                    ['id' => 11, 'cover_image' => 'http://7xp47i.com1.z0.glb.clouddn.com/grid2-4.jpg', 'title' => '', 'price' => 1000],
                    ['id' => 10, 'cover_image' => 'http://7xp47i.com1.z0.glb.clouddn.com/grid2-5.jpg', 'title' => '', 'price' => 1000],
                    ['id' => 9, 'cover_image' => 'http://7xp47i.com1.z0.glb.clouddn.com/grid2-6.jpg', 'title' => '', 'price' => 1000],
                ]
            ],
        ];

        return $data;
    }

}
