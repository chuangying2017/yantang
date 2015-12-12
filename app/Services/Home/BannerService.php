<?php namespace App\Services\Home;
class BannerService {

    public static function lists()
    {
        $data = [
            'sliders' => [
                [
                    "title"       => "标题1",
                    "url"         => "/",
                    "cover_image" => "http://7xp47i.com1.z0.glb.clouddn.com/hero-slide.jpg",
                    "index"       => "1"
                ],
                [
                    "title"       => "标题2",
                    "url"         => "/",
                    "cover_image" => "http://7xp47i.com1.z0.glb.clouddn.com/hero-slide2.jpg",
                    "index"       => "2"
                ],
                [
                    "title"       => "标题3",
                    "url"         => "/",
                    "cover_image" => "http://7xp47i.com1.z0.glb.clouddn.com/hero-slide3.jpg",
                    "index"       => "3"
                ],
                [
                    "title"       => "标题4",
                    "url"         => "/",
                    "cover_image" => "http://7xp47i.com1.z0.glb.clouddn.com/hero-slide4.jpg",
                    "index"       => "4"
                ],
            ],
            'grids'   => [
                [
                    "title"       => "标题1",
                    "url"         => "/",
                    "cover_image" => "http://7xp47i.com1.z0.glb.clouddn.com/banner-.jpg",
                    "index"       => "1"
                ],
                [
                    "title"       => "标题2",
                    "url"         => "/",
                    "cover_image" => "http://7xp47i.com1.z0.glb.clouddn.com/banner-2.jpg",
                    "index"       => "2"
                ],
                [
                    "title"       => "标题3",
                    "url"         => "/",
                    "cover_image" => "http://7xp47i.com1.z0.glb.clouddn.com/banner-3.jpg",
                    "index"       => "3"
                ],
                [
                    "title"       => "标题4",
                    "url"         => "/",
                    "cover_image" => "http://7xp47i.com1.z0.glb.clouddn.com/banner-4.jpg",
                    "index"       => "4"
                ],
            ]

        ];

        return $data;
    }
}
