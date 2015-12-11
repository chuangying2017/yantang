<?php namespace App\Services\Home;
class BannerService {

    public static function lists()
    {
        $data = [
            'sliders' => [
                [
                    "title"       => "标题1",
                    "url"         => "/",
                    "cover_image" => "/static/img/hero-slide.jpg",
                    "index"       => "1"
                ],
                [
                    "title"       => "标题2",
                    "url"         => "/",
                    "cover_image" => "/static/img/hero-slide2.jpg",
                    "index"       => "2"
                ],
                [
                    "title"       => "标题3",
                    "url"         => "/",
                    "cover_image" => "/static/img/hero-slide3.jpg",
                    "index"       => "3"
                ],
                [
                    "title"       => "标题4",
                    "url"         => "/",
                    "cover_image" => "/static/img/hero-slide4.jpg",
                    "index"       => "4"
                ],
            ],
            'grids'   => [
                [
                    "title"       => "标题1",
                    "url"         => "/",
                    "cover_image" => "/static/img/banner-.jpg",
                    "index"       => "1"
                ],
                [
                    "title"       => "标题2",
                    "url"         => "/",
                    "cover_image" => "/static/img/banner-2.jpg",
                    "index"       => "2"
                ],
                [
                    "title"       => "标题3",
                    "url"         => "/",
                    "cover_image" => "/static/img/banner-3.jpg",
                    "index"       => "3"
                ],
                [
                    "title"       => "标题4",
                    "url"         => "/",
                    "cover_image" => "/static/img/banner-4.jpg",
                    "index"       => "4"
                ],
            ]

        ];

        return $data;
    }
}
