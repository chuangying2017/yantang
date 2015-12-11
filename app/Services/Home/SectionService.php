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
                "products" => Product::whereIn('id', [1, 2, 3, 5, 7, 10])->get(['title', 'id', 'price', 'cover_image'])
            ],
            [
                "title"    => "限时特惠",
                "style"    => "",
                "url"      => "",
                "products" => Product::whereIn('id', [2, 4, 5, 6, 11, 8])->get(['title', 'id', 'price', 'cover_image'])
            ],
        ];

        return $data;
    }

}
