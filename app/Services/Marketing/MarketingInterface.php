<?php namespace App\Services\Marketing;

interface MarketingInterface {

    //浏览
    public static function lists($status);

    //查看详情
    public static function show($status);

}
